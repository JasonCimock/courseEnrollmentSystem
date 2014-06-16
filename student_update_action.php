<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid = $_GET["sessionid"];
verify_session($sessionid, $access);

// Suppress PHP auto warning.
ini_set("display_errors", 0);

$id = $_POST["id"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$address = $_POST["address"];
$studenttype = $_POST["studenttype"];
$status = $_POST["status"];

/// Form the sql string and execute it.
$sql = "update students set id = '$id', firstname = '$firstname', lastname = '$lastname', address = '$address', studenttype = '$studenttype', status = '$status' where id = '$id'";

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false) {
    // Error handling interface.
    echo "<B>Update Failed.</B> <BR />";
    die("<i> 

  <form method=\"post\" action=\"student_update?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"1\" name=\"update_fail\">
  <input type=\"hidden\" value = \"$id\" name=\"id\">
  <input type=\"hidden\" value = \"$firstname\" name=\"firstname\">
  <input type=\"hidden\" value = \"$lastname\" name=\"lastname\">
  <input type=\"hidden\" value = \"$address\" name=\"address\">
  <input type=\"hidden\" value = \"$studenttype\" name=\"studenttype\">
  <input type=\"hidden\" value = \"$status\" name=\"status\">        
    
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record updated.  Go back.
Header("Location:manage.php?sessionid=$sessionid");
?>
