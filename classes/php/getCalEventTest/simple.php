<?php
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';

require_once "../Span.php";


session_start();

$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('1022620417954-umm4lcjlmu56bknb0jcl6ecn9g7b7lrv.apps.googleusercontent.com');
$client->setClientSecret('CVHomrRDmzwXhPu6mfdEcW5q');
$client->setRedirectUri('http://localhost/simple.php');
$client->setDeveloperKey('AIzaSyD2byk5D8l2BywWVpUu0Gy7cEVWb4dxcag');
$cal = new Google_CalendarService($client);
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
  	
  	$winStart = new DateTime("2014-07-08 11:14");
  	$winEnd = new DateTime("2014-09-08 11:14");
	$users = array('testy1cs419g5@gmail.com', 'testy2cs419g5@gmail.com', 'testy3cs419g5@gmail.com', 'testy4cs419g5@gmail.com');
	var_dump(getCalEvents( $users, $winStart, $winEnd, $client));


  // $calList = $cal->calendarList->listCalendarList();
  // print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
  // print "test";


$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}

function getCalEvents ( $users, $winStart, $winEnd, $client ){
	$eventList = array();
	$tz = 'America/Los_Angeles';
	$freebusy = new Google_FreeBusyRequest();
	$freebusy->setTimeMin($winStart->format(DateTime::RFC3339));
	$freebusy->setTimeMax($winEnd->format(DateTime::RFC3339));
	$freebusy->setTimeZone($tz);
	$item = new Google_FreeBusyRequestItem();

	foreach ($users as $user){

		$item->setId($user);
		$freebusy->setItems(array($item));
		$service = new Google_CalendarService($client);  
		$request = $service->freebusy->query($freebusy);
		// return $request;
		$cals = $request['calendars'];

   	$busys = $cals[$user]['busy']; //get user's busys
   	
   	foreach ($busys as $busy) {
   		$eventList[] = new Span(new DateTime($busy['start']), new DateTime($busy['end']));
   	}
	}
	
	return $eventList;
}