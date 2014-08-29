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
  	
  	$span1 = new Span(new DateTime("2014-07-08 11:14"), new DateTime("2014-07-08 14:14"));
  	$span2 = new Span(new DateTime("2014-07-08 11:16"), new DateTime("2014-07-08 14:14"));
  	$span3 = new Span(new DateTime("2014-07-08 10:16"), new DateTime("2014-07-08 10:19"));
  	$span4 = new Span(new DateTime("2014-07-08 14:16"), new DateTime("2014-07-08 14:45"));
  	$span5 = new Span(new DateTime("2014-07-08 14:35"), new DateTime("2014-07-08 14:55"));
  	
  	$spans = array($span1, $span2, $span3, $span4, $span5 );
	   var_dump($span1->isConflict($span1, $span2));
	// var_dump(consolidateSpans( $spans));

	/*
		output should be [10:16 - 10:19][11:14 - 14:14][ 14:16 - 14:55 ] */


  


$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}

function consolidateSpans ( $spans ){
	 /****************************************************************************
        * returns: a list of spans
        * parameters: span list
        * precond: list of users > 0, window start is in future
        */
        
	 	

			foreach ($spans as $cur){
				foreach ($spans as $other) {
					if ($cur->isConflict( $cur, $other )){
						$cur = combineSpans( $cur, $other);
						$spans.remove($other);
					} //if
				}// foreach
			} //foreach 	
			
			return $spans;
}