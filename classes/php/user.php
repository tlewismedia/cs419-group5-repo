<?php
////////////////////////////////////////////////////
// USER - PHP User class
//
// Version 1.01
//
// Define an USER class 
//
// Author: Krishna Kodali
//
// License: N/A
////////////////////////////////////////////////////
class User {
   public function __construct($firstname,$lastname,$email,$events=array()) {
        $this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->events = $events;
    }
	
  /*
  * Method to check conflict return bool value
  
  */
  public function printUser($firstname="",$lastname="",$email="") {
     echo $firstname;
	 echo $lastname;
	 echo $email;
  } 
}
