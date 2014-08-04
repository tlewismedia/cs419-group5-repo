<?php
//error_reporting(E_ALL);
//require_once 'google-api-php-client/src/Google_Client.php';
//require_once 'google-api-php-client/src/contrib/Google_CalendarService.php';
require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_CalendarService.php';
session_start();

if ((isset($_SESSION)) && (!empty($_SESSION))) {
   //echo "There are cookies<br>";
   //echo "<pre>";
   //print_r($_SESSION);
   //echo "</pre>";
}

$client = new Google_Client();
$client->setApplicationName("Google Calender");
$client->setClientId('223671811715-jqcsfjrr9lm6bsev4p057nkkbk4qgs2v.apps.googleusercontent.com');
$client->setClientSecret('sQmK60itSF3gRiT0zIA0GUps');
$client->setRedirectUri('http://web.engr.oregonstate.edu/~kodalik/sp/Googlecalender/examples/calendar/simple1.php');
//$client->setDeveloperKey('AIzaSyAEsLiL3FuANtrvHLlLpggRGwj1h4UdEMo');
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
  echo "<br>Getting access";
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken())
{
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {
$( "#from" ).datepicker({
dateFormat : 'yy-mm-dd',
changeMonth: true,
changeYear: true,
numberOfMonths: 1,
minDate: 0 ,
onClose: function( selectedDate ) {
$( "#to" ).datepicker( "option", "minDate", selectedDate );
}
});
$( "#to" ).datepicker({
dateFormat : 'yy-mm-dd',
 changeMonth: true,
changeYear: true,
numberOfMonths: 1,
minDate: 0 ,
onClose: function( selectedDate ) {
$( "#from" ).datepicker( "option", "maxDate", selectedDate );
}
});
});
</script>
<h2>Add Event To Your Google Calender</h2>
<form name="postevent" action="" method="POST">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
<td>
Event Name : <input type="text" name="eventname">
</td>
</tr>
<tr>
<td>
Event Location : <input type="text" name="location">
</td>
</tr>
<tr>
<td>
Event Start Date : 
<input type="text" id="from" name="from">
<!--<select name="year">
<?php for($i=2010;$i<=date('Y');$i++) { ?>
<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>
</select>
<select name="month">
<?php for($ii=1;$ii<=12;$ii++) {
if($ii<10)
{
 $val = '0'.$ii;
}
else
{
$val = $ii;
}
 ?>
<option value="<?php echo $val; ?>"><?php echo $val; ?></option>
<?php } ?>
</select>
<select name="day">
<?php for($iii=1;$iii<=31;$iii++) { 
if($iii<10)
{
 $valiii = '0'.$iii;
}
else
{
$valiii = $iii;
}
?>
<option value="<?php echo $valiii; ?>"><?php echo $valiii; ?></option>
<?php } ?>
</select>-->
<select name="hours">
<?php for($hours=0;$hours<=23;$hours++) {
if($hours<10)
{
 $valhours = '0'.$hours;
}
else
{
$valhours = $hours;
}

?>
<option value="<?php echo $valhours; ?>"><?php echo $valhours; ?></option>
<?php } ?>
</select>
<select name="minute">
<?php for($min=0;$min<=59;$min++) { 
if($min<10)
{
 $valmin = '0'.$min;
}
else
{
$valmin = $min;
}
?>
<option value="<?php echo $valmin; ?>"><?php echo $valmin; ?></option>
<?php } ?>
</select>
<select name="seconds">
<?php for($sec=0;$sec<=59;$sec++) {
if($sec<10)
{
 $valsec = '0'.$sec;
}
else
{
$valsec = $sec;
}
?>
<option value="<?php echo $valsec; ?>"><?php echo $valsec; ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
Event End Date : 
<input type="text" id="to" name="to">
<!--<select name="yearend">
<?php for($j=2010;$j<=date('Y');$j++) { ?>
<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
<?php } ?>
</select>
<select name="monthend">
<?php for($jj=1;$jj<=12;$jj++) {
if($jj<10)
{
 $valj = '0'.$jj;
}
else
{
$valj = $jj;
}
 ?>
<option value="<?php echo $valj; ?>"><?php echo $valj; ?></option>
<?php } ?>
</select>
<select name="dayend">
<?php for($jjj=1;$jjj<=31;$jjj++) {
if($jjj<10)
{
 $valjjj = '0'.$jjj;
}
else
{
$valjjj = $jjj;
}
 ?>
<option value="<?php echo $valjjj; ?>"><?php echo $valjjj; ?></option>
<?php } ?>
</select>-->
<select name="hoursend">
<?php for($hours=0;$hours<=23;$hours++) { 
if($hours<10)
{
 $valhours = '0'.$hours;
}
else
{
$valhours = $hours;
}
?>
<option value="<?php echo $valhours; ?>"><?php echo $valhours; ?></option>
<?php } ?>
</select>
<select name="minutesend">
<?php for($min=0;$min<=59;$min++) {
if($min<10)
{
 $valmin= '0'.$min;
}
else
{
$valmin = $min;
}
?>
<option value="<?php echo $valmin; ?>"><?php echo $valmin; ?></option>
<?php } ?>
</select>
<select name="secondsend">
<?php for($sec=0;$sec<=59;$sec++) {
if($sec<10)
{
 $valsec= '0'.$sec;
}
else
{
$valsec = $sec;
}
?>
<option value="<?php echo $valsec; ?>"><?php echo $valsec; ?></option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td>
<input type="submit" name="addevent" value="ADD EVENT"/>
</td>
</tr>
<tr>
<td>
<a href="listing.php">Go To Listing</a>
</td>
</tr>
</table>
</form>
<?php
//echo "<hr><font size=+1>I have access to your calendar</font>";
  if(isset($_POST['addevent']) && $_POST['eventname']!="")
  {
  $eventname = $_POST['eventname'];
  $location = $_POST['location'];
  /*$year = $_POST['year'];
  $month = $_POST['month'];
  $day = $_POST['day'];*/
  $from = $_POST['from'];
  $hours  = $_POST['hours'];
  $minute = $_POST['minute'];
  $seconds = $_POST['seconds'];
  //$startdate = $year."-".$month."-".$day."T".$hours.":".$minute.":".$seconds;
  $startdate = $from."T".$hours.":".$minute.":".$seconds;
  
  /*$yearend = $_POST['yearend'];
  $monthend = $_POST['monthend'];
  $dayend = $_POST['dayend'];*/
  $to = $_POST['to'];
  $hoursend  = $_POST['hoursend'];
  $minutesend = $_POST['minutesend'];
  $secondsend = $_POST['secondsend'];
  //$startdateend = $yearend."-".$monthend."-".$dayend."T".$hoursend.":".$minutesend.":".$secondsend;
  $startdateend = $to."T".$hoursend.":".$minutesend.":".$secondsend;
  
  $event = new Google_Event();
  $event->setSummary($eventname);
  $event->setLocation($location);
  $start = new Google_EventDateTime();
  $start->setDateTime($startdate);
  $start->setTimeZone('America/Los_Angeles');
  $event->setStart($start);
  $end = new Google_EventDateTime();
  $end->setDateTime($startdateend);
  $end->setTimeZone('America/Los_Angeles');
  $event->setEnd($end);
  
  try { 
    $createdEvent = $cal->events->insert('primary', $event);
    echo "<br><font size=+1>Event created</font>";
  } catch (Google_Exception $e) { 
    //echo  $e->getCode(); 
	//echo  $e->getMessage();    
	if($e->getCode() == 400)
	{
	  echo "<br><font color='red'>end date/time should be after start date/time <br> or ".$e->getMessage()."</font>";
	}
	return false; 
    // or display the error message with $e->getMessage()
  }
  
  
  }
  //echo "<hr><br><font size=+1>Already connected</font> (No need to login)";

} 
else 
{

  $authUrl = $client->createAuthUrl();
  print "<hr><br><font size=+2><a href='$authUrl'>Connect Me!</a></font>";

}

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
echo "<br><br><font size=+2><a href=$url?logout>Logout</a></font>";

?>
