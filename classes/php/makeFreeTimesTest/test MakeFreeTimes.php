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
  	
  	$winStart = new DateTime("2014-07-08 6:00");
  	$winEnd = new DateTime("2014-07-08 17:00");

  	$span1 = new Span(new DateTime("2014-07-08 6:00"), new DateTime("2014-07-08 7:00"));
  	$span2 = new Span(new DateTime("2014-07-08 10:00"), new DateTime("2014-07-08 12:00"));
  	$span3 = new Span(new DateTime("2014-07-08 13:00"), new DateTime("2014-07-08 14:00"));
  	$span4 = new Span(new DateTime("2014-07-08 15:00"), new DateTime("2014-07-08 16:00"));

  	$events = array($span1, $span2, $span3, $span4);
	
	var_dump(makeFreeTimes( $winStart, $winEnd, $events));


$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}

function makeFreeTimes( $winStart, $winEnd, $events ){
	/*****************************************************************************
	* returns: Span[] (free times)
	* parameters: dateTime winStart, dateTime winEnd,  Span[] events
	*/
	$freeTimes = array();
	$start = $winStart;
	for ($i = 0; count($events) - 1; $i++){
	   $freeTimes[] = new Span( $start, $events[$i]['start']);
	   $start = $events[$i]['end'];
	}
	$freeTimes[] = Span( $start, $winEnd);
	

	return $freeTimes;


}