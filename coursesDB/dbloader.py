import json
import sqlite3

j = json.load(open('items.json'))
db = sqlite3.connect("courses.db")
# print json.dumps(j, sort_keys=True,indent=4, separators=(',', ': '))

# DROP / CREATE TABLES
drop1 = "DROP TABLE IF EXISTS `Courses`;"
drop2 = "DROP TABLE IF EXISTS `Profs`;"
drop3 = "DROP TABLE IF EXISTS `CoursesProfs`;"
create1 = '''CREATE TABLE Courses (
  crn int NOT NULL,
  prof varchar(255) NOT NULL,
  days varchar(255) NOT NULL,
  startDate date NOT NULL,
  endDate date NOT NULL,
  startTime time NOT NULL,
  endTime time NOT NULL,
  PRIMARY KEY (`crn`)
);'''
create2 = '''CREATE TABLE Profs (
    email varchar(255) NOT NULL,
    firstName varchar(255) NOT NULL,
    lastName varchar(255) NOT NULL,
    PRIMARY KEY (email)
);'''
create3 = '''CREATE TABLE CoursesProfs (
    cid int NOT NULL,
    pid int NOT NULL,
    PRIMARY KEY (cid, pid),
    FOREIGN KEY (cid) REFERENCES Courses (crn),
    FOREIGN KEY (pid) REFERENCES Profs (email)
);'''

c = db.cursor()
c.execute(drop1)
c.execute(drop2)
c.execute(drop3)
c.execute(create1)
c.execute(create2)
c.execute(create3)


# populate tables
columns = ['crn', 'prof', 'days', 'startDate', 'endDate', 'startTime', 'endTime']
query = "insert into courses values (?,?,?,?,?,?,?)"

for course in j:
    # http://stackoverflow.com/questions/8811783/convert-json-to-sqlite-in-python-how-to-map-json-keys-to-database-columns-prop
    keys = tuple(course[c] for c in columns)
    
    c.execute(query, keys)
    db.commit() #save database

c.close()
    


