# Responsible for connecting to database

import sqlite3

class MyDB:
    conn = sqlite3.connect('courses.db')
   
    def connect(self):
        return self.conn


# TEST:
# def main():
#   db = MyDB()
#   conn = db.connect()
#   c = conn.cursor()
#   c.execute('SELECT * FROM Courses')
#   print c.fetchall()

# if __name__ == "__main__":
#     main()      