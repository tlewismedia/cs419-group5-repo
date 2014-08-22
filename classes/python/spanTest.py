# import span.py
from datetime import datetime, date, time
from span import Span


d = date(2005, 7, 14)
t = time(12, 30)
start =  datetime.combine(d, t)

d = date(2005, 7, 14)
t = time(12, 45)
end =  datetime.combine(d, t)

span1 = Span(start, end)

d = date(2005, 8, 14)
t = time(12, 30)
start =  datetime.combine(d, t)

d = date(2005, 8, 14)
t = time(12, 45)
end =  datetime.combine(d, t)

span2 = Span(start, end)

print "testing printspans"
spanlist = [span1, span2]
span1.printSpans(spanlist)


# list = [1,2,3,4]

# def procList( the_list):
# 	for num in the_list:
# 		print num

# procList( list)

