<?php
//error_reporting(E_ALL);
//require_once 'google-api-php-client/src/Google_Client.php';
//require_once 'google-api-php-client/src/contrib/Google_CalendarService.php';
require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_CalendarService.php';
session_start();

if ((isset($_SESSION)) && (!empty($_SESSION))) {
   //echo "There are cookies<br>";
}

$client = new Google_Client();
$client->setApplicationName("Google Calender");
$client->setClientId('223671811715-jqcsfjrr9lm6bsev4p057nkkbk4qgs2v.apps.googleusercontent.com');
$client->setClientSecret('sQmK60itSF3gRiT0zIA0GUps');
$client->setRedirectUri('http://web.engr.oregonstate.edu/~kodalik/sp/Googlecalender/examples/calendar/simple1.php');

$cal = new Google_CalendarService($client);

if (isset($_GET['logout']))
{
  echo "<br><br><font size=+2>Logging out</font>";
  unset($_SESSION['token']);
}

if (isset($_GET['code'])) 
{
  echo "<br>I got a code from Google = ".$_GET['code']; // You won't see this if redirected later
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
  echo "<br>I got the token = ".$_SESSION['token']; // <-- not needed to get here unless location uncommented
}

if (isset($_SESSION['token'])) 
{
  //echo "<br>Getting access";
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken())
{
  if(isset($_GET['eventid']) && $_GET['eventid']!="")
  {
    echo "<br>Deleting Event";
    $eventid = $_GET['eventid'];
    $cal->events->delete('primary', $eventid);
	echo "<script>window.location.href='listing.php'</script>";
  }
} 
else 
{

  $authUrl = $client->createAuthUrl();
  print "<hr><br><font size=+2><a href='$authUrl'>Connect Me!</a></font>";

}

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
echo "<br><br><font size=+2><a href=$url?logout>Logout</a></font>";

?>
