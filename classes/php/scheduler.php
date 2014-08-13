<?php
/**
* Responsible for scheduling logic, finding free times / users
*/


require_once "classes/span.php";

// * Note: names of private properties or methods should be preceeded by an underscore.

class Scheduler {

	define("SPANUNIT", 1);
	define("WINDOWDEFAULT", 30); //30 days

	$_service;   // google cal api service object
	
	// pass in the api service object on construction
	function Scheduler($service) 
	{
		$_service = $service;
	}

	function findFreeUsers( winStart, winEnd )
	{
		/**
		* returns: a list of spans
		* parameters: a list of users, [ window start, window end ]
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/

	}

	function isConflict( span, span )
	{
		/**
		* returns: true if no conflicts
		* parameters: span interval, span list events
		* precond: list of users > 0, span length > 0
		*/

	}

	function checkForConflicts( $interval, $events )
	{
		/**
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

	function makeEventList( $users, $winStart, $winEnd )
	{
		/**
		* returns: a list of spans
		* parameters: a list of users, [ window start, window end ]
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/

		//get events from google calendars
		// http://stackoverflow.com/questions/17553273/how-to-finish-this-google-calendar-api-v3-freebusy-php-example/17991286#17991286
		$eventList = array();

		$freebusy = new Google_FreeBusyRequest();
		$freebusy->setTimeMin($winStart);
		$freebusy->setTimeMax($winEnd);
		$freebusy->setGroupExpansionMax(10);
		$freebusy->setCalendarExpansionMax(10);
		
		$freebusy->setItems = $users;
		$result = $service->freebusy->query($freebusy);
		$cals = $result->getCalendars();

		//for each calender in result, for each busy object make an event and add to list
		foreach ($cals as $cal){
			$busys = $cal->getBusy();
			foreach ($busys as $busy){ 
				$events[] = new $span($busy.start, $busy.end);
			}
		}


		//get events from courses DB for each user
		$db = new MyDB();
		
		foreach ($users as $user){
			$sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
          		INNER JOIN CoursesProfs cp ON cp.cid = c.prof
          		INNER JOIN Profs p ON p.email = cp.pid
          		WHERE p.email = '".$user->email()."';";


			$result = $db->query($sel);  //result should have 

			while( $row = $result->fetchArray()){
    			for each row, get the days {
    				for each letter in days
    					day = dayMap[letter]
    					
    					curDate = walkToFirstDateWithDay(row.startDate, day)  //returns a date
    					
						while (curDate <= row.endDate) {
							$eventDateTimeStart = convertToDateTime (start, row.startTime)
							$eventDateTimeEnd = convertToDateTime (start, row.endTime)
	    					$events[] = new event( $eventDateTimeStart, $eventDateTimeStart )
	    					curDate.addDays(7); //days in a week   may need to make function
	    				} //while
    			} // foreach
  			} //while
		} //foreach
	} //makeEventList()

}

?>