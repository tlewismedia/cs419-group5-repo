<?php

require_once "Span.php";



$span1 = new Span(new DateTime("2014-07-08 11:14"), new DateTime("2014-07-08 14:14"));
$span2 = new Span(new DateTime("2014-07-08 11:16"), new DateTime("2014-07-08 14:14"));
$span3 = new Span(new DateTime("2014-07-08 10:16"), new DateTime("2014-07-08 10:19"));
$span4 = new Span(new DateTime("2014-07-08 14:16"), new DateTime("2014-07-08 14:45"));
$span5 = new Span(new DateTime("2014-07-08 14:35"), new DateTime("2014-07-08 14:55"));
    
    
$spans = array($span1, $span2, $span3, $span4, $span5 );

echo "before sort";

$span1->printSpans($spans);
// var_dump($spans);

echo "after sort";

$span1->sortSpans($spans);

$span1->printSpans($spans);
// var_dump($spans);