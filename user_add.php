<?
include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Get values for the record to be added if from user_add_action.php
$username = $_POST["username"];
$password = $_POST["password"];
$isstudent = $_POST["isstudent"];
$isadmin = $_POST["isadmin"];


// display the insertion form.
echo("
  <h1>Add User Page</h1> <br />
  <form method=\"post\" action=\"user_add_action.php?sessionid=$sessionid\">
  Username (Required, up to 8 characters): <input type=\"text\" value = \"$username\" size=\"10\" maxlength=\"8\" name=\"username\"> <br /> 
  Password (Required, up to 12 characters): <input type=\"text\" value = \"$password\" size=\"20\" maxlength=\"12\" name=\"password\">  <br />
  Student<input type=\"checkbox\" name=\"isstudent\" value=\"y\"> <br />
  Administrator<input type=\"checkbox\" name=\"isadmin\" value=\"y\">  <br />
  ");


echo("

  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>");
?>