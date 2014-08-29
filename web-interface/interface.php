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
  });
  </script>


</head>
<body>

<h1>Web Interface</h1>

<h2>Find Free Time</h2>

<form action="#">
	<label>Attendees (select one or more)</label>

	<select multiple>
	  <option value="testy1cs419g5@gmail.com">Roberta Golliher</option>
	  <option value="testy2cs419g5@gmail.com">Kakhramon Gafurov</option>
	  <option value="testy3cs419g5@gmail.com">Alexei Soldatov</option>
	  <option value="testy4cs419g5@gmail.com">Sujita Sklenar</option>
	</select>

	<label for="dateStart"></label>
	<p>Date Start: <input type="text" id="dateStartPicker"></p>
	
	<p>Date End: <input type="text" id="dateEndPicker"></p>


	<input type="submit" value="Find">



</form>
	
</body>
</html>