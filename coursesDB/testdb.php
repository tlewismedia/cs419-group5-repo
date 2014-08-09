<?php
/*******************************************************************************
testdb.php - tests the courses database
 *
 * note: THIS IS NOT SECURE!
 *******************************************************************************/

define('DATABASE', 'courses.db');
define('DEBUG', true);

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('courses.db');
    }
}

if (!empty($_POST["lastname"])) {
  $ln = $_POST["lastname"];
  echo "last name: ".$ln."\n";

  $sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
          INNER JOIN CoursesProfs cp ON cp.cid = c.prof
          INNER JOIN Profs p ON p.email = cp.pid
          WHERE p.lastName = '".$ln."';";

  $db = new MyDB();
  $result = $db->query($sel);


  while( $row = $result->fetchArray()){
    var_dump($row); 
  }


}

echo "

<form action='testdb.php' method='POST'>
    <label>Enter a last name:</label>
    <input  name='lastname'>
    <input type='submit' value='Submit'>
</form>


"

?>
