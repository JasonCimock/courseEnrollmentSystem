<?
include "utility_functions.php";

// Get the client id and password and verify them
$clientid = $_POST["clientid"];
$password = $_POST["password"];

$sql = "select username, isstudent, isadmin " .
       "from users " .
       "where username='$clientid'
         and passw='$password'";


$result_array = execute_sql_in_mysql ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  //display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

 
if($values = mysqli_fetch_array ($cursor)){
  //oci_free_statement($cursor);

  // found the client
  $clientid = $values[0];
  $isstudent = $values[1];
  $isadmin = $values[2];

  // create a new session for this client
  $sessionid = md5(uniqid(rand()));

  // store the link between the sessionid and the clientid
  // and when the session started in the session table

  $sql = "insert into usersession " .
    "(sessionid, username, sessiondate) " .
    "values ('$sessionid', '$clientid', sysdate())";

  $result_array = execute_sql_in_mysql ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($cursor == false){
    //display_oracle_error_message($cursor);
    die("Failed to create a new session");
  }
  else {

    // OK - we have created a new session
    // Direct based on user type
    if (($isstudent == 'y' or $isstudent == 'Y') and ($isadmin == 'y' or $isadmin == 'Y')) {
     //echo("<p>Test1</p>"); 
     header("Location:welcomepage.php?sessionid=$sessionid");      
    }
    elseif ($isstudent == 'y' or $isstudent == 'Y') {
      //echo("<p>Test2</p>");
      header("Location:student.php?sessionid=$sessionid");
    }
    else {
      //echo("<p>Test3</p>");
      header("Location:manage.php?sessionid=$sessionid");
    }
  }

}
else { 
  // client username not found
  die ('Login failed.  Click <A href="login.html">here</A> to go back to the login page.');
}
 
?>