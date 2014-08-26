<?php
include "Span.php";
/* Test Case for SPAN  class
*
*
*/
$obj	=	new Span();

### testing isConflict ####
$result = $obj->isConflict('2014-08-07 04:39:52','2014-08-11 04:39:53');
var_dump($result);
### testing isConflict ####
echo "<br>";
$resultcompare = $obj->compareTo('2014-08-07 04:39:52','2014-08-11 04:39:53');
echo $resultcompare;
### tesitng printSpans ####
echo "<br>";
$resultprint = $obj->printSpans(array("start"=>"2014-08-07 04:39:52","end"=>"2014-08-11 04:39:53"));
echo $resultprint;
### tesitng combineSpans ####
echo "<br>";
$current = array("start"=>"2014-08-07 04:39:52","end"=>"2014-08-11 04:39:53");
$other = array("start"=>"2014-08-05 04:39:52","end"=>"2014-08-15 04:39:53");
$resultcombine = $obj->combineSpans($current,$other);
print_r($resultcombine);
### tesitng spanIncrEnd ####
echo "<br>";
$resultspanIncrEnd = $obj->spanIncrEnd("2014-08-07 04:39:52");
echo $resultspanIncrEnd;
### tesitng spanDecEnd ####
echo "<br>";
$resultspanDecEnd = $obj->spanDecEnd("2014-08-07 04:39:52");
echo $resultspanDecEnd;
?>


