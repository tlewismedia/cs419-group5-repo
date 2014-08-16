
# Responsible for scheduling logic, finding free times / users

# includes here
import datetime

class Scheduler:

    SPANUNIT = 1  # min
    WINDOWDEFAULT = 30  # days

    def __init__(self):
        # init


    def findFreeTimes( winStart, winEnd, users ):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future,
        # window end is reasonable
        # note: needs to be called with keyword argument like findFreeTime( winStart = 1, winEnd = 2, users=users)

        events = []
        freeTimes = []


        if not winStart:
            winstart = datetime.datetime.now()
        if not winEnd:
            winEnd = winStart + timedelta(days=WINDOWDEFAULT)
        

        events = makeEventsLst( users )

        # add empty space to freeTimes 
        start = winStart
        for i in xrange(1, events.length - 1):
            freeTimes[] = Span( start, events[i].start)
            start = events[i].end
        freeTimes[] = Span( start, winEnd)


        return freeTimes




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

        # add calender events



        # add courses



        # consolidate and sort



        return events




