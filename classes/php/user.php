<?php
/**
* user class
*/
class User {

   public function __construct($firstName,$lastName,$email) {
      $this->firstName = $firstName;
		  $this->lastName = $lastName;
		  $this->email = $email;
    }
	
  /*
  * Method to check conflict return bool value
  
  */
  public function printUser($firstName, $lastName, $email ) {
    echo $firstName;
	  echo $lastName;
	   echo $email;
  } 
}
