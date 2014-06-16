<?
include "utility_functions.php";

//Access level
$access = "e";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);


// connection OK - delete the session.
$sql = "delete from usersession where sessionid = '$sessionid'";

$result_array = execute_sql_in_mysql ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($cursor == false){
  die("Session removal failed");
}

// jump to login page
header("Location:login.html");
?>