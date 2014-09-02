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
	
	public function printUser($users) {
		/*****************************************************************************
		* prints a list of users
		* parameters: list of Users
		*/

		// var_dump($users);

		echo "<br>";
		$i = 1;
		foreach ($users as $user) {
			echo $i.") ".$user->firstName." ".
				$user->lastName.", ".$user->email."<br>";
			$i++;
		}
	} 
}
