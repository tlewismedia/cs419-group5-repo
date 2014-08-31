<?php
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';


require_once "../classes/php/scheduler.php";



session_start();

$client = new Google_Client();

// var_dump($client);

$client->setApplicationName("Google Calendar PHP Starter Application");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('1022620417954-umm4lcjlmu56bknb0jcl6ecn9g7b7lrv.apps.googleusercontent.com');
$client->setClientSecret('CVHomrRDmzwXhPu6mfdEcW5q');
$client->setRedirectUri('http://localhost/testScheduler.php');
$client->setDeveloperKey('AIzaSyD2byk5D8l2BywWVpUu0Gy7cEVWb4dxcag');
var_dump($client);

// $cal = new Google_CalendarService($client);
if (isset($_GET['logout'])) {
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
  	
   echo "<h2>Testing Find Free Times:</h2>";

   $sch = new Scheduler();


    $winStart = new DateTime("2014-07-08 11:14");
    $winEnd = new DateTime("2014-09-08 11:14");

    $user1 = new User("Roberta","Golliher","testy1cs419g5@gmail.com");
    $user2 = new User("Kakhramon","Gafurov","testy2cs419g5@gmail.com");
    $user3 = new User("Alexei","Soldatov","testy3s419g5@gmail.com");
    $user4 = new User("Sujita","Sklenar","testy4cs419g5@gmail.com");
    
  	$users = array($user1,$user2,$user3,$user4);

    $free = $sch->findFreeTimes($winStart, $winEnd, $users, $client);

    var_dump($free);
  	
    // $spans = array($span1, $span2, $span3, $span4, $span5 );
	
 //  echo "consolidated spans should be [11:14, 14:14], [10:16, 10:19], [14:16, 14:55]" ;  
	// var_dump(consolidateSpans( $spans));

	/*
		output should be [10:16 - 10:19][11:14 - 14:14][ 14:16 - 14:55 ] */


  


$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}

