<?php
////////////////////////////////////////////////////
// SPAN - PHP SPAN class
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
class Span {


   public function __construct($span1=array(),$span2=array()) {
       $this->span1 = $span1;
               $this->span2 = $span2;
   }
	

 /*
  * Method to check conflict
  */
  
  public function isConflict($span1="",$span2="") {
     if(strtotime($span1['end']) < strtotime($span2['start']))
	 {
	  return false;
	 }
	 else if(strtotime($span2['end']) > strtotime($span1['start']))
	 {
	  return false;
	 }
	 else
	 {
	  return true;
	 }
  } 
  
	
  /*
  * Method to check compareTo
  */
  public function compareTo($span1,$span2) {
     if(strtotime($span1.start) < strtotime($span2.end))
	 {
	  return "-1";
	 }
	 else if(strtotime($span2.start) > strtotime($span1.end))
	 {
	  return "1";
	 }
	 else
	 {
	  return "0";
	 }
  }
  
  
  /* function to print span
  *
  */
  public function printSpans($span = array())
  {
	echo $span['start'];
	echo $span['end'];
  }
  
 /* function to print span
  *
  */
  public function combineSpans($current = array(),$other = array())
  {
	if(strtotime($current['start']) < strtotime($other['start']))
	{
	 $result['start'] = $current['start'];
	}
	else
	{
	 $result['start'] = $other['start'];
	}
	
	if(strtotime($current['end']) < strtotime($other['end']))
	{
	 $result['end'] = $other['end'];
	}
	else
	{
	 $result['end'] = $current['end'];
	}
	return $result;
  }
  
  // usage//
  /*
  $span = new Span();
  $result = $span->compareTo($start,$end);
  */
  
  
  /*
  Span trime increment by 1 unit // 1 unit = 1 minute assumed
  $start (2014-08-12 10:12:12)
  */
  public function spanIncrEnd($start="") {
	 $time = strtotime($start);
     return date("Y-m-d H:i:s", strtotime('+1 minutes', $time));
  }
  
  /*
  Span trime decrement by 1 unit // 1 unit = 1 minute assumed
  $start (2014-08-12 10:12:12)
  */
  public function spanDecEnd($start="") {
	 $time = strtotime($start);
     return date("Y-m-d H:i:s", strtotime('-1 minutes', $time));
  }
  
  /*
  *
  */
  
  public function consolidateSpans($self,$span)
    {
        foreach($span as $cur)
        {
            if(isConflict($cur, $other))
            {
                $cur = $this->combineSpans($cur,$other );
            }
        }
        
        return $cur;
    }
	
	
	/* function for sort span
	*
	*
	*/
	public function sortSpans($spans = array())
	{
	    foreach($spans as $span)
		{
		   $timespampspan[] = strtotime($span);
		}
		$newspan = asort($timespampspan);
		foreach($newspan as $new)
		{
		   $returnspan[] = date('Y-m-d',$new);
		}
		return $returnspan;		
	}
	
  
}
