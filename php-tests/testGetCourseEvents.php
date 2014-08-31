<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);
error_reporting(-1);


// include 'ChromePhp.php';

require_once "../classes/php/scheduler.php";


?>
<?php ob_start(); //output buffering required to use chrome logger, prevent output?>  
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test getCourseEvents</title>
</head>
<body>
<?php
  
  $sch = new Scheduler();
  
	$events = array();

 $user1 = new User("Roberta","Golliher","testy1cs419g5@gmail.com");
  $user2 = new User("Kakhramon","Gafurov","testy2cs419g5@gmail.com");
  $user3 = new User("Alexei","Soldatov","testy3s419g5@gmail.com");
  $user4 = new User("Sujita","Sklenar","testy4cs419g5@gmail.com");
    
  $users = array($user1,$user2,$user3,$user4);
  $spans = $sch->getCourseEvents($events, $users);

  foreach ($spans as $span){
    echo 'start: ';
    echo date_format($span['start'], 'Y/m/d H:i');
    echo "  end: ";
    echo date_format($span['end'], 'Y/m/d H:i');
    echo "<br>";
  }
   



   // $span = events[0]
   // $span.printSpans(events)
?>
</body>
</html>
<?php ob_flush(); ?>