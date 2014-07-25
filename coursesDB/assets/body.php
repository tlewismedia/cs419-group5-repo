<!-- main html file for TEST (php version) -->
<?php echo $G["MESSAGES"] ?>
<?php echo $G["ERRORS"] ?>
<?php echo $G["CONTENT"] ?>
<?php echo "

<form action='testdb.php' method='POST'>
    <label>Enter a last name:</label>
    <input  name='lastname'>
    <input type='submit' value='Submit'>
</form>


" ?>