<?php
/**
* Responsible for scheduling logic, finding free times / users
*/


// includes here

// * Note: names of private properties or methods should be preceeded by an underscore.

class Scheduler {

	define("SPANUNIT", 1);
	define("WINDOWDEFAULT", 30); //30 days

	function Scheduler() 
	{
		//init
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

	function checkForConflicts( interval, events )
	{
		/**
		* returns: true if no conflicts
		* parameters: span interval, span list events
		* precond: list of users > 0, span length > 0
		*/

	}

	function makeEventList( users, winStart, winEnd )
	{
		/**
		* returns: a list of spans
		* parameters: a list of users, [ window start, window end ]
		* precond: list of users > 0, window start is in future, window end is reasonable
		*/

	}

}

?>