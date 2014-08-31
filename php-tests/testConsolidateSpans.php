<?php


require_once "../classes/php/scheduler.php";



$sch = new Scheduler();
    $span1 = new Span(new DateTime("2014-07-08 11:14"), new DateTime("2014-07-08 14:14"));
    $span2 = new Span(new DateTime("2014-07-08 11:16"), new DateTime("2014-07-08 14:14"));
  	$span3 = new Span(new DateTime("2014-07-08 10:16"), new DateTime("2014-07-08 10:19"));
  	$span4 = new Span(new DateTime("2014-07-08 14:16"), new DateTime("2014-07-08 14:45"));
  	$span5 = new Span(new DateTime("2014-07-08 14:35"), new DateTime("2014-07-08 14:55"));
  	
  	
    $spans = array($span1, $span2, $span3, $span4, $span5 );
	
  echo "consolidated spans should be [11:14, 14:14], [10:16, 10:19], [14:16, 14:55]" ;  
	var_dump($sch->consolidateSpans( $spans));

	/*
		output should be [10:16 - 10:19][11:14 - 14:14][ 14:16 - 14:55 ] */




