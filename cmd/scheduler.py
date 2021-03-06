
# Responsible for scheduling logic, finding free times / users

# includes here
import datetime
from span import *
from MyDB import MyDB
import httplib2
import os
import sys

import json
import pprint

from apiclient import discovery
from oauth2client import file
from oauth2client import client
from oauth2client import tools

class Scheduler:

    SPANUNIT = 1  # min
    WINDOWDEFAULT = 30  # days
    DAYS = {
        "M": 1,
        "T": 2,
        "W": 3,
        "R": 4,
        "F": 5,
        "S": 6,
        "N": 0
    }
# def __init__(self):
        # init
    currentTime = datetime.datetime.now()
    # ("Pacific") may be needed
    in30days = currentTime + datetime.timedelta(days = 30)
    # def __init__(self):
        # init

    # CLIENT_SECRETS is name of a file containing the OAuth 2.0 information for this
    # application
    CLIENT_SECRETS = os.path.join(os.path.dirname(__file__), 'client_secrets.json')

    FLOW = client.flow_from_clientsecrets(CLIENT_SECRETS,
      scope=[
          'https://www.googleapis.com/auth/calendar',
          'https://www.googleapis.com/auth/calendar.readonly',
        ],
        message=tools.message_if_missing(CLIENT_SECRETS))

    @staticmethod
    def getCalEvents(user, startTime, endTime):

        storage = file.Storage('sample.dat')
        credentials = storage.get()
        if credentials is None or credentials.invalid:
            credentials = tools.run_flow(FLOW, storage, flags)

        http = httplib2.Http()
        http = credentials.authorize(http)

        service = discovery.build('calendar', 'v3', http=http)

        try:
            page_token = None
            while True:
                calendar_list = service.calendarList().list(pageToken=page_token).execute()

                freebusy_query = {
                  "timeMin" : startTime,
                  "timeMax" : endTime,
                  "items" :[
                    {
                      "id" : user
                    }
                  ]
                }
                request = service.freebusy().query(body=freebusy_query)
                response = request.execute()

                busyList = []
                events = response['calendars'][user]['busy']
                for e in events:
                    #print e['start'] + " " + e['end']
                    s = timeConv.deGoogle (e['start'])
                    en = timeConv.deGoogle (e['end'])
                    x = Span(s, en)
                    busyList.append(x)


                page_token = calendar_list.get('nextPageToken')
                if not page_token:
                    return busyList

        except client.AccessTokenRefreshError:
            print ("The credentials have been revoked or expired, please re-run"
              "the application to re-authorize")

    @staticmethod
    def findFreeTimes(users, winStart = currentTime, winEnd = in30days):
       ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future,
        # window end is reasonable

        events = []

        # set window if end is < start
        if winEnd < winStart:
            winEnd = winStart + datetime.timedelta(days = 30)

        # make event list
        events = Scheduler.makeEventList( users, winStart, winEnd )

        # make free time list
        freeTimes = Scheduler.makeFreeTimes( winStart, winEnd, events);

        return freeTimes

    @staticmethod
    def makeFreeTimes( winStart, winEnd, events ):
            ######################################################################
            # returns: Span[] (free times)
            # parameters: dateTime winStart, dateTime winEnd,  Span[] events
            # include time before first listed event and time after so winStart, winEnd must be used

        if len(events) < 1:
            span = Span(winStart, winEnd)
            freeTimes = [span]
        else:
            freeTimes = []
            for event in events:

                if winStart < event.start:
                    freeSpan = Span(winStart, event.start)
                    freeTimes.append(freeSpan)
                winStart = event.end
            if winStart < winEnd:
                freeSpan = Span( winStart, winEnd)
                freeTimes.append(freeSpan)

        return freeTimes

    @staticmethod
    def getCourseEvents( users ):
    ######################################################################
    # returns: spans
    # parameters: refernce to event list, list of users

        db = MyDB()
        conn = db.connect()
        c = conn.cursor()

        # http://stackoverflow.com/questions/283645/python-list-in-sql-query-as-parameter
        placeholder= '?'
        placeholders= ', '.join(placeholder for unused in users)

        query = """SELECT  c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
                INNER JOIN CoursesProfs cp ON cp.cid = c.prof
                INNER JOIN Profs p ON p.email = cp.pid
                WHERE p.email IN (%s)""" % placeholders

        results = c.execute(query, users)
        for row in results:
            # print row

            # format data
            daysArr = list(row[0])
            startDate = datetime.datetime.strptime(row[1], "%x");
            endDate = datetime.datetime.strptime(row[2], "%x");

            startTime = str(row[3])

            if len(startTime) == 3: #need four digits
                startTime = '0'+startTime
            startTime = datetime.time( int(startTime[0:2]), int(startTime[2:4]))

            endTime = str(row[4])
            if len(endTime) == 3: #need four digits
                endTime = '0'+endTime
            endTime = datetime.time( int(endTime[0:2]), int(endTime[2:4]))

            # add course span for each day
            for letter in daysArr:  #for each day of that class

                curDate = Scheduler.getFirstDateForDay(startDate, letter)  #returns a datetime
                events =[]
                while (curDate <= endDate):  #add all spans for that day within the window

                    date = curDate.date()

                    # print type(date)
                    # print type(startTime)

                    year = date.year
                    month = date.month
                    day = date.day
                    shour = startTime.hour
                    sminute = startTime.minute
                    ehour = endTime.hour
                    eminute = endTime.minute

                    eventDateTimeStart = datetime.datetime.combine(datetime.date(year, month, day), datetime.time( shour, sminute))
                    eventDateTimeEnd = datetime.datetime.combine(datetime.date(year, month, day), datetime.time( ehour, eminute))

                    newSpan = Span( eventDateTimeStart, eventDateTimeEnd )

                    events.append(newSpan)

                    curDate = curDate + datetime.timedelta(days=7)  # days in a week
            return events


    @staticmethod
    def getFirstDateForDay( startDate, letter):
    ######################################################################
    # returns: dateTime for first occurance of day (name) after the startDate
    # parameters: dateTime startDate (of Course), day (letter)
    #
    # ex: If the class is on M and W starting on 08/14/2014, we can use this function to
    #     find the first W.

        day1 = int(datetime.datetime.strftime( startDate, "%w"))
        day2 = Scheduler.DAYS[letter]

        delta = (day2 + 7 - day1) % 7  #find num days to add
        newDate = startDate + datetime.timedelta(days=delta)

        # print newDate
        return newDate

    @staticmethod
    def findFreeUsers(emails, reqStart, reqEnd):
        ######################################################################
        # returns: a list of available users
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future,
        # window end is reasonable
        availableUsers =[]
        span = Span(reqStart, reqEnd)
        reqStart = timeConv.forGoogle(reqStart)
        reqEnd = timeConv.forGoogle(reqEnd)
        for email in emails:
            free = True
            user = [email]
            eventList = Scheduler.getCalEvents(email, reqStart, reqEnd)
            #for event in eventList:
                #event.printSpan()
            courseList = Scheduler.getCourseEvents(user)
            if isinstance(courseList, list):
                eventList = eventList + courseList
            for event in eventList:

                if Span.isConflict (span, event):
                    free = False
            if free == True:
                availableUsers.append(email)
        return availableUsers

    @staticmethod
    def makeEventList( users, winStart, winEnd ):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future, window end
        # is reasonable

        span = Span(winStart, winEnd)
        events = [span]
        del events[0]
        gStart = timeConv.forGoogle(winStart)
        gEnd = timeConv.forGoogle(winEnd)
        for user in users:
            eventList = Scheduler.getCalEvents(user, gStart, gEnd)
            if isinstance(eventList, list):
                events += eventList
        courseList = Scheduler.getCourseEvents(users)
        if isinstance(courseList, list):
            eventList += courseList
        events += eventList

        Span.sortSpans(events)

        events = Scheduler.eventsInSpan (events, winStart, winEnd)

        Span.consolidateSpans(events)
        #for event in events:
            #event.printSpan()
            #print isinstance(event, Span)
        Span.sortSpans(events)
        return events

    @staticmethod
    def eventsInSpan( events, winStart, winEnd ):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future, window end
        # is reasonable
        span = Span(winStart, winEnd)
        inSpan = [span]
        del inSpan[0]
        i = 0
        for event in events:
            #print i
            #i += 1
            #print timeConv.dttostring(event.start)
            #print timeConv.dttostring(event.end)
            if event.start >= winStart and event.end <= winEnd:
                inSpan.append(event)
        events = inSpan
        return events

    @staticmethod
    def curseFreeUsers(users, winStart, winEnd):
        start = datetime.datetime.strptime(winStart, "%m/%d/%Y %H:%M")
        end = datetime.datetime.strptime(winEnd, "%m/%d/%Y %H:%M")
        freeUsers = Scheduler.findFreeUsers(users, start, end)
        userstring = ""
        for user in freeUsers:
            userstring += user + ", "
        return userstring

    @staticmethod
    def curseFreeTimes(users, winStart = currentTime, winEnd = in30days):
        if isinstance (winStart, datetime.datetime):
            start = winStart
        else:
            start = datetime.datetime.strptime(winStart, "%m/%d/%Y %H:%M")
        if isinstance (winEnd, datetime.datetime):
            end = winEnd
        else:
            end = datetime.datetime.strptime(winEnd, "%m/%d/%Y %H:%M")
        times = Scheduler.findFreeTimes(users, start, end)
        timestring = ""
        for time in times:
            s = timeConv.dttostring(time.start)
            e = timeConv.dttostring(time.end)
            cat = s + " - " + e +"\n"
            timestring += cat

        s = timeConv.dttostring(start)
        e = timeConv.dttostring(end)
        return "All users are available these times between " + s + " and " + e + ":\n" +timestring
            
        

