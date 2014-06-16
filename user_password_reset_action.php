<?
include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Suppress PHP auto warning.
ini_set( "display_errors", 0);  

// Obtain information for the record to be updated.
$username = $_GET["username"];


//default password string
$default = "password";

// Form the sql string and execute it.
$sql = "update users set passw = '$default' where username = '$username'";
$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  // Error handling interface.
  echo "<B>Update Failed.</B> <BR />";
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
Header("Location:manage.php?sessionid=$sessionid");
?>