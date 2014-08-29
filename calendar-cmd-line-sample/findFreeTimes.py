from span import *
from scheduler import *

import datetime


if __name__ == '__main__':
    email = ["testy1cs419g5@gmail.com", "testy2cs419g5@gmail.com", "testy3cs419g5@gmail.com", "testy4cs419g5@gmail.com"]

    reqStart = timeConv.stringtodt("15 Aug 2014 02:00")
    reqEnd= timeConv.stringtodt("30 Aug 2014 23:59")
    print("Event List")
    freeTimes = Scheduler.findFreeTimes(email)
    print("__________Free Times____________")
    Span.printSpans(freeTimes)
  
