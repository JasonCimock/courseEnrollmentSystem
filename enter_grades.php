<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Get values for the record to be added if from enter_grades_action.php
$id = $_POST["id"];
$seqid = $_POST["seqid"];
$grade = $_POST["grade"];


// display the insertion form.
echo("
  <h1>Enter Grades Page</h1> 
  <form method=\"post\" action=\"enter_grades_action.php?sessionid=$sessionid\">
  Student id: (Required, up to 8 characters): <input type=\"text\" value = \"$id\" size=\"10\" maxlength=\"8\" name=\"id\"> <br /> 
  Course sequence id: (Required, up to 5 characters): <input type=\"text\" value = \"$seqid\" size=\"8\" maxlength=\"5\" name=\"seqid\">  <br />
  Grade (Required e.g. 4.0): <input type=\"text\" value = \"$grade\" size=\"5\" maxlength=\"3\" name=\"grade\">  <br />
  <br />
  <input type=\"submit\" value=\"Enter Grade\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>
  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");

?>
