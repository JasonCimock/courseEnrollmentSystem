<?
include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Suppress PHP auto warnings.
ini_set( "display_errors", 0);  

// Get the values of the record to be inserted.
$username = trim($_POST["username"]);
if ($username == "") $username = "NULL";

$password = $_POST["password"];
$isstudent = $_POST["isstudent"];
$isadmin = $_POST["isadmin"];

if ($isstudent == 'y') {
    $isstudent = 'y';
}
else {
    $isstudent = 'n';
}

if ($isadmin == 'y') {
    $isadmin = 'y';
}
else {
    $isadmin = 'n';
}

// Form the insertion sql string and run it.
$sql = "insert into users values ('$username', '$password', '$isstudent', '$isadmin')";
//echo($sql);

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  // Error handling interface.
  echo "<B>Insertion Failed.</B> <BR />";
  die("<i> 

  <form method=\"post\" action=\"user_add?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$username\" name=\"username\">
  <input type=\"hidden\" value = \"$password\" name=\"password\">
  <input type=\"hidden\" name=\"isstudent\" value=\"$isstudent\"> <br />
  <input type=\"hidden\" name=\"isstudent\" value=\"$isadmin\"> <br />
  
  Read the error message, and then try again:
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

// Record inserted.  Go back.
Header("Location:manage.php?sessionid=$sessionid");
?>