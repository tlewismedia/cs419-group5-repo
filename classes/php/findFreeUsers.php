<?php
////////////////////////////////////////////////////
// SPAN - PHP Findfreeuser class
//
// Version 1.01
//
// Define an SPAN class that can be used to connect
// and communicate with google calender api.
//
// Author: Krishna Kodali
//
// License: N/A
////////////////////////////////////////////////////
class Findfreeuser extends Span{
  /*
  * Method to findFreeUsers return array value
  *
  */
  public function findFreeUsers($emails=array(),$reqStart="",$reqEnd="") {
     $availableUsers = array();
	 if(count($emails)>0)
         {
             foreach($emails as $key=>$email)
             {
                 $free = true;
                 $eventList = $this->getCalEvents($email, $beg, $done);
                 if (Span::isConflict ($eventStart, $eventEnd, $reqStart, $reqEnd))
                 {
                     $free = false;
                 }
                 if($free = true)
                 {
                     $availableUsers = $email;
                 }
             }
                 
             return $availableUsers;
         }
    }
	
	/* Method to get calender events via api
	*
	*/
	
	public function getCalEvents($user, $startTime, $endTime)
    {
        // in this method we call api to check user events for selected user emial and give start and end time
    }
}
