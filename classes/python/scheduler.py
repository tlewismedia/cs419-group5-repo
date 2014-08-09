
# Responsible for scheduling logic, finding free times / users

# includes here


class Scheduler:

    SPANUNIT = 1  # min
    WINDOWDEFAULT = 30  # days

    def __init__(self):
        # init


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


    def makeEventList( users, winStart, winEnd ):
        ######################################################################
        # returns: a list of spans
        # parameters: a list of users, [ window start, window end ]
        # precond: list of users > 0, window start is in future, window end
        # is reasonable
