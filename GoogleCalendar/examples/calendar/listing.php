<?php
error_reporting(E_ALL);
require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_CalendarService.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google Calender");
$client->setClientId('223671811715-jqcsfjrr9lm6bsev4p057nkkbk4qgs2v.apps.googleusercontent.com');
$client->setClientSecret('sQmK60itSF3gRiT0zIA0GUps');
$client->setRedirectUri('http://web.engr.oregonstate.edu/~kodalik/sp/Googlecalender/examples/calendar/simple1.php');
// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
// $client->setClientId('insert_your_oauth2_client_id');
// $client->setClientSecret('insert_your_oauth2_client_secret');
// $client->setRedirectUri('insert_your_oauth2_redirect_uri');
// $client->setDeveloperKey('insert_your_developer_key');
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
  //$calList = $cal->calendarList->listCalendarList();
  //print "<h1>Calendar List</h1><pre>" . print_r($calList, true) . "</pre>";
  $eventsdata = $cal->events->listEvents('primary');
  //print "<h1>Event List</h1><pre>" . print_r($eventsdata, true) . "</pre>";

$_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
  print "<a class='login' href='$authUrl'>Connect Me!</a>";
}
?>
<table cellspacing="0" cellpadding="0" border="1" width="100%">
<tr>
<td colspan="8"><h3>Event Listing</h3><a href="simple.php">Add Event</a></td>
</tr>
<tr>
<td>Event Name</td>
<td> Location</td>
<td> Creator</td>
<td> Organiser</td>
<td> Start</td>
<td> End</td>
<td> Details</td>
<td> Action</td>
</tr>
<?php
foreach($eventsdata['items'] as $allevent)
{
?>
<tr>
<td>&nbsp;<?php echo $allevent['summary'] ?></td>
<td>&nbsp; <?php echo $allevent['location'] ?></td>
<td> &nbsp;<?php echo $allevent['creator']['displayName'] ?></td>
<td>&nbsp; <?php echo $allevent['organizer']['displayName'] ?></td>
<td>&nbsp; <?php echo $allevent['start']['dateTime'] ?></td>
<td>&nbsp; <?php echo $allevent['end']['dateTime'] ?></td>
<td>&nbsp; <a href="<?php echo $allevent['htmlLink'] ?>" target="_new">Click Here</a></td>
<td> <a href="editevent.php?eventid=<?php echo $allevent['id'] ?>">Edit</a> / <a href="deleteevent.php?eventid=<?php echo $allevent['id'] ?>">Delete</a></td>
</tr>
<?php
}
?>
</table>

