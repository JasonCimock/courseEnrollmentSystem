<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid = $_GET["sessionid"];
verify_session($sessionid, $access);

// Suppress PHP auto warnings.
ini_set("display_errors", 0);

// Get the values of the record to be inserted.
$username = trim($_POST["username"]);
if ($username == "")
    $username = "NULL";

$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$address = $_POST["address"];
$studenttype = $_POST["studenttype"];
$status = $_POST["status"];
$sql = array("set transaction isolation level serializable");
$sql[] = "select maxint from idtrack where maxid = 'max'";


$result_array = execute_sqls_in_mysql_without_commit($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false) {
    die("Client Query Failed.");
}

$values = mysqli_fetch_array($cursor);
$maxid = $values[0];
$newmax = $maxid + 1;
$n = strlen($newmax);
$padding = "";
for ($i = 0; $i + $n < 6; $i++) {
    $padding .= "0";
}
$newmax = $padding . $newmax;
$id = "" . strtolower(substr($firstname, 0, 1)) . strtolower(substr($lastname, 0, 1)) . $newmax;

$sql = "insert into students values ('$id', '$username', '$firstname', '$lastname', '$address', '$studenttype', '$status')";
$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false) {
    echo "<B>Insertion Failed.</B> <BR />";
    die("<i> 

  <form method=\"post\" action=\"student_add?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$username\" name=\"username\">
  <input type=\"hidden\" value = \"$firstname\" name=\"firstname\">
  <input type=\"hidden\" value = \"$lastname\" name=\"lastname\">
  <input type=\"hidden\" value = \"$address\" name=\"address\">    
  <input type=\"hidden\" name=\"studenttype\" value=\"$studenttype\"> 
  <input type=\"hidden\" name=\"status\" value=\"$status\"> 
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
} else {
    
    // Record inserted.  Go back.
    Header("Location:manage.php?sessionid=$sessionid");
 
}

?>
