
import time
import datetime
import sys

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

if __name__ == "__main__":
    if not len(sys.argv) == 5:
        print 'usage: python scheduler.py [start] [end] [start] [end] eg "30 Nov 14 16:30" DMY H:M'
    else:
        firstEvent = Scheduler(sys.argv[1], sys.argv[2])
        secEvent = Scheduler(sys.argv[3], sys.argv[4])
        print Scheduler.isConflict(firstEvent, secEvent)
        
