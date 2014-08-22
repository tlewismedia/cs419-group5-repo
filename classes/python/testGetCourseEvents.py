import datetime
from span import Span
from MyDB import MyDB
import sqlite3

DAYS = {
        "M": 1,
        "T": 2,
        "W": 3,
        "R": 4,
        "F": 5,
        "S": 6,
        "N": 0
    }

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
                




# TEST getCourseEvents:
def main():
    events = []
    users = ['fakeemail1@gmail.com', 'fakeemail2@gmail.com', 'fakeemail2@gmail.com', 'fakeemail4@gmail.com']

    getCourseEvents(events, users)

    span = events[0]
    span.printSpans(events)


if __name__ == "__main__":
    main() 
