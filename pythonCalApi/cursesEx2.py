#! /usr/bin/env python

import curses

stdscr = curses.initscr()
curses.noecho()
curses.cbreak()
stdscr.keypad(1)

stdscr.addstr(0,0, "Hello")

curses.nocbreak(); stdscr.keypad(0); curses.echo()
curses.endwin()
