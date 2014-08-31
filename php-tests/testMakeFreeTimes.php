<?php
require_once "../classes/php/scheduler.php";

$sch = new Scheduler();

$winStart = new DateTime("2014-07-08 6:00");
$winEnd = new DateTime("2014-07-08 17:00");

$span1 = new Span(new DateTime("2014-07-08 6:00"), new DateTime("2014-07-08 7:00"));
$span2 = new Span(new DateTime("2014-07-08 10:00"), new DateTime("2014-07-08 12:00"));
$span3 = new Span(new DateTime("2014-07-08 13:00"), new DateTime("2014-07-08 14:00"));
$span4 = new Span(new DateTime("2014-07-08 15:00"), new DateTime("2014-07-08 16:00"));

$events = array($span1, $span2, $span3, $span4);



var_dump($sch->makeFreeTimes( $winStart, $winEnd, $events));


