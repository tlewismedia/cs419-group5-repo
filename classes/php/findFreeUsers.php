<?php
////////////////////////////////////////////////////
//  - PHP Findfreeuser class
//
// Version 1.01
//
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
				 $span1['start'] = $eventStart;
				 $span1['end'] = $eventEnd;
				 $span2['start'] = $reqStart;
				 $span2['end'] = $reqEnd;
                 if (Span::isConflict ($span1, $span2))
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
	

