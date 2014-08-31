<?php
if (isset($_POST['name'])) {
	
	// var_dump($_POST);

	$names = $_POST['name'];
	$start = $_POST['dtStart'];
	$end = $_POST['dtEnd'];
	$task = $_POST['task'];
	
	var_dump($names);
	var_dump($start);
	var_dump($end);


	if ($task == 'time') {
		echo "Find Free Times gets called here using 'names' array and optional start / end";
	} else if ($task == 'user'){
		echo "Find Free User gets called here using 'names' array and optional start / end";
	} else {
		echo "Invalid Input";
	}
	
	

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web test Interface</title>

	<script src="/lib/jquery-2.1.1.min.js"></script>
  <script src="/lib/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="/lib/jquery-ui.min.css">
  
  
  <script>
  $(function() {
    $( "#dateStartPicker" ).datepicker();
    $( "#dateEndPicker" ).datepicker();
    $('#scrollDefaultExample').timepicker({ 'scrollDefault': 'now' });
  });
  </script>


</head>
<body>

<h1>Web Interface</h1>

<hr>

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
	<label for="Start">Start</label>
	<input name="dtStart" type="datetime-local">
	</p>
	<p>
	<label for="Start">End</label>
	<input name="dtEnd" type="datetime-local">
	</p>
	<p>
	<input type="hidden" name="task" value="time"> <!-- indicate its for findFreeTimes -->
	<input type="submit" value="Find">
	</p>


</form>
<hr>
<h2>Find Free Users</h2>

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
	<label for="Start">Start</label>
	<input name="dtStart" type="datetime-local">
	</p>
	<p>
	<label for="Start">End</label>
	<input name="dtEnd" type="datetime-local">
	</p>
	<p>
	<input type="hidden" name="task" value="user"> <!-- indicate its for findFreeUsers -->
	<input type="submit" value="Find">
	</p>


</form>
	
</body>
</html>