<?
include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Verify where we are from, manage.php or  user_update_action.php.
if (!isset($_POST["update_fail"])) { // from manage.php
  // Fetch the record to be updated.
  $username = $_GET["username"];

  // the sql string
  $sql = "select username, passw, isstudent, isadmin from users where username = '$username'";
  $result_array = execute_sql_in_mysql($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($cursor == false){
    die("Query Failed.");
  }

  $values = mysqli_fetch_array ($cursor);
  $username = $values[0];
  $password = $values[1];
  $isstudent = $values[2];
  $isadmin = $values[3];
}
else { // from user_update_action.php
  // Obtain values of the record to be updated directly.
  $username = $_POST["username"];
  $password = $_POST["password"];
  $isstudent = $_POST["isstudent"];
  $isadmin = $_POST["isadmin"];
  
}

// Display the record to be updated.
echo("
  <form method=\"post\" action=\"user_update_action.php?sessionid=$sessionid\">
  Username: <input type=\"text\" value = \"$username\" size=\"10\" maxlength=\"10\" name=\"username\"> <br /> 
  Password: <input type=\"text\" value = \"$password\" size=\"20\" maxlength=\"30\" name=\"password\">  <br />
");

if ($isstudent == 'y') {
    echo("Student<input type=\"checkbox\" checked name=\"isstudent\" value=\"y\"> <br />");
}
else {
    echo("Student<input type=\"checkbox\" name=\"isstudent\" value=\"y\"> <br />");
}
if ($isadmin == 'y') {
    echo("Administrator<input type=\"checkbox\" checked name=\"isadmin\" value=\"y\">  <br />");
}
else {
    echo("Administrator<input type=\"checkbox\" name=\"isadmin\" value=\"y\">  <br />");
}
 
echo("  
  <input type=\"submit\" value=\"Update\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");
?>