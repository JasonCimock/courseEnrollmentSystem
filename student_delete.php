<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid = $_GET["sessionid"];
verify_session($sessionid, $access);
$id = $_GET["id"];

// Fetech the record to be deleted and display it
$sql = "select * from students s where s.id = '$id'";
//echo($sql);

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false) {
    die("Client Query Failed.");
}

if (!($values = mysqli_fetch_array($cursor))) {
    // Record already deleted by a separate session.  Go back.
    echo("Record already deleted by a separate session.  Go back.");
    //Header("Location:manage.php?sessionid=$sessionid");
}
$id = $values[0];
$username = $values[1];
$firstname = $values[2];
$lastname = $values[3];
$address = $values[4];
$studenttype = $values[5];
$status = $values[6];

echo("
  <form method=\"post\" action=\"student_delete_action.php?sessionid=$sessionid\">
  student id (Read-only): <input type=\"text\" readonly value = \"$id\" size=\"10\" maxlength=\"10\" name=\"id\"> <br />     
  Username (Read-only): <input type=\"text\" readonly value = \"$username\" size=\"10\" maxlength=\"10\" name=\"username\"> <br /> 
  First name (Read-only: <input type=\"text\" readonly value = \"$firstname\" size=\"20\" maxlength=\"30\" name=\"firstname\">  <br />
      Last name (Read-only: <input type=\"text\" readonly value = \"$lastname\" size=\"20\" maxlength=\"30\" name=\"lastname\">  <br />
  Address (Read-only: <input type=\"text\" readonly value = \"$address\" size=\"20\" maxlength=\"30\" name=\"address\">  <br />    
  ");
if ($studenttype == 'u') {
    echo ("Student type (Read-only): <input type=\"radio\" readonly checked name=\"studenttype\" values=\"u\">Undergraduate <input type=\"radio\" readonly name=\"studenttype\" values=\"g\">Graduate <br />");
}
else {
    echo ("Student type (Read-only): <input type=\"radio\" readonly name=\"studenttype\" values=\"u\">Undergraduate <input type=\"radio\" checked readonly name=\"studenttype\" values=\"g\">Graduate <br />");
}
if ($status == 'g') {
  echo ("Status (Read-only): <input type=\"radio\" readonly checked name=\"status\" values=\"g\">Good standing <input type=\"radio\" readonly name=\"status\" values=\"p\">On Probation <br />");
}
else {
    echo ("Status (Read-only): <input type=\"radio\" readonly name=\"status\" values=\"g\">Good standing <input type=\"radio\" checked readonly name=\"status\" values=\"p\">On Probation <br />");
}
  
echo("
  <input type=\"submit\" value=\"Delete\">
  </form>
  
  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");

?>
