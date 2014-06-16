<?
include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);


$username = $_GET["username"];


// Fetech the record to be deleted and display it
$sql = "select username, passw, isstudent, isadmin from users where username = '$username'";
//echo($sql);

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  die("Client Query Failed.");
}

if (!($values = mysqli_fetch_array ($cursor))) {
  // Record already deleted by a separate session.  Go back.
  Header("Location:manage.php?sessionid=$sessionid");
}

$username = $values[0];
$password = $values[1];
$isstudent = $values[2];
$isadmin = $values[3];

// Display the record to be deleted.
echo("
  <form method=\"post\" action=\"user_delete_action.php?sessionid=$sessionid\">
  Username (Read-only): <input type=\"text\" readonly value = \"$username\" size=\"10\" maxlength=\"10\" name=\"username\"> <br /> 
  Password: <input type=\"text\" disabled value = \"$password\" size=\"20\" maxlength=\"30\" name=\"password\">  <br />
  ");
if ($isstudent == 'y') {
    echo ("Student: <input type=\"checkbox\" checked name=\"isstudent\">  <br />");
}
else {
    echo ("Student: <input type=\"checkbox\" name=\"isstudent\">  <br />");
}
if ($isadmin == 'y') {
  echo ("Admin: <input type=\"checkbox\" checked name=\"isadmin\">  <br />");
}
else {
    echo ("Admin: <input type=\"checkbox\" name=\"isadmin\">  <br />");
}
  
echo("
  <input type=\"submit\" value=\"Delete\">
  </form>
  
  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");

?>