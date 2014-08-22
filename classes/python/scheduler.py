
# Responsible for scheduling logic, finding free times / users

# includes here
import datetime


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
            
            curDate = getFirstDateForDay(startDate, letter)  #returns a datetime
            
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
                


    def getFirstDateForDay( startDate, letter):
    ######################################################################
    # returns: dateTime for first occurance of day (name) after the startDate
    # parameters: dateTime startDate (of Course), day (letter)
    # 
    # ex: If the class is on M and W starting on 08/14/2014, we can use this function to
    #     find the first W.
   
    day1 = int(datetime.datetime.strftime( startDate, "%w"))
    day2 = DAYS[letter]

    delta = (day2 + 7 - day1) % 7  #find num days to add
    newDate = startDate + datetime.timedelta(days=delta)

    # print newDate

    return newDate



