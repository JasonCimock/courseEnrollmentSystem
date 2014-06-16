<?
include "utility_functions.php";

//Access level
$access = "a";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);

$student_admin = student_admin_nav_check($sessionid);

// Generate the query section
echo("
  <h1>Admin Dashboard</h1>
  <h2>User Info and Search</h2>
  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\"> 
  Username: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_uname\"> 
  Password: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_password\">
  Student<input type=\"checkbox\" name=\"q_isstudent\" value=\"true\">
  Administrator<input type=\"checkbox\" name=\"q_isadmin\" value=\"true\">
  ");

echo("
  <input type=\"submit\" value=\"Search\">
  </form>
");

// Interpret the query requirements
$q_uname = $_POST["q_uname"];
$q_password = $_POST["q_password"];
$q_isstudent = $_POST["q_isstudent"];
$q_isadmin = $_POST["q_isadmin"];


$whereClause = "";
$check = FALSE;

if (isset($q_uname) and $q_uname != "") { 
  $whereClause .= "username like '%$q_uname%'";
  $check = TRUE; 
}

if (isset($q_password) and $q_password != "" and $check == TRUE) { 
  $whereClause .= " and passw like '%$q_password%'"; 
}
else if (isset($q_password) and $q_password != "") {
  $whereClause .= "passw like '%$q_password%'";
  $check = TRUE;
}

if (isset($q_isstudent) and $q_isstudent != "" and $check == TRUE) { 
  $whereClause .= " and isstudent = 'y'"; 
}
else if (isset($q_isstudent) and $q_isstudent != ""){
  $whereClause .= "isstudent = 'y'";
  $check = TRUE;
}

if (isset($q_isadmin) and $q_isadmin != "" and $check == TRUE) { 
  $whereClause .= " and isadmin = 'y'"; 
}
else if (isset($q_isadmin) and $q_isadmin != ""){
  $whereClause .= "isadmin = 'y'";
  $check = TRUE;
}

// Form the query statement and run it.
if ($whereClause == "") {
  $sql = "select username, passw, isstudent, isadmin
  from users order by username";
}
else {
  $sql = "select username, passw, isstudent, isadmin
  from users where $whereClause order by username";
}

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  die("Client Query Failed.");
}

// Display the query results
echo "<table border=1>";
echo "<tr> <th>Username</th> <th>Password</th> <th>Student</th> <th>Admin</th> <th>Update</th> <th>Delete</th> <th>Password Reset</th></tr>";

// Fetch the result from the cursor one by one
while ($values = mysqli_fetch_array ($cursor)){
  $username = $values[0];
  $password = $values[1];
  if ($values[2] == 'y') {
      $isstudent = "Yes";
  }
  else {
      $isstudent = "No";
  }
  if ($values[3] == 'y') {
      $isadmin = "Yes";
  }
  else {
      $isadmin = "No";
  }
  echo("<tr>" . 
    "<td>$username</td> <td>$password</td> <td>$isstudent</td> <td>$isadmin</td>".
    " <td> <A HREF=\"user_update.php?sessionid=$sessionid&username=$username\">Update</A> </td> ".
    " <td> <A HREF=\"user_delete.php?sessionid=$sessionid&username=$username\">Delete</A> </td> ".
    " <td> <A HREF=\"user_password_reset_action.php?sessionid=$sessionid&username=$username\">Reset</A> </td> ".
    "</tr>");
}
echo "</table>";
echo("<br />");
echo("
  <form method=\"post\" action=\"user_add.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add A New User\">
  </form>
  ");

echo ("<h2>Student Info and Search</h2>
  <form method=\"post\" action=\"manage.php?sessionid=$sessionid\"> 
  First name: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"firstname\"> 
  Last name: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"lastname\">
  Student id: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"id\"><br />
  Course number: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"coursenumber\"> 
  Student type: <input type=\"radio\" name=\"type\" value=\"u\">Undergraduate <input type=\"radio\" name=\"type\" value=\"g\">Graduate<br />
  Status: <input type=\"radio\" name=\"status\" value=\"g\">Good Standing <input type=\"radio\" name=\"status\" value=\"p\">Probation
");
echo("
  <input type=\"submit\" value=\"Search\">
  </form>
");

// Interpret the query requirements
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$id = $_POST["id"];
$coursenumber = $_POST["coursenumber"];
$type = $_POST["type"];
$status = $_POST["status"];

$whereClause = "";
$check = FALSE;

if (isset($firstname) and $firstname != "") { 
  $whereClause .= " and firstname like '%$firstname%'";
  $check = TRUE; 
}

if (isset($lastname) and $lastname != "" and $check == TRUE) { 
  $whereClause .= " and lastname like '%$lastname%'"; 
}
else if (isset($lastname) and $lastname != "") {
  $whereClause .= " and lastname like '%$lastname%'";
  $check = TRUE;
}
if (isset($id) and $id != "" and $check == TRUE) { 
  $whereClause .= " and s.id like '%$id%'"; 
}
else if (isset($id) and $id != "") {
  $whereClause .= " and s.id like '%$id%'";
  $check = TRUE;
}
if (isset($coursenumber) and $coursenumber != "" and $check == TRUE) { 
  $whereClause .= " and c.coursenumber like '%$coursenumber%'"; 
}
else if (isset($coursenumber) and $coursenumber != "") {
  $whereClause .= " and c.coursenumber like '%$coursenumber%'";
  $check = TRUE;
}
if (isset($type) and $type != "" and $check == TRUE) { 
  $whereClause .= " and studenttype like '%$type%'"; 
}
else if (isset($type) and $type != "") {
  $whereClause .= " and studenttype like '%$type%'";
  $check = TRUE;
}
if (isset($status) and $status != "" and $check == TRUE) { 
  $whereClause .= " and status like '%$status%'"; 
}
else if (isset($status) and $status != "") {
  $whereClause .= " and status like '%$status%'";
  $check = TRUE;
}

// Form the query statement and run it.
if ($whereClause == "") {
  $sql = "select id, firstname, lastname, studenttype, status
  from students order by id";
}
else {
  $sql = "select distinct s.id, s.firstname, s.lastname, s.studenttype, s.status
  from students s, sections c, taken t where s.id = t.id and t.seqid = c.seqid $whereClause order by id";
}

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  die("Client Query Failed.");
}

// Display the query results
echo "<table border=1>";
echo "<tr> <th>id</th> <th>First Name</th> <th>Last Name</th> <th>Student Type</th> <th>Status</th> <th>Update</th> <th>Delete</th></tr>";

// Fetch the result from the cursor one by one
while ($values = mysqli_fetch_array ($cursor)){
    $id = $values[0];
    $firstname = $values[1];
    $lastname = $values[2];
    if ($values[3] == 'u') {
        $studenttype = "Undergraduate";
    }
    else if ($values[3] == 'g') {
        $studenttype = "Graduate";
    }
    if ($values[4] == 'g') {
        $status = "Good Standing";
    }
    else if ($values[4] == 'p') {
        $status = "On Probation";
    }
    echo("<tr>" . 
    "<td>$id</td> <td>$firstname</td> <td>$lastname</td> <td>$studenttype</td> <td>$status</td>".
    " <td> <A HREF=\"student_update.php?sessionid=$sessionid&id=$id\">Update</A> </td> ".
    " <td> <A HREF=\"student_delete.php?sessionid=$sessionid&id=$id\">Delete</A> </td> ".
    "</tr>");
}
echo("</table>");
echo("<br />");
echo("
  <form method=\"post\" action=\"student_add.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add A New Student\">
  </form>
  ");
echo("
  <form method=\"post\" action=\"enter_grades.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Enter grades\">
  </form>
  ");

if ($student_admin) {
  echo("
  <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back to Welcome Page\">
  </form>
  ");
}

echo("<br />Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.");

?>