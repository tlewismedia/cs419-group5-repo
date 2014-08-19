<?php
////////////////////////////////////////////////////
// SPAN - PHP Conflict class
//
// Version 1.01
//
// Define an isConflict class that can be used to connect
// and communicate with google calender api.
//
// Author: Krishna Kodali
//
// License: N/A
////////////////////////////////////////////////////
class Conflict {
   public function __construct($start,$end) {
        $this->start = $start;
		$this->end = $end;
    }
	
  /*
  * Method to check conflict return bool value
  
  */
  public function isConflict($start="",$end="") {
     if(strtotime($start) < strtotime($end))
	 {
	  return false;
	 }
	 else if(strtotime($start) > strtotime($end))
	 {
	  return false;
	 }
	 else
	 {
	  return true;
	 }
  } 
}
