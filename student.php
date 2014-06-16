<?

include "utility_functions.php";

//Access level
$access = "s";

$sessionid = $_GET["sessionid"];
verify_session($sessionid, $access);
$student_admin = student_admin_nav_check($sessionid);

$sql = "select id, firstname, lastname, address, studenttype, status from users u, students s, usersession n
where u.username = s.username and s.username = n.username and n.sessionid = '$sessionid'";

$result_array = execute_sql_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false) {
    //display_oracle_error_message($cursor);
    die("Client Query Failed.");
}

if ($values = mysqli_fetch_array($cursor)) {
    //oci_free_statement($cursor);
    $id = $values[0];
    $firstname = $values[1];
    $lastname = $values[2];
    $address = $values[3];
    if ($values[4] == 'u') {
        $studenttype = "Undergraduate";
    } else {
        $studenttype = "Graduate";
    }

    if ($values[5] == 'g') {
        $status = "Good Standing";
    } else {
        $status = "On Probation";
    }
    echo ("<h1>Student Dashboard</h1>");
    echo ("<h2>Student Info</h2><table border=1>");
    echo ("<tr> <th>ID</th> <th>First Name</th> <th>Last Name</th> <th>Address</th> <th>Student Type</th> <th>Probationary Status</th></tr>");
    echo ("<tr> <td>$id</td> <td>$firstname</td> <td>$lastname</td> <td>$address</td> <td>$studenttype</td> <td>$status</td></tr>");
    echo ("</table>");

    $sql = "select s.seqid, c.coursenumber, c.coursename, s.semester, s.year, c.credits, t.grade from courses c, sections s, taken t
  where c.coursenumber = s.coursenumber and s.seqid = t. seqid and t.id = '$id'";
    
    $result_array = execute_sql_in_mysql($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($cursor == false) {
        //display_oracle_error_message($cursor);
        die("Client Query Failed.");
    }

    echo "<br /><h2>Course Info</h2><table border=1>";
    echo "<tr> <th>SeqID</th> <th>Course Number</th> <th>Course Title</th> <th>Semester</th> <th>Year</th> <th>Credits</th> <th>Grade</th></tr>";

    $courses_taken = 0;
    $gpa_sum = 0;
    $credit_sum = 0;
    while ($values = mysqli_fetch_array($cursor)) {
        $seqid = $values[0];
        $coursenumber = $values[1];
        $coursename = $values[2];
        if ($values[3] == 'f') {
            $semester = "Fall";
        } else if ($values[3] == 's') {
            $semester = "Spring";
        } else if ($values[3] == 'u') {
            $semester = "Summer";
        }
        $year = $values[4];
        $credits = $values[5];
        $grade = $values[6];
        echo("<tr><td>$seqid</td><td>$coursenumber</td><td>$coursename</td><td>$semester</td><td>$year</td><td>$credits</td><td>$grade</td></tr>");

        if ($values[6] != NULL) {
            $courses_taken++;
            $gpa_sum += ($values[6] * $values[5]);
            $credit_sum += $values[5];
        }
        $overall_gpa = $gpa_sum / $credit_sum;
    }
    //oci_free_statement($cursor);
    echo ("</table>");
    echo ("<br /><table border=1>");
    echo ("<tr><th>Number of Courses Completed</th><th>Credits Earned</th><th>Overall GPA</th></tr>");
    echo ("<tr><td>$courses_taken</td><td>$credit_sum</td><td>$overall_gpa</td></tr>");
    echo ("</table><br />");
    echo("
    <form method=\"post\" action=\"register.php?sessionid=$sessionid\">
    <input type=\"submit\" value=\"Search and Register for Courses\">
    </form>
    ");
}

echo("
  <h2>Password Reset</h2>  
  <form method=\"post\" action=\"student_password_action.php?sessionid=$sessionid\">
  Username: <input type=\"text\" value = \"$username\" size=\"10\" maxlength=\"10\" name=\"username\"> <br /> 
  Old Password: <input type=\"text\" value = \"$oldpassword\" size=\"20\" maxlength=\"30\" name=\"oldpassword\">  <br />
  New Password: <input type=\"text\" value = \"$newpassword\" size=\"20\" maxlength=\"30\" name=\"newpassword\">  <br />
  <input type=\"submit\" value=\"Update\">
  <input type=\"reset\" value=\"Clear Form\">
  </form> <br />
");

if ($student_admin) {
  echo("
  <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back to Welcome Page\">
  </form>
  ");
}
echo("<br />");
echo("Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.");
?>