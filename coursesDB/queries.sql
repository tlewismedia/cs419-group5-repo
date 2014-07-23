
SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
INNER JOIN CoursesProfs cp ON cp.cid = c.crn
INNER JOIN Profs p ON p.email = cp.pid
WHERE p.firstName = 'Lesley';

