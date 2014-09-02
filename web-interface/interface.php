<?php

require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';


require_once "../classes/php/scheduler.php";
require_once "../classes/php/MyDB.php";

$MAXRANGE = 90;

$client = new Google_Client();
$client->setApplicationName("Google Calendar PHP Starter Application");

// Visit https://code.google.com/apis/console?api=calendar to generate your
// client id, client secret, and to register your redirect uri.
$client->setClientId('1022620417954-umm4lcjlmu56bknb0jcl6ecn9g7b7lrv.apps.googleusercontent.com');
$client->setClientSecret('CVHomrRDmzwXhPu6mfdEcW5q');
$client->setRedirectUri('http://localhost/testScheduler.php');
$client->setDeveloperKey('AIzaSyD2byk5D8l2BywWVpUu0Gy7cEVWb4dxcag');


if (isset($_POST['name'])) {
	
	// var_dump($_POST);

	$names = $_POST['name'];
	$task = $_POST['task'];

	//check / set window times
	$winStart = new DateTime($_POST['dtStart'],new DateTimeZone('America/Los_Angeles')); //defaults to current if not set
	if (strlen($_POST['dtEnd'] > 0)){
		$winEnd = new DateTime($_POST['dtEnd'],new DateTimeZone('America/Los_Angeles'));
	}else {
		$temp = clone $winStart;
		$winEnd = $temp->add(new DateInterval('P'.WINDOWDEFAULT.'D'));
	}

	// var_dump($winStart);
	// var_dump($winEnd);
	$valid = isDateRangeValid($winStart, $winEnd);


	$sch = new Scheduler();
	$users = createUsersFromEmails($names);
	
	if (!$valid){
		echo "<div id='result'>INVALID RANGE</div>";
	} else {
	
		if ($task == 'time') {
			//free times
			
	  		$free = $sch->findFreeTimes($winStart, $winEnd, $users, $client);
	  		echo "<div id='result'><h3>Free Times for date range:<br> ".$winStart->format('Y-m-d H:i')." to ".$winEnd ->format('Y-m-d H:i')."</h3>";
	  		$free[0]->printSpans($free);
	  		echo "</div>";

		} else if ($task == 'user'){
			//free users
			$free = $sch->findFreeUsers($users, $winStart, $winEnd, $client);
			echo "<div id='result'><h3>People available for date range: <br>".$winStart->format('Y-m-d H:i')." to ".$winEnd ->format('Y-m-d H:i')."</h3>";
			$free[0]->printUser($free);
			echo "</div>";
		} else {
			echo "Invalid Input";
		}

	}//if in range
} //if name is set

function createUsersFromEmails($emails){
	$db = new MyDB();
	$users = array();
	$userids = "p.email = '".implode("' \n   OR p.email = '",$emails)."'";
	$sel = "SELECT p.firstName, p.lastName, p.email   
	FROM  Profs p
	WHERE $userids";

	$result = $db->query($sel);

	// return $result;

	while($res = $result->fetchArray(SQLITE3_ASSOC)){
		$users[] = new User($res['firstName'],$res['lastName'],$res['email']);
	}
	$db->close();
	return $users;
}

function isDateRangeValid($winStart, $winEnd){
	global $MAXRANGE;
	if ($winStart->diff($winEnd)->days > $MAXRANGE) return false;
	if ($winStart >= $winEnd) return false;
	return true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web test Interface</title>

	<script src="/lib/jquery-2.1.1.min.js"></script>
  <script src="/lib/jquery-ui.min.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  
 


</head>
<body>

<style>
html {
    height: 100%;
}

	body{
	height: 100%;
    margin: 0;
    background-repeat: no-repeat;
    background-attachment: fixed;
		font-family: 'Roboto', sans-serif;
		background: rgb(125,185,232); /* Old browsers */
background: -moz-radial-gradient(center, ellipse cover,  rgba(125,185,232,1) 0%, rgba(30,87,153,1) 100%); /* FF3.6+ */
background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(125,185,232,1)), color-stop(100%,rgba(30,87,153,1))); /* Chrome,Safari4+ */
background: -webkit-radial-gradient(center, ellipse cover,  rgba(125,185,232,1) 0%,rgba(30,87,153,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-radial-gradient(center, ellipse cover,  rgba(125,185,232,1) 0%,rgba(30,87,153,1) 100%); /* Opera 12+ */
background: -ms-radial-gradient(center, ellipse cover,  rgba(125,185,232,1) 0%,rgba(30,87,153,1) 100%); /* IE10+ */
background: radial-gradient(ellipse at center,  rgba(125,185,232,1) 0%,rgba(30,87,153,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7db9e8', endColorstr='#1e5799',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
background-size: cover;
	}
	.form{
		float: left;
		width: 40%;
		margin: 5%;
	}
	#main{
		margin: 10% auto;
		width: 70%;
		background-color: #ECF8FF;
		border-radius: 3px;
		overflow: hidden;
	}
	#result{
		text-align: center;
		margin: 10% auto;
		width: 70%;
		background-color: #ECF8FF;
		border-radius: 3px;
		overflow: hidden;
		padding-bottom: 10px;
	}
	h1{
		text-align: center;
	}

	@media (max-width: 700px){
		.form{
			width: 90%;
			margin: 5%;
		}
	}
</style>

<div id="main">
	<h1>Web Interface</h1>

	
	<section class="form">
	<h2>Find Free Time</h2>

		<form method="post" action="interface.php">
			<label>Attendees (select one or more)</label>
			<p>
			<select multiple name="name[]">
			  <option value="testy1cs419g5@gmail.com">Roberta Golliher</option>
			  <option value="testy2cs419g5@gmail.com">Kakhramon Gafurov</option>
			  <option value="testy3cs419g5@gmail.com">Alexei Soldatov</option>
			  <option value="testy4cs419g5@gmail.com">Sujita Sklenar</option>
			</select>
			</p>
			<p>
			<label for="Start">Start</label><br>
			<input name="dtStart" type="datetime-local">
			</p>
			<p>
			<label for="Start">End</label><br>
			<input name="dtEnd" type="datetime-local">
			</p>
			<p>
			<input type="hidden" name="task" value="time"> <!-- indicate its for findFreeTimes -->
			<input type="submit" value="Find">
			</p>


		</form>
	</section>

	<section class="form">
		<h2>Find Free People</h2>

		<form method="post" action="interface.php">
			<label>Possible Attendees (select one or more)</label>
			<p>
			<select multiple name="name[]">
			  <option value="testy1cs419g5@gmail.com">Roberta Golliher</option>
			  <option value="testy2cs419g5@gmail.com">Kakhramon Gafurov</option>
			  <option value="testy3cs419g5@gmail.com">Alexei Soldatov</option>
			  <option value="testy4cs419g5@gmail.com">Sujita Sklenar</option>
			</select>
			</p>
			<p>
			<label for="Start">Start</label><br>
			<input name="dtStart" type="datetime-local" required>
			</p>
			<p>
			<label for="Start">End</label><br>
			<input name="dtEnd" type="datetime-local" required>
			</p>
			<p>
			<input type="hidden" name="task" value="user"> <!-- indicate its for findFreeUsers -->
			<input type="submit" value="Find">
			</p>


		</form>
	</section>
</div> 
</body>
</html>