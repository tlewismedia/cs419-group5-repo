import sqlite3

class MyClass:
    """A simple example class"""
    i = 12345
    conn = sqlite3.connect('courses.db')
    def connect(self):
        return self.conn

def main():
	x = MyClass()
	connection = x.connect()


if __name__ == "__main__":
    main() 






# class MyDB:
    
   
#     def connect():
#         print "hi"



# def main():
#   print "in main"
#   db = MyDB()
#   db.connect()