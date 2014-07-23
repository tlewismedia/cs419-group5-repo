-- SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `Courses`;
DROP TABLE IF EXISTS `Profs`;
DROP TABLE IF EXISTS `CoursesProfs`;

CREATE TABLE Courses (
  crn int NOT NULL,
  prof varchar(255) NOT NULL,
  days varchar(255) NOT NULL,
  startDate date NOT NULL,
  endDate date NOT NULL,
  startTime time NOT NULL,
  endTime time NOT NULL,
  PRIMARY KEY (`crn`)
);

CREATE TABLE Profs (
    email varchar(255) NOT NULL,
    firstName varchar(255) NOT NULL,
    lastName varchar(255) NOT NULL,
    PRIMARY KEY (email)
);

CREATE TABLE CoursesProfs (
    cid int NOT NULL,
    pid int NOT NULL,
    PRIMARY KEY (cid, pid),
    FOREIGN KEY (cid) REFERENCES Courses (crn),
    FOREIGN KEY (pid) REFERENCES Profs (email)
);



-- INSERT COURSES
INSERT INTO Courses(crn, prof, days, startDate, endDate, startTime, endTime) 
VALUES ('70454', 'Blair, L.', 'MTWR', '07/21/14', '08/15/14', '09:00:00', '09:50:00');
INSERT INTO Courses(crn, prof, days, startDate, endDate, startTime, endTime) 
VALUES ('72507', 'Williams, T.', 'MTWR', '07/21/14', '08/15/14', '10:00:00', '11:50:00');
INSERT INTO Courses(crn, prof, days, startDate, endDate, startTime, endTime) 
VALUES ('70600', 'Blair, L.', 'MTWR', '07/21/14', '08/15/14', '13:00:00', '13:50:00');


-- INSERT PROFS
INSERT INTO Profs(email, firstName, lastName) 
VALUES ('lesley.blair@oregonstate.edu', 'Lesley', 'Blair');
INSERT INTO Profs(email, firstName, lastName) 
VALUES ('willitim@onid.orst.edu', 'Tim', 'Williams');



-- INSERT COURSES/PROFESSOR ASSOCIATIONS
INSERT INTO	CoursesProfs( cid, pid) VALUES ('70454', 'lesley.blair@oregonstate.edu');
INSERT INTO	CoursesProfs( cid, pid) VALUES ('72507', 'willitim@onid.orst.edu');
INSERT INTO	CoursesProfs( cid, pid) VALUES ('70600', 'lesley.blair@oregonstate.edu');

-- -- INSERT AWARDS
-- INSERT INTO Award(name, title, year) VALUES ('Beerfest', 'Best IPA', 2013);
-- INSERT INTO Award(name, title, year) VALUES ('Beerfest', 'Heartiest Beer', 2012);
-- INSERT INTO Award(name, title, year) VALUES ('Beerfest', 'Most Drinkable', 2011);
-- INSERT INTO Award(name, title, year) VALUES ('Beerfest', 'Beerfest Selection', 2013);
-- INSERT INTO Award(name, title, year) VALUES ('Beer Reviews', 'Best for a Romantic Evening', 2010);

-- -- INSERT BREW/AWARD ASSOCIATIONS
-- INSERT INTO BrewAward(bid, aid) VALUES ((SELECT id from Brew WHERE name='Total Domination'), 
--     (SELECT id from Award WHERE name='Beerfest' AND title='Best IPA' AND year='2013'));
-- INSERT INTO BrewAward(bid, aid) VALUES ((SELECT id from Brew WHERE name='Pilot Rock Porter'), 
--     (SELECT id from Award WHERE name='Beerfest' AND title='Heartiest Beer' AND year='2012'));
-- INSERT INTO BrewAward(bid, aid) VALUES ((SELECT id from Brew WHERE name='Drifter Pale Ale'), 
--     (SELECT id from Award WHERE name='Beerfest' AND title='Most Drinkable' AND year='2011'));
-- INSERT INTO BrewAward(bid, aid) VALUES ((SELECT id from Brew WHERE name='Brutal IPA'), 
--     (SELECT id from Award WHERE name='Beer Reviews' AND title='Best for a Romantic Evening' AND year='2010'));
-- INSERT INTO BrewAward(bid, aid) VALUES ((SELECT id from Brew WHERE name='Total Domination'), 
--     (SELECT id from Award WHERE name='Beerfest' AND title='Beerfest Selection' AND year='2013'));
-- INSERT INTO BrewAward(bid, aid) VALUES ((SELECT id from Brew WHERE name='Pilot Rock Porter'), 
--     (SELECT id from Award WHERE name='Beerfest' AND title='Beerfest Selection' AND year='2013'));
-- INSERT INTO BrewAward(bid, aid) VALUES ((SELECT id from Brew WHERE name='Drifter Pale Ale'), 
--     (SELECT id from Award WHERE name='Beerfest' AND title='Beerfest Selection' AND year='2013'));


-- -- INSERT REVIEWERS
-- INSERT INTO Reviewer(firstName, lastName) VALUES ('Barney', 'Gumble');
-- INSERT INTO Reviewer(firstName, lastName) VALUES ('Homer', 'Simpson');
-- INSERT INTO Reviewer(firstName, lastName) VALUES ('Moe', 'Szyslak');

-- -- INSERT REVIEWS
-- INSERT INTO Review (bid, rid, score, comment, date, time) VALUES(
-- 	(SELECT id from Brew WHERE name='Total Domination'),
-- 	(SELECT id from Reviewer WHERE firstName='Barney' AND lastName='Gumble'),
-- 	5,
-- 	'Grows hair on your knuckles.',
-- 	CURDATE(),
-- 	NOW());
-- INSERT INTO Review (bid, rid, score, comment, date, time) VALUES(
-- 	(SELECT id from Brew WHERE name='Drifter Pale Ale'),
-- 	(SELECT id from Reviewer WHERE firstName='Homer' AND lastName='Simpson'),
-- 	4,
-- 	'mmmmmmm!',
-- 	CURDATE(),
-- 	NOW());
-- INSERT INTO Review (bid, rid, score, comment, date, time) VALUES(
-- 	(SELECT id from Brew WHERE name='Pilot Rock Porter'),
-- 	(SELECT id from Reviewer WHERE firstName='Moe' AND lastName='Szyslak'),
-- 	5,
-- 	'Sticks to your ribs.',
-- 	CURDATE(),
-- 	NOW());