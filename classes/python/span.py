import time
from datetime import *
import datetime
import sys

class Span:

#[startTime, endTime]

    def __init__(self, startTime, endTime):
    ######################################################################
    # returns: span: list of 2 datetime objects
    # parameters: start and end in datetime or string (21 Jan 2014 17:30)
    # precond: start < end
        if isinstance(startTime, datetime.datetime):
            self.start = startTime
            self.end = endTime
        else:
            self.start = datetime.datetime.strptime(startTime, "%d %b %Y %H:%M")
            self.end = datetime.datetime.strptime(endTime, "%d %b %Y %H:%M")

    def isConflict( span1, span2 ):
    ######################################################################
    # returns: true if no conflicts, false if overlapping
    # parameters: 2 spans

        if span1.end <= span2.start:
            return False
        elif span2.end <= span1.start:
            return False
        else:
            return True


    def combineSpans( cur, other ):
    ######################################################################
    # returns: 1 span
    # parameters: 2 spans
    # precond: spans overlap
        cur.start = min(cur.start, other.start)
        cur.end = max(cur.end, other.end)
        return cur  

    
    def compareSpans( span1, span2 ):
    ######################################################################
    # Parameters: spans
    # Returns: -1, 1,  for <, >, 0 if overlaps
    # precond: 
        if span1.end < span2.start:
            return -1
        if span1.start > span2.end:
            return 1
        else:
            return 0


    def printSpan(self):
    ######################################################################
    # Parameters: span
    # Returns: void
    # precond:
        timeConv.printLegible(self.start)
        timeConv.printLegible(self.end)

    @staticmethod 
    def printSpans( spans):
    ######################################################################
    # Parameters: List of Spans(startTime, EndTime)
    # Returns:  void
    # Print spans startTime and endTime
        for span in spans:
            Span.printSpan(span)

    @staticmethod 
    def consolidateSpans(spans ):
    ######################################################################
    # returns: a list of spans
    # parameters: span list
    # precond: 
        for cur in spans:
            for other in spans[1:]:
                if Span.isConflict( cur, other ):
                    cur = Span.combineSpans( cur, other )
                    spans.remove(other)
        return spans

    @staticmethod 
    def sortSpans( spans):
    ######################################################################
    # returns: a list of spans
    # parameters: span list
        spans.sort(key=operator.attrgetter('start'))
        return spans

class timeConv:

    @staticmethod
    def forGoogle( timeVal ):
    ######################################################################
    # Parameters: datetime
    # Returns: string for Google request, Pac. time
    # precond: 
        return timeVal.strftime("%Y-%m-%dT%H:%M:%S.000-07:00")

    @staticmethod
    def printLegible( timeVal ):
    ######################################################################
    # Parameters: datetime
    # Returns: void, prints string
    # precond:         
        print timeVal.strftime("%a, %d %b %Y %H:%M")

    @staticmethod    
    def deGoogle(timeVal):
    ######################################################################
    # Parameters: string from Google response
    # Returns: datetime
    # precond:        
        t = timeVal[:-1]
        t = datetime.datetime.strptime(t, "%Y-%m-%dT%H:%M:%S")
        t -= datetime.timedelta(hours = 7)
        return t

    @staticmethod    
    def stringtodt(timeVal):
    ######################################################################
    # Parameters: string (21 Jan 2014 21:30)
    # Returns: datetime
    # precond:        
        return datetime.datetime.strptime(timeVal, "%d %b %Y %H:%M")

