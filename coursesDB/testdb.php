<?php
/*******************************************************************************
testdb.php - tests the courses database
 *
 * note: THIS IS NOT SECURE!
 *******************************************************************************/

define('DATABASE', 'courses.db');

define('TITLE', 'Courses DB Test');
define('HEADER', 'assets/header.php');
define('BODY', 'assets/body.php');
define('FOOTER', 'assets/footer.php');


echo "hello";
if (!empty($_POST["lastname"])) {
    $ln =  $_POST["lastname"];
    $sel = "SELECT p.firstName, c.days, c.startDate, c.endDate, c.startTime, c.endTime   FROM Courses c
INNER JOIN CoursesProfs cp ON cp.cid = c.crn
INNER JOIN Profs p ON p.email = cp.pid
WHERE p.lastName = ".$ln.";";
    $sel = "select * from courses;";


    try {
        $db = new PDO('mysql:host=localhost;dbname=' . MYSQLDB, MYSQLUSER, MYSQLPASS,
            array( PDO::ATTR_PERSISTENT => true ));
    } catch (PDOException $e) {
        error($e->getMessage());
    }


    


    $db = new PDO('sqlite:'.DATABASE);


    $query = $db->query($sel); #Lists all tables

    $converted_res = ($query) ? 'true' : 'false';
    echo $converted_res;

    if ($query)
        echo 'success';

   if (!$query)
       die("Impossible to execute query.") #As reported above, this means that the db owner is different from the web server's one, but we did not commit any syntax mistake.


    $results = sqlite_fetch_all( $query);
   echo $reults;

   print $query->numRows();
   while ($row = $query->fetch())
       echo($row['name']."\n");




}
// _init();
// main();
// page();

// function main()
// {
//     global $G;
//     message("PHP testing sandbox (%s) version %s", $G['ME'], VERSION);
// //    try {
// //        $db = new PDO('sqlite:' . DATABASE);
// //        $db->exec('CREATE TABLE IF NOT EXISTS t (a, b, c)');
// //        message('Table t sucessfully created');
// //    } catch(PDOException $e) {
// //        message($e->getMessage());
// //    }

// }

// function _init( )
// {
//     global $G;
//     $G['TITLE'] = TITLE;
//     $G['ME'] = basename($_SERVER['SCRIPT_FILENAME']);

//     // initialize display vars
//     foreach ( array( 'MESSAGES', 'ERRORS', 'CONTENT' ) as $v )
//         $G[$v] = "";
// }

// function page( )
// {
//     global $G;
//     set_vars();

//     require_once(HEADER);
//     require_once(BODY);
//     require_once(FOOTER);
//     exit();
// }

// //

// function set_vars( )
// {
//     global $G;
//     if(isset($G["_MSG_ARRAY"])) foreach ( $G["_MSG_ARRAY"] as $m ) $G["MESSAGES"] .= $m;
//     if(isset($G["_ERR_ARRAY"])) foreach ( $G["_ERR_ARRAY"] as $m ) $G["ERRORS"] .= $m;
//     if(isset($G["_CON_ARRAY"])) foreach ( $G["_CON_ARRAY"] as $m ) $G["CONTENT"] .= $m;
// }

// function content( $s )
// {
//     global $G;
//     $G["_CON_ARRAY"][] = "\n<div class=\"content\">$s</div>\n";
// }

// function message()
// {
//     global $G;
//     $args = func_get_args();
//     if(count($args) < 1) return;
//     $s = vsprintf(array_shift($args), $args);
//     $G["_MSG_ARRAY"][] = "<p class=\"message\">$s</p>\n";
// }

// function error_message()
// {
//     global $G;
//     $args = func_get_args();
//     if(count($args) < 1) return;
//     $s = vsprintf(array_shift($args), $args);
//     $G["_ERR_ARRAY"][] = "<p class=\"error_message\">$s</p>\n";
// }

// function error( $s )
// {
//     error_message($s);
//     page();
// }

?>
