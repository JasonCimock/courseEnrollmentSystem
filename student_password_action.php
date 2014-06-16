<?
include "utility_functions.php";

//Access level
$access = "s";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Suppress PHP auto warning.
ini_set( "display_errors", 0);  

// Obtain information for the record to be updated.
$username = $_POST["username"];
$oldpassword = $_POST["oldpassword"];
$newpassword = $_POST["newpassword"];

// Form the sql string and execute it.
$sql = "update users set passw = '$newpassword' where username = '$username' and passw = '$oldpassword'";
//echo($sql);

$result_array = execute_sql_in_mysql ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";

  //display_oracle_error_message($cursor);

  die("<i> 

  <form method=\"post\" action=\"user_update?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"1\" name=\"update_fail\">
  <input type=\"hidden\" value = \"$username\" name=\"username\">
  <input type=\"hidden\" value = \"$password\" name=\"password\">
  <input type=\"hidden\" value = \"$usertype\" name=\"usertype\">
    
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record updated.  Go back.
Header("Location:student.php?sessionid=$sessionid");
?>