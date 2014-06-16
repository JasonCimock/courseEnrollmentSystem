<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid = $_GET["sessionid"];
verify_session($sessionid, $access);

// Suppress PHP auto warnings.
ini_set("display_errors", 0);

$id = $_POST["id"];
$seqid = $_POST["seqid"];
$grade = $_POST["grade"];

$sql = "update taken set grade = '$grade' where id = '$id' and seqid = '$seqid'";

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false) {
    echo "<B>Insertion Failed.</B> <BR />";
    die("<i> 

  <form method=\"post\" action=\"enter_grades?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$id\" name=\"id\">
  <input type=\"hidden\" value = \"$seqid\" name=\"seqid\">
  <input type=\"hidden\" value = \"$grade\" name=\"grade\">
    
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

Header("Location:manage.php?sessionid=$sessionid");
?>
