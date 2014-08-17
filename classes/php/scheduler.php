<?php
/**
* Responsible for scheduling logic, finding free times / users
*/


require_once "classes/Span.php";
require_once "classes/MyDB.php";

// * Note: names of private properties or methods should be preceeded by an underscore.


define('DEBUG', true);

class Scheduler {

	define("SPANUNIT", 1);
	define("WINDOWDEFAULT", 30); //30 days
	
	$_DAYS = array(
    "M" => 0,
    "T" => 1,
    "W" => 2,
    "R" => 3,
    "F" => 4,
    "S" => 5,
    "N" => 6
	);

	$_service;   // google cal api service object
	
	// pass in the api service object on construction
	function Scheduler($service) 
	{
		$_service = $service;
	}

	function findFreeUsers( winStart, winEnd )
	{
		/*****************************************************************************
		* returns: a list of spans
		* parameters:  window start, window end 
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/

	}

	function isConflict( span, span )
	{
		/*****************************************************************************
		* returns: true if no conflicts
		* parameters: span interval, span list events
		* precond: list of users > 0, span length > 0
		*/

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
			if isConflict($event, $interval)
				return false;
		}
		return true;

	}

	function findFreeTimes( $winStart, $winEnd, $users )
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
		$events = makeEventList( $users )

		// make free time list
		$freeTimes = makeFreeTimes( $winStart, $winEnd, $events);

		return $freeTimes
		
	} //findFreeTimes()

	function makeFreeList( $winStart, $winEnd, $events ){
		/*****************************************************************************
		* returns: Span[] (free times)
		* parameters: dateTime winStart, dateTime winEnd,  Span[] events
		*/
		
		$start = $winStart
		for ($i = 0, $events.length() - 1, $i++){
		   $freeTimes[] = new Span( $start, $events[i].getStart());
		   $start = $events[i].getEnd();
		}
		$freeTimes[] = Span( $start, $winEnd);
		

		return $freeTimes


	}

	function getCalEvents(&$events, $users){
		/*****************************************************************************
		* returns: void
		* parameters: event list, list of users
		*/
		// http://stackoverflow.com/questions/17553273/how-to-finish-this-google-calendar-api-v3-freebusy-php-example/17991286#17991286
		foreach ($users as $user){
			$userNames[] = $user.getEmail();
		}

		$freebusy = new Google_FreeBusyRequest();
		$freebusy->setTimeMin($winStart);
		$freebusy->setTimeMax($winEnd);
		$freebusy->setGroupExpansionMax(10);
		$freebusy->setCalendarExpansionMax(10);
		
		$freebusy->setItems = $userNames;
		$result = $service->freebusy->query($freebusy);
		$cals = $result->getCalendars();

		//for each calender in result, for each busy object make an event and add to list
		foreach ($cals as $cal){
			$busys = $cal->getBusy();
			foreach ($busys as $busy){ 
				$events[] = new $span($busy['start'], $busy['end']);
			}
		}
	}

	function makeEventList( $winStart, $winEnd, $users )
	{
		/*****************************************************************************
		* returns: a list of spans (courses and calendar events of users)
		* parameters: a list of users, [ window start, window end ]
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/
		$events = [];
	
		getCalEvents($events, $users);
		getCourseEvents($events, $users);

		//consolidateSpans($events)

		//sortSpans($events)

		return $events;
		
	} //makeEventList

	function getCourseEvents(&$events, &$users){
	{
		/*****************************************************************************
		* returns: void
		* parameters: refernce to event list, list of users
		*/
	//get events from courses DB for each user
		$db = new MyDB();


		//for each user
		//		for each class
		//			for each day of that class

		
		foreach ($users as $user){
			$sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
          		INNER JOIN CoursesProfs cp ON cp.cid = c.prof
          		INNER JOIN Profs p ON p.email = cp.pid
          		WHERE p.email = '".$user->email()."';";



          		// NOTE: TODO add constraint on query for window (low priority, because already contrained to current term)

			$result = $db->query($sel);   

			while( $row = $result->fetchArray()){  //fetches each class row
    			
 				//get the days
 				$daysArr = str_spli($row.days);
 				
 				//add course span for each day
 				foreach ($daysArr as $letter){  //for each day of that class
 					$day = DAYMAP[$letter];
 					
 					$curDate = getFirstDateForDay($row.startDate, $day)  //returns a date
 					
					while (curDate <= $row.endDate) {  //add all spans for that day within the window
						$eventDateTimeStart = convertToDateTime (curDate, row.startTime)  //specify thye time
						$eventDateTimeEnd = convertToDateTime (curDate, row.endTime)
    					$events[] = new Span( $eventDateTimeStart, $eventDateTimeStart )

    					$curDate->add(new DateInterval('P7D')); //days in a week 
    				} //while
    			} //foreach
  			} //while
		} //foreach
	} //getCourseEvents

  
	function getFirstDateforDay( $startDate, $day){
  /****************************************************************************
	* returns: a date marking the first of reoccuring days
	* parameters: startDate (of course), day
	* precond: day must be in DAYMAP
	*
	* ex: If the class is on M and W starting on 08/14/2014, we can use this function to
	*     find the first W.
	*/
		$startDay = getDay($startDate);
		$day1 = $_DAYS[$startDay]; //should return int (0-6)
		$day2 = $_DAYS[$day];
		$daysToAdd = (($day2 + $_DAYS.length()) - $day1) % $_DAYS.length();  //find num days to add


		$startDate->add(new DateInterval('P'.$daysToAdd.'D'));

		return $startDate;
	} //getFirstDateforDay


	function consolidateSpans( $spans ){
        /****************************************************************************
        * returns: a list of spans
        * parameters: span list
        * precond: list of users > 0, window start is in future
        */
        
			foreach ($spans as $cur){
				foreach ($spans as $other) {
					if (isConflict( $cur, $other ){
						$cur = combineSpans( $cur, $other);
						$spans.remove($other);
					} //if
				}// foreach
			} //foreach 	
			
			return $spans

   } //consolidateSpans

   function combineSpans( $cur, $other ){
        /****************************************************************************
        * returns: merged span
        * parameters: span, span
        */
        
			$cur.start = min($cur.start, $other.start)
         $cur.end = max($cur.end, $other.end)
          return $cur
   } //combineSpans
}

?>