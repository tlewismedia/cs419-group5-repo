import time
import datetime
import sys

class Span:

#[startTime, endTime]

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

    def printSpans(spans):
    # Parameters: List of Spans(startTime, EndTime) or print span
    # Returns:  void
    # Print spans startTime and endTime
        for span in spans:
            printLegible(span.start)
            printLegible(span.end)
    
    def compareTo( span1, span2 )
    # Parameters: spans
    # Returns: -1, 1,  for <, >, 0 if overlaps
    # precond: 
        if span1.end < span2.start
            return -1
        if span1.start > span2.end
            return 1
        else
            return 0


def printLegible( timeVal ):
    print time.strftime("%a, %d %b %Y %H:%M:%S", timeVal)


