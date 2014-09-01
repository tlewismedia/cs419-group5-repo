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
  public function printUser($users) {

    var_dump($users);

    echo "<br>";
    $i = 1;
    foreach ($users as $user) {
      echo $i.") ".$user->firstName." ".
        $user->lastName.", ".$user->email."<br>";
      $i++;
    }


    //  echo $firstName;
	   // echo $lastName;
	   // echo $email;
  } 
}
