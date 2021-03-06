<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Get values for the record to be added if from user_add_action.php
$username = $_POST["username"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$address = $_POST["address"];
$studenttype = $_POST["studenttype"];
$status = $_POST["status"];

// display the insertion form.
echo("
  <h1>Add Student Page</h1> <br />
  <p>A student id will automatically generated by the system. A new user must be added prior to adding a new student.</p>
  <form method=\"post\" action=\"student_add_action.php?sessionid=$sessionid\">
  Username (Required, up to 8 characters): <input type=\"text\" value = \"$username\" size=\"10\" maxlength=\"8\" name=\"username\"> <br /> 
  First Name (Required, up to 20 characters): <input type=\"text\" value = \"$firstname\" size=\"25\" maxlength=\"20\" name=\"firstname\">  <br />
  Last Name (Required, up to 20 characters): <input type=\"text\" value = \"$lastname\" size=\"25\" maxlength=\"20\" name=\"lastname\">  <br />
  Address (up to 30 characters): <input type=\"text\" value = \"$address\" size=\"30\" maxlength=\"30\" name=\"address\">  <br />
  ");
if ($studenttype == 'g') {
    echo("Student Type: <input type=\"radio\" name=\"studenttype\" value = \"u\">Undergraduate <input type=\"radio\" checked name=\"studenttype\" value = \"g\">Graduate <br />");
}
else {
    echo("Student Type: <input type=\"radio\" checked name=\"studenttype\" value = \"u\">Undergraduate <input type=\"radio\" name=\"studenttype\" value = \"g\">Graduate <br />");
}
if ($status == 'p') {
    echo("Status: <input type=\"radio\" name=\"status\" value = \"g\">Good Standing <input type=\"radio\" checked name=\"status\" value = \"p\">Probation <br />");
}
else {
    echo("Status: <input type=\"radio\" checked name=\"status\" value = \"g\">Good Standing <input type=\"radio\" name=\"status\" value = \"p\">Probation <br />");
}


echo("
  <br />
  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");
?>
