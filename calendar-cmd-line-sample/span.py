import time
from datetime import *
import datetime
import sys
import operator

class Span:

#[startTime, endTime]

    def __init__(self, startTime, endTime):
        if isinstance(startTime, datetime.datetime):
            self.start = startTime
            self.end = endTime
        else:
            self.start = datetime.datetime.strptime(startTime, "%d %b %y %H:%M")
            self.end = datetime.datetime.strptime(endTime, "%d %b %y %H:%M")

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


    def printSpan(self):
        timeConv.printLegible(self.start)
        print ("-"),
        timeConv.printLegible(self.end)
        print " "

    @staticmethod 
    def printSpans( spans):
    # Parameters: List of Spans(startTime, EndTime) or print span
    # Returns:  void
    # Print spans startTime and endTime
        for span in spans:
            span.printSpan()

    @staticmethod 
    def consolidateSpans(spans ):
    ######################################################################
    # returns: a list of spans
    # parameters: span list
    # precond: list of users > 0, window start is in future
        for cur in spans:
            for other in spans[1:]:
                if Span.isConflict( cur, other ):
                    cur = Span.combineSpans( cur, other )
                    spans.remove(other)
        return spans

    @staticmethod 
    def sortSpans( spans):
        spans.sort(key=operator.attrgetter('start'))
        return spans

class timeConv:

    @staticmethod
    def forGoogle( timeVal ):
        # Parameters: span
        # Returns: strings for Google request
        # precond: 
        return timeVal.strftime("%Y-%m-%dT%H:%M:%S.000-07:00")

    @staticmethod
    def printLegible( timeVal ):
        v = timeVal.strftime("%a, %d %b %Y %H:%M")
        print(v),

    @staticmethod    
    def deGoogle(timeVal):
        t = timeVal[:-1]
        t = datetime.datetime.strptime(t, "%Y-%m-%dT%H:%M:%S")
        t -= datetime.timedelta(hours = 7)
        return t

    @staticmethod    
    def stringtodt(timeVal):
        return datetime.datetime.strptime(timeVal, "%d %b %Y %H:%M")

    @staticmethod
    def dttostring( timeVal ):
        v = timeVal.strftime("%a, %d %b %Y %H:%M")
        return v

