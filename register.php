<?php
include "utility_functions.php";

//Access level
$access = "s";

$sessionid = $_GET["sessionid"];
verify_session($sessionid, $access);

//$student_admin = student_admin_nav_check($sessionid);
//$error_messages = $_POST["error_messages[]"];
//var_dump($error_messages);
/*
if (isset($error_messages)) { 
  for ($i = 0; $i < count($error_messages); $i++) {
      echo("$error_messages[$i]");
  }   
}
 * 
 */
echo("<h1>Search and Register for Courses</h1>");
echo("
  <form method=\"post\" action=\"register.php?sessionid=$sessionid\"> 
  Semester (f = Fall, s = Spring, u = Summer: <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_semester\"> 
  Course Number (e.g. cs1000, cs): <input type=\"text\" size=\"20\" maxlength=\"30\" name=\"q_coursenumber\"> 
  ");

echo("
  <input type=\"submit\" value=\"Search\">
  </form>
  If the enrollment date for a course has passed, it will not be listed.
  
");

// Interpret the query requirements
$q_semester = $_POST["q_semester"];
$q_coursenumber = $_POST["q_coursenumber"];

$whereClause = "";
$check = FALSE;

if (isset($q_semester) and $q_semester != "") { 
  $whereClause .= "s.semester like '%$q_semester%'";
  $check = TRUE; 
}

if (isset($q_coursenumber) and $q_coursenumber != "" and $check == TRUE) { 
  $whereClause .= " and c.coursenumber like '%$q_coursenumber%'"; 
}
else if (isset($q_coursenumber) and $q_coursenumber != "") {
  $whereClause .= "c.coursenumber like '%$q_coursenumber%'";
  $check = TRUE;
}

// Form the query statement and run it.
if ($whereClause == "") {
  $sql = array("1" => "create or replace view seat_count(seqid, seat_count) as select seqid, count(*) from taken t group by seqid order by seqid",
       "2" => "select s.seqid, c.coursenumber, c.coursename, c.credits, s.semester, s.year, s.time, s.seats, seat_count from courses c, enrollmentdates e, sections s left join seat_count t on s.seqid = t.seqid where c.coursenumber = s.coursenumber and s.semester = e.semester and s.year = e.year and e.edate > sysdate() order by c.coursenumber");
}
else {
  $sql = array("1" => "create or replace view seat_count(seqid, seat_count) as select seqid, count(*) from taken t group by seqid order by seqid",
       "2" => "select s.seqid, c.coursenumber, c.coursename, c.credits, s.semester, s.year, s.time, s.seats, seat_count from courses c, enrollmentdates e, sections s left join seat_count t on s.seqid = t.seqid where c.coursenumber = s.coursenumber and s.semester = e.semester and s.year = e.year and e.edate > sysdate() and $whereClause order by c.coursenumber");
}

$result_array = execute_sqls_in_mysql($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($cursor == false){
  //display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

echo ("<br /><h2>Courses</h2>
       <form method=\"post\" action=\"register_action.php?sessionid=$sessionid\">
       <table border=1>");
echo ("<tr><th>Select</th> <th>SeqID</th> <th>Course Number</th> <th>Course Title</th> <th>Credits</th>
    <th>Semester</th> <th>Year</th> <th>Time</th><th>Max Seats</th><th>Seats Available</th></tr>");
while ($values = mysqli_fetch_array($cursor)) {
        $seqid = $values[0];
        $coursenumber = $values[1];
        $coursename = $values[2];
        $credits = $values[3];
        if ($values[4] == 'f') {
            $semester = "Fall";
        }
        else if ($values[4] == 's') {
            $semester = "Spring";
        }
        else if ($values[4] == 'u') {
            $semester = "Summer";
        }
        $year = $values[5];
        $time = $values[6];
        $seats = $values[7];
        if ($values[8] == '') {
            $seats_available = $seats;
        }
        else {
            $seats_available = $seats - $values[8];
        }    
        echo("<tr><td><input type=\"checkbox\" name=\"seqids[]\" value=\"$seqid\"></td><td>$seqid</td><td>$coursenumber</td><td>$coursename</td><td>$credits</td><td>$semester</td>
            <td>$year</td><td>$time</td><td>$seats</td><td>$seats_available</td></tr>");
}
//oci_free_statement($cursor);
echo("</table><br />
     <input type=\"submit\" value=\"Register\">
     </form><br />
");
echo("
    <form method=\"post\" action=\"student.php?sessionid=$sessionid\">
    <input type=\"submit\" value=\"Back to Student Dashboard\">
    </form>
    ");
//$sql = "drop view seat_count";
//$result_array = execute_sql_in_oracle ($sql);


?>
