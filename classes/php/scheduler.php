<?php
/**
* Responsible for scheduling logic, finding free times / users
*/


require_once "span.php";
require_once "MyDB.php";
require_once "user.php";

// * Note: names of private properties or methods should be preceeded by an underscore.


define('DEBUG', true);
define("WINDOWDEFAULT", 30); //30 days

class Scheduler {


	function findFreeUsers( $users, $winStart, $winEnd, $client )
	{
		/*****************************************************************************
		* returns: a list of spans
		* parameters:  window start, window end 
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/

		//set win defaults
		// if (!$winStart) $winStart = new DateTime();
		// if (!$winEnd) $winEnd = $winStart->add(new DateInterval('P'.WINDOWDEFAULT.'D'));

		$window = new Span( $winStart, $winEnd );

		$freeUsers = array();

		// for each user, get their events
			// if none of their events conflict with the window, they are included on free user list

		

		foreach ($users as $user){
			$singleUserArr = array($user);
			$userEvents = $this->makeEventList( $winStart, $winEnd, $singleUserArr, $client );




			$include = true;

			foreach ($userEvents as $event) {
				if ($event->isConflict($event, $window )){
					$include = false;
					continue;
				}
			}

			//add to list
			if ($include) $freeUsers[] = $user;
		}

		return $freeUsers;

	}

	function checkForConflicts( $interval, $events )
	{
		/*****************************************************************************
		* returns: true if no conflicts
		* parameters: span interval, span list events
		* precond: list of users > 0, span length > 0
		*
		* used to determine if an interval conflicts with a set of events
		*/

		foreach ($events as $event){
			if (isConflict($event, $interval))
				return false;
		}
		return true;

	}

	function makeEventList( $winStart, $winEnd, $users, $client )
	{
		/*****************************************************************************
		* returns: a list of spans (courses and calendar events of users)
		* parameters: a list of users, [ window start, window end ]
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/
		

		$events = $this->getCalEvents($winStart, $winEnd, $users, $client);


		$this->getCourseEvents($events, $users);



		$consol = $this->consolidateSpans($events);



		

		$spTemp = new Span(null, null);
		$spTemp->sortSpans($consol);



		return $consol;
		
	} //makeEventList


	function findFreeTimes( $winStart, $winEnd, $users, $client )
	{
		/*****************************************************************************
		* returns: a list of spans
		* parameters: a list of users, [ window start, window end ]
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/



		$events = array();
		$freeTimes = array();

		//set win defaults
		if (!$winStart) $winStart = new DateTime();
		if (!$winEnd) $winEnd = $winStart->add(new DateInterval('P'.WINDOWDEFAULT.'D'));

		//make event list
		$events = $this->makeEventList( $winStart, $winEnd, $users, $client );




		// make free time list
		$freeTimes = $this->makeFreeTimes( $winStart, $winEnd, $events);

		return $freeTimes;
		
	} //findFreeTimes()

	function makeFreeTimes( $winStart, $winEnd, $events ){
		/*****************************************************************************
		* returns: Span[] (free times)
		* parameters: dateTime winStart, dateTime winEnd,  Span[] events
		*/
		$count = count($events);
		$freeTimes = array();
		$i = 0;
		
		//check if first event starts the same time window starts

		if (count($events)){ //if there are any events

			if ($winStart == $events[0]->start){
				$start = $events[0]->end;
				$i++;
			} else {
				$start = $winStart;
			}

			// make spans out of times between event
			for ($i; $i < $count; $i++){
				
			   $span = new Span( $start, $events[$i]->start);
			   $freeTimes[] = $span;
			   $start = $events[$i]->end;
			}
			$freeTimes[] = new Span( $start, $winEnd);
		} else {
			//the whole window is open

			$freeTimes[] = new Span( $winStart, $winEnd);
		}
		

		return $freeTimes;

	}

	function getCalEvents ( $winStart, $winEnd, $users, $client ){
		/*****************************************************************************
			* returns: events
			* parameters: event list, list of users
			*/
		


		$gTimeS = $winStart->format(DateTime::ATOM);
		$gTimeE = $winEnd->format(DateTime::ATOM);



		$eventList = array();
		$tz = 'America/Los_Angeles';
		$freebusy = new Google_FreeBusyRequest();
		$freebusy->setTimeMin($gTimeS);
		$freebusy->setTimeMax($gTimeE);
		$freebusy->setTimeZone($tz);
		$item = new Google_FreeBusyRequestItem();



		foreach ($users as $user){

			$item->setId($user->email);
			$freebusy->setItems(array($item));
			$service = new Google_CalendarService($client);  
			$request = $service->freebusy->query($freebusy);
			// return $request;

			
			$cals = $request['calendars'];

			
	   	$busys = $cals[$user->email]['busy']; //get user's busys

	   	
	   	foreach ($busys as $busy) {
	   		$eventList[] = new Span(new DateTime($busy['start']), new DateTime($busy['end']));
	   	}
		}
		
		
		return $eventList;

	}

	

	function getCourseEvents($events, $users){

	// ****************************************************************************
	// * returns: void
	// * parameters: refernce to event list, list of users


	//get events from courses DB for each user

	$output = "";
	$db = new MyDB();

	

	$emails = array();
	foreach ($users as $user){
		$emails[] = $user->email;
	}


  // http://stackoverflow.com/questions/7538927/mysql-select-statement-with-php-array
 


  $userids = "p.email = '".implode("' \n   OR p.email = '",$emails)."'";

  // ChromePhp::log($userids);

	$sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
	INNER JOIN CoursesProfs cp ON cp.cid = c.prof
	INNER JOIN Profs p ON p.email = cp.pid
	WHERE $userids";


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
   		$curDate = $this->getFirstDateForDay($course['startDate'], $letter);
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



	function consolidateSpans ( $spans ){
	 /****************************************************************************
        * returns: a list of spans
        * parameters: span list
        * precond: list of users > 0, window start is in future
        */

			foreach ($spans as $cur){
				foreach ($spans as $other) {
					if ($cur->isConflict( $cur, $other )){
						$new = $this->combineSpans( $cur, $other);
            $spans[array_search($cur, $spans)] = $new;
						if ($cur != $other) unset($spans[array_search($other, $spans)]);
					} //if
				}// foreach
			} //foreach 	
			
			return $spans;
}

function combineSpans( $cur, $other ){
        /****************************************************************************
        * returns: merged span
        * parameters: span, span
        */
        
      $cur->start = min($cur->start, $other->start);
      $cur->end = max($cur->end, $other->end);
      return $cur;
   } //combineSpans
}

?>