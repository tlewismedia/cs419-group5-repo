<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
error_reporting(-1);

require_once "MyDB.php";
include 'ChromePhp.php';



function getFirstDateForDay( $startDate, $letter){
    ######################################################################
    # returns: dateTime for first occurance of day (name) after the startDate
    # parameters: dateTime startDate (of Course), day (letter)
    #
    # ex: If the class is on M and W starting on 08/14/2014, we can use this function to
    #     find the first W.

   // date("N", $timestamp)

	$DAYS = array(
		'M'=> 1,
		'T'=> 2,
		'W'=> 3,
		'R'=> 4,
		'F'=> 5,
		'S'=> 6,
		'N'=> 0
	);

    $day1 = date('N',date_timestamp_get($startDate));
    $day2 = $DAYS[$letter];

    $delta = ($day2 + 7 - $day1) % 7;  //find num days to add
    $startDate->modify('+'.$delta.' day');
    // $newDate = $startDate + datetime.timedelta(days=delta);

    return $startDate;

}


function getCourseEvents($events, $users){

	// ****************************************************************************
	// * returns: void
	// * parameters: refernce to event list, list of users


	//get events from courses DB for each user

  



	$output = "";
	$db = new MyDB();


	//for each user
	//		for each class
	//			for each day of that class
	// WHERE p.email = '".$users[0]->email."';";

	// foreach ($users as $user){

  // http://stackoverflow.com/questions/7538927/mysql-select-statement-with-php-array
 


  $userids = "p.email = '".implode("' \n   OR p.email = '",$users)."'";

  ChromePhp::log($userids);

	$sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
	INNER JOIN CoursesProfs cp ON cp.cid = c.prof
	INNER JOIN Profs p ON p.email = cp.pid
	WHERE $userids";

  // $sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
  // INNER JOIN CoursesProfs cp ON cp.cid = c.prof
  // INNER JOIN Profs p ON p.email = cp.pid
  // WHERE p.email = '";



	// NOTE: TODO add constraint on query for window (low priority, because already contrained to current term)

	$result = $db->query($sel);


   $courses = array();

   $i = 0;

   //get all the coursess for each class, store in courses
  while($res = $result->fetchArray(SQLITE3_ASSOC)){
		$courses[$i]['days'] = str_split($res['days']); //convert to array of char


		$courses[$i]['startDate'] = DateTime::createFromFormat('m/d/y', $res['startDate']);


		$courses[$i]['endDate'] = DateTime::createFromFormat('m/d/y',$res['endDate']);
		
    //format start time
    if (strlen($res['startTime']) == 3) {
			$res['startTime'] = '0'.$res['startTime']; //prepend 0 if only 3 digits
		}
		$tempTime = substr($res['startTime'], 0, 2).':'.substr($res['startTime'], 2, 2);
		$courses[$i]['startTime'] = DateTime::createFromFormat('H:i', $tempTime);
		

    //format start time
    if (strlen($res['endTime']) == 3) {
      $res['endTime'] = '0'.$res['endTime']; //prepend 0 if only 3 digits
    }

    $tempTime = substr($res['endTime'], 0, 2).':'.substr($res['endTime'], 2, 2);
		$courses[$i]['endTime'] = DateTime::createFromFormat('H:i', $tempTime);

		$i++;
  }

  //checking starttimes
  foreach ($courses as $course) {

  }


   $i = 0;
   $spans = array();
   foreach ($courses as $course) {

   	foreach ($course['days'] as $letter){ //for each letter day of course
   		$curDate = getFirstDateForDay($course['startDate'], $letter);
   		while ($curDate <= $course['endDate']){  #add all spans for that day within the window

                // date = $curDate.date()
                $year = $curDate->format('Y');
                $month = $curDate->format('m');
                $day = $curDate->format('d');
                $shour = $course['startTime']->format('H');
                $smin = $course['startTime']->format('i');
                $ehour = $course['endTime']->format('H');
                $emin = $course['endTime']->format('i');

                $startString = $year.$month.$day.$shour.$smin;
                $eventDateTimeStart = DateTime::createFromFormat('YmdHi', $startString);

                $endString = $year.$month.$day.$ehour.$emin;
                $eventDateTimeEnd = DateTime::createFromFormat('YmdHi', $endString);
                $spans[$i]['start'] =  $eventDateTimeStart;
                $spans[$i]['end'] =  $eventDateTimeEnd;


                // newSpan = Span( eventDateTimeStart, eventDateTimeEnd )

                // events.append(newSpan)

                
                $curDate->modify('+7 day');

   			$i++;
   		}//while
   	} //each
  } //each
  return $spans;
}//getCourseEvents
?>
<?php ob_start(); //output buffering required to use chrome logger, prevent output?>  
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test getCourseEvents</title>
</head>
<body>
<?php
  
  
	$events = array();

  $users = array('fakeemail1@gmail.com', 'fakeemail2@gmail.com', 'fakeemail3@gmail.com', 'fakeemail4@gmail.com');
  $spans = getCourseEvents($events, $users);

  foreach ($spans as $span){
    echo 'start: ';
    echo date_format($span['start'], 'Y/m/d H:i');
    echo "  end: ";
    echo date_format($span['end'], 'Y/m/d H:i');
    echo "<br>";
  }
   



   // $span = events[0]
   // $span.printSpans(events)
?>
</body>
</html>
<?php ob_flush(); ?>