<?php
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';
require_once "../classes/php/scheduler.php";


$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('1022620417954-umm4lcjlmu56bknb0jcl6ecn9g7b7lrv.apps.googleusercontent.com');
$client->setClientSecret('CVHomrRDmzwXhPu6mfdEcW5q');
$client->setRedirectUri('http://localhost/testScheduler.php');
$client->setDeveloperKey('AIzaSyD2byk5D8l2BywWVpUu0Gy7cEVWb4dxcag');


 $sch = new Scheduler();


  $winStart = new DateTime("2014-07-08 11:14", new DateTimeZone('America/Los_Angeles'));
  $winEnd = new DateTime("2014-09-08 11:14",new DateTimeZone('America/Los_Angeles'));

  $user1 = new User("Roberta","Golliher","testy1cs419g5@gmail.com");
  $user2 = new User("Kakhramon","Gafurov","testy2cs419g5@gmail.com");
  $user3 = new User("Alexei","Soldatov","testy3s419g5@gmail.com");
  $user4 = new User("Sujita","Sklenar","testy4cs419g5@gmail.com");
 

  echo "<h2>Testing Find Free Times: (all users) </h2>";
	$users = array($user1,$user2,$user3,$user4);

  $free = $sch->findFreeTimes($winStart, $winEnd, $users, $client);

  var_dump($free);

   echo "<h2>Testing Find Free Times (Roberta):</h2>";

  $winStart = new DateTime("2014-08-30 11:14",new DateTimeZone('America/Los_Angeles'));
  $winEnd = new DateTime("2014-09-13 11:14", new DateTimeZone('America/Los_Angeles'));

  $users = array($user1);

  $free = $sch->findFreeTimes($winStart, $winEnd, $users, $client);

  var_dump($free);


  echo "<h2>Testing Find Free Users:</h2>";
  $winStart = new DateTime("2014-09-03 13:10", new DateTimeZone('America/Los_Angeles') );
  $winEnd = new DateTime("2014-09-03 13:20", new DateTimeZone('America/Los_Angeles') ) ;
  $users = array($user1,$user2,$user3,$user4);
  
  echo "Roberta is busy:<br>";
  
  
  $free = $sch->findFreeUsers($users, $winStart, $winEnd, $client);

  var_dump($free);




