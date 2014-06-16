<?php

include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

// Verify where we are from, manage.php or  student_update_action.php.
if (!isset($_POST["update_fail"])) { // from manage.php
  // Fetch the record to be updated.
  $id = $_GET["id"];

  // the sql string
  $sql = "select id, firstname, lastname, address, studenttype, status from students where id = '$id'";
  //echo($sql);

  $result_array = execute_sql_in_mysql($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($cursor == false){
    die("Query Failed.");
  }

  $values = mysqli_fetch_array ($cursor);
  $id = $values[0];
  $firstname = $values[1];
  $lastname = $values[2];
  $address = $values[3];
  $studenttype = $values[4];
  $status = $values[5];
}
else { // from user_update_action.php
  // Obtain values of the record to be updated directly.
  $id = $_POST["id"];
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $address = $_POST["address"];
  $studenttype = $_POST["studenttype"];
  $status = $_POST["status"];
  
}


echo("
  <form method=\"post\" action=\"student_update_action.php?sessionid=$sessionid\">
  id: <input type=\"text\" value = \"$id\" size=\"10\" maxlength=\"10\" name=\"id\"> <br /> 
  First name: <input type=\"text\" value = \"$firstname\" size=\"20\" maxlength=\"30\" name=\"firstname\">  <br />
  Last name: <input type=\"text\" value = \"$lastname\" size=\"20\" maxlength=\"30\" name=\"lastname\">  <br />
  Address: <input type=\"text\" value = \"$address\" size=\"20\" maxlength=\"30\" name=\"address\">  <br />
");

if ($studenttype == 'u') {
    echo("Student type:<input type=\"radio\" checked name=\"studenttype\" value=\"u\">Undergraduate ");
    echo("<input type=\"radio\" name=\"studenttype\" value=\"g\">Graduate <br />");
}
else {
    echo("Student type:<input type=\"radio\" name=\"studenttype\" value=\"u\">Undergraduate");
    echo("<input type=\"radio\" name=\"studenttype\" checked value=\"g\">Graduate <br />");
}

if ($status == 'g') {
    echo("Status:<input type=\"radio\" checked name=\"status\" value=\"g\">Good standing ");
    echo("<input type=\"radio\" name=\"status\" value=\"p\">Probation <br />");
}
else {
    echo("Status:<input type=\"radio\" name=\"status\" value=\"g\">Good standing ");
    echo("<input type=\"radio\" name=\"status\" checked value=\"p\">Probation <br />");
}

echo("  
  <input type=\"submit\" value=\"Update\">
  <input type=\"reset\" value=\"Reset to Original Value\">
  </form>

  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");


?>
