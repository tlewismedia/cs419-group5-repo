import curses
from scheduler import *

stdscr = curses.initscr()
stdscr.keypad(1)
curses.echo()
users = []


while 1:
    stdscr.clear()
    row = 0
    stdscr.addstr(row, 0, "Options u: add users, s: start time, e: end time, w: print who is available, t: print times available, Esc to quit",
              curses.A_REVERSE)
    stdscr.refresh()
    row+=2
    c = stdscr.getch(row, 0)
    if c == 27:
        break  # Exit the while() when Esc is pressed
    if c == ord('u'):
        more = True
        while more == True:
            stdscr.refresh()
            row+=1
            stdscr.addstr(row, 0, "Enter User: ", curses.A_REVERSE)
            s = stdscr.getstr(row,15)
            users.append(s)
            row+=1
            stdscr.addstr(row, 0, "User added: " + s + " Are there more users? y/n",
                  curses.A_REVERSE)
            stdscr.refresh()
            row+=1
            resp = stdscr.getch(row, 0)
            if resp == ord('y'):
                more= True
            else:
                more = False
    if c == ord('s'):
        stdscr.refresh()
        row+=1
        stdscr.addstr(row, 0, "Enter an start date and time:\n", curses.A_REVERSE)
        start = stdscr.getstr(row+1,0)
        row+=1
    if c == ord('e'):
        stdscr.refresh()
        row+=1
        stdscr.addstr(row, 0, "Enter an end date and time:\n", curses.A_REVERSE)
        end = stdscr.getstr(row+1,0)
        row+=1
    if c == ord('w'):
        stdscr.refresh()
        row+=1
        try:
        who = Scheduler.curseFreeUsers(users, start, end)
        stdscr.addstr(row, 0, "These users are available between " + start + " and " + end +":\n"+ s, curses.A_REVERSE)
        row+=3
        stdscr.refresh()
        stdscr.getch()
    if c == ord('t'):
        stdscr.refresh()
        row+=1
        try: 
            times = Scheduler.curseFreeTimes(users, start, end)
        except NameError: 
            times = Scheduler.curseFreeTimes(users)
        stdscr.addstr(row, 0, times, curses.A_REVERSE)
        row+= (len(times)/35)
        stdscr.refresh()
        stdscr.getch()

curses.nocbreak(); stdscr.keypad(0); curses.echo()

curses.endwin()
