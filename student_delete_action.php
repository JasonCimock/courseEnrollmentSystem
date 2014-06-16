<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

ini_set( "display_errors", 0);  
$id = $_POST["id"];

// Form the sql string and execute it.
$sql = array("delete from taken where id = '$id'", "delete from students where id = '$id'");

$result_array = execute_sqls_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  // Error handling interface.
  echo "<B>Deletion Failed.</B> <BR />";
  die("<i> 

  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record deleted.  Go back.
Header("Location:manage.php?sessionid=$sessionid");
?>
