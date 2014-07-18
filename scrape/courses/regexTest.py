import re

s = re.findall( '[a-z]+' , '   123abc234  ')
print s[0]