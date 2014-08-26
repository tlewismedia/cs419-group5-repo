<?php
include "Conflict.php";
/* Test Case for  Conflict class
*
*
*/
$obj	=	new Conflict();

### testing isConflict ####
$result = $obj->isConflict('2014-08-07 04:39:52','2014-08-11 04:39:53');
var_dump($result);
?>


