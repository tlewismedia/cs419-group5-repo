
import time
import datetime
import sys
import operator

class Scheduler:

    SPANUNIT = 1  # min
    WINDOWDEFAULT = 30  # days

    def __init__(self, startTime, endTime):
        self.start = time.strptime(startTime, "%d %b %y %H:%M")
        self.end = time.strptime(endTime, "%d %b %y %H:%M")

    def isConflict( span1, span2 ):
        ######################################################################
        # returns: true if no conflicts
        # parameters: span interval, span list events
        # precond: list of users > 0, span length > 0
        if span1.end <= span2.start:
            return False
        elif span2.end <= span1.start:
            return False
        else:
            return True

    def consolidateSpans( self, spans ):
        ######################################################################
        # returns: a list of spans
        # parameters: span list
        # precond: list of users > 0, window start is in future
        for cur in spans:
            for other in spans[1:]:
                if Scheduler.isConflict( cur, other ):
                    cur = Scheduler.combineSpans( cur, other )
                    spans.remove(other)
        return spans
           
    def combineSpans( cur, other ):
            cur.start = min(cur.start, other.start)
            cur.end = max(cur.end, other.end)
            return cur

    def compareSpans( span1, span2 ):
    # Parameters: spans
    # Returns: -1, 1,  for <, >, 0 if overlaps
    # precond: 
        if span1.end < span2.start:
            return -1
        if span1.start > span2.end:
            return 1
        else:
            return 0


def sortSpans(spans):
    spans.sort(key=operator.attrgetter('start'))
    return spans

"""    def findFreeUsers( winStart, winEnd ):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future,
        # window end is reasonable


    def checkForConflicts( interval, events[] ):
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
        # is reasonable"""


def printLegible( timeVal ):
    print time.strftime("%a, %d %b %Y %H:%M:%S", timeVal)



if __name__ == "__main__":
    if not len(sys.argv) == 5:
        print 'usage: python scheduler.py [start] [end] [start] [end] eg "30 Nov 14 16:30" DMY H:M'
    else:
        firstEvent = Scheduler(sys.argv[1], sys.argv[2])
        secEvent = Scheduler(sys.argv[3], sys.argv[4])
        print Scheduler.compareSpans( firstEvent, secEvent )
        a = [firstEvent, secEvent]
        sortSpans(a)
        #events = [firstEvent.makeTuple(), secEvent.makeTuple()]
        #a = sorted(events, key= operator.itemgetter(0))
        printLegible( a[0].start)
        
