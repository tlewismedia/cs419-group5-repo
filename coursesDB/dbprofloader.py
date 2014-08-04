import json
import sqlite3

pJson = json.load(open('profs.json'))
cpJson = json.load(open('caNamesProfs.json'))
db = sqlite3.connect("courses.db")
c = db.cursor()
# populate prof table
columns = ['email', 'firstName', 'lastName']
query = "insert into profs values (?,?,?)"

for prof in pJson:
    # http://stackoverflow.com/questions/8811783/convert-json-to-sqlite-in-python-how-to-map-json-keys-to-database-columns-prop
    keys = tuple(prof[c] for c in columns)   
    c.execute(query, keys)

# populate prof table
columns = ['caName', 'email']
query = "insert into coursesprofs values (?,?)"

for pair in cpJson:
    keys = tuple(pair[c] for c in columns)   
    c.execute(query, keys)

db.commit() #save database
db.close()
