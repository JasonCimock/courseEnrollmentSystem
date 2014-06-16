<?
include "utility_functions.php";

//Access level
$access = "b";

$sessionid =$_GET["sessionid"];
verify_session($sessionid, $access);


echo("<h1>Course Enrollment Student Administrator Menu: </h1>");
echo("<UL>
  <LI><A HREF=\"manage.php?sessionid=$sessionid\">Admin Interface</A></LI>
  <LI><A HREF=\"student.php?sessionid=$sessionid\">Student Interface</A></LI>
  </UL>");

echo("<br />");
echo("<br />");
echo("Click <A HREF = \"logout_action.php?sessionid=$sessionid\">here</A> to Logout.");
?>