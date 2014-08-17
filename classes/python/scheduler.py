
# Responsible for scheduling logic, finding free times / users

# includes here
import datetime


class Scheduler:

    SPANUNIT = 1  # min
    WINDOWDEFAULT = 30  # days
    DAYS = {
        "M": 0,
        "T": 1,
        "W": 2,
        "R": 3,
        "F": 4,
        "S": 5,
        "N": 6
    }

    # def __init__(self):
        # init

    def findFreeTimes(winStart, winEnd, users):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future,
        # window end is reasonable
        # note: needs to be called with keyword argument like findFreeTime( winStart = 1, winEnd = 2, users=users)

        events = []

        # set window default
        if not winStart:
            winstart = datetime.datetime.now()
        if not winEnd:
            winEnd = winStart + timedelta(days=WINDOWDEFAULT)
        
        # make event list
        events = makeEventsLst( users )

        # make free time list
        freeTimes = makeFreeTimes( winStart, winEnd, events);

        return freeTimes


    def makeFreeTimes( winStart, winEnd, events ):
        ######################################################################
        # returns: Span[] (free times)
        # parameters: dateTime winStart, dateTime winEnd,  Span[] events

        start = winStart
        for i in range(0, events.length-1):
            freeTimes[] = new Span(start, events[i].getStart())
            start = events[i].getEnd()
        }
        $freeTimes[] = new Span( start, winEnd)
        
        return $freeTimes

    def getCalEvents( events, users, service):
        ######################################################################
        # returns: void
        # parameters: event list, list of users, gCal service object
        calendar_list = service.calendarList().list(pageToken=page_token).execute()

        freebusy_query = {
            "timeMin" : '2014-08-10T10:00:00.000-07:00',
            "timeMax" : '2014-08-16T10:00:00.000-07:00',
            "items" :[
              {
                "id" : 'fakeymcfakerson789@gmail.com'
              }
            ]
          }
          request = service.freebusy().query(body=freebusy_query)
          response = request.execute()

          
          events = response['calendars']['fakeymcfakerson789@gmail.com']['busy']
          for e in events:
            print e['start'] + " " + e['end']

            # create new spans and add to events


    def findFreeUsers( winStart, winEnd ):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future,
        # window end is reasonable


    def isConflict( span, span ):
        ######################################################################
        # returns: true if no conflicts
        # parameters: span interval, span list events
        # precond: list of users > 0, span length > 0


    def checkForConflicts( interval, events ):
        ######################################################################
        # returns: true if no conflicts
        # parameters: span interval, span list events
        # precond: list of users > 0, span length > 0

        foreach event in events:
            if isConflict(event, interval):
                return false
                
        return true


    def makeEventList( users, winStart, winEnd ):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future, window end
        # is reasonable
        events = []

        getCalEvents(events, users, service);
        getCourseEvents(events, users);

        # consolidateSpans($events) TODO

        # sortSpans($events) TODO



        return events


    def getCourseEvents( events, users ):
        ######################################################################
        # returns: void
        # parameters: refernce to event list, list of users
          
        db = MyDB()
        conn = db.connect()
        c = conn.cursor()
        
        query = """SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
                INNER JOIN CoursesProfs cp ON cp.cid = c.prof
                INNER JOIN Profs p ON p.email = cp.pid
                WHERE p.email = '"+$user->email()+"';"""
        c.execute(query)
        

        while( row = c.fetchArray()):

            # get the days
            daysArr = list(row.days)

            # add course span for each day
            for letter in daysArr:  #for each day of that class
                day = DAYMAP[letter]
                
                curDate = getFirstDateForDay(row.startDate, day)  #returns a date
                
                while (curDate <= row.endDate):  #add all spans for that day within the window
                    eventDateTimeStart = convertToDateTime (curDate, row.startTime)  #specify thye time
                    eventDateTimeEnd = convertToDateTime (curDate, row.endTime)
                    events[] = new Span( eventDateTimeStart, eventDateTimeStart )

                    curDate = curDate + datetime.timedelta(days=7)  # days in a week 
                


        # print c.fetchall()


    def getFirstDateforDay( startDate, day):
    ######################################################################
    # returns: a date marking the first of reoccuring days
    # parameters: startDate (of course), day
    # 
    # ex: If the class is on M and W starting on 08/14/2014, we can use this function to
    #     find the first W.
    startDay = getDay(startDate)  # TODO: getday
    day1 = DAYS[startDay] #should return int (0-6)
    day2 = DAYS[day]
    daysToAdd = ((day2 + _DAYS.length()) - day1) % DAYS.length()  #find num days to add

    startDate = startDate + datetime.timedelta(days=daysToAdd)

    return startDate;

