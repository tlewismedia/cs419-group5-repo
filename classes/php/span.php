<?php
////////////////////////////////////////////////////
// SPAN - PHP SPAN class
//
// Version 1.01
//
// Define an SPAN class that can be used to connect
// and communicate with google calender api.
//
// Author: Kodali Krishna
//
// License: N/A
////////////////////////////////////////////////////
class Span {


   public function __construct($span1=array(),$span2=array()) {
		if($span1['end'] == ""){$span1['end'] = date('Y-m-d H:i:s');}
		if($span1['start'] == ""){$span1['start'] = date('Y-m-d H:i:s');}
		if($span2['end'] == ""){$span2['end'] = date('Y-m-d H:i:s');}
		if($span2['start'] == ""){$span2['start'] = date('Y-m-d H:i:s');}
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
  public function compareSpans($span1="",$span2="") {
     if(strtotime($span1['end']) < strtotime($span2['start']))
	 {
	  return "-1";
	 }
	 else if(strtotime($span1['start']) > strtotime($span2['end']))
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
  public function printSpan($span)
  {
	echo $span['start'];
	echo $span['end'];
  }
  
  
  /* function to print span
  *
  */
  public function printSpans($span = array())
  {
    foreach($span as $spans)
	{
	  echo $spans['start'];
	  echo $spans['end'];
	}
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
  public function spanIncrEnd($span="") {
	 $time = strtotime($span);
     return date("Y-m-d H:i:s", strtotime('+1 minutes', $time));
  }
  
  /*
  Span trime decrement by 1 unit // 1 unit = 1 minute assumed
  $start (2014-08-12 10:12:12)
  */
  public function spanDecEnd($span="") {
	 $time = strtotime($span);
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
	
	
	/* function for sorhey i ahve also t span
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
