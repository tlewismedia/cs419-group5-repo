<?php
/**
* Responsible for scheduling logic, finding free times / users
*/

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('courses.db');
    }
}




// EXAMPLE USAGE:

// $db = new MyDB();
		
// 		foreach ($users as $user){
// 			$sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
//           		INNER JOIN CoursesProfs cp ON cp.cid = c.prof
//           		INNER JOIN Profs p ON p.email = cp.pid
//           		WHERE p.email = '".$user->email()."';";


// 			$result = $db->query($sel);

?>