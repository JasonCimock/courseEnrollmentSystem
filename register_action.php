<?php

include "utility_functions.php";
include "prereq_check.php";

//Access level
$access = "s";

$sessionid = $_GET["sessionid"];
$user_info = verify_session($sessionid, $access);

$seqids = $_POST['seqids'];
if (empty($seqids)) {
    echo("You didn't select any courses.");
} else {
    $n = count($seqids);
    $error_messages = array();
    
    
    for ($i = 0; $i < $n; $i++) {
        $already_registered = false;
        $sql = "select seqid from taken where id = '$user_info[1]'";
        $result_array = execute_sql_in_mysql($sql);
        $result = $result_array["flag"];
        $cursor = $result_array["cursor"];

        if ($cursor == false) {
            //display_oracle_error_message($cursor);
            die("Client Query Failed.");
        }
        while($values = mysqli_fetch_array($cursor)) {
           if ($values[0] == $seqids[$i]) {
               $already_registered = true;
           }
        }
        
        $class_full = false;
        $prereq_check_result = prereq_check($user_info[0], $seqids[$i]);
        $sql = array("set transaction isolation level serializable");
        $sql[] = "create or replace view seat_count(seqid, seat_count) as select seqid, count(*) from taken t group by seqid order by seqid";
        $sql[] = "select c.seat_count, s.seats from sections s left join seat_count c on s.seqid = c.seqid where s.seqid = '$seqids[$i]'";
        //$sql[] = "drop view seat_count";
        //var_dump($sql);
        $result_array = execute_sqls_in_mysql_without_commit($sql);
        $result = $result_array["flag"];
        $cursor = $result_array["cursor"];

        if ($cursor == false) {
            //display_oracle_error_message($cursor);
            die("Client Query Failed.");
        }
        
        $values = mysqli_fetch_array($cursor);
        mysqli_free_result($cursor);
        $sql_clear = array("drop view seat_count", "commit");
        $result_array = execute_sqls_in_mysql($sql_clear);
        if ($values[0] == '') {
            $values[0] = 0;
        }
        if ($values[0] >= $values[1]) {
            $class_full = true;
        }
        
        if ($prereq_check_result == false and !$class_full and !$already_registered) {
            $sql = "insert into taken values ('$user_info[1]', '$seqids[$i]', null)";
            $result_array = execute_sql_in_mysql($sql);
            $result = $result_array["flag"];
            $cursor = $result_array["cursor"];

            if ($cursor == false) {
                //display_oracle_error_message($cursor);
                die("Client Query Failed.");
            }
            echo("Registration for '$seqids[$i]' was successful. <br />");
        }
        else {
            $sql = "commit";
            $result_array = execute_sql_in_mysql($sql);
            $result = $result_array["flag"];
            $cursor = $result_array["cursor"];

            if ($cursor == false) {
                //display_oracle_error_message($cursor);
                die("Client Query Failed.");
            }
            if ($prereq_check_result) {
                $count = count($prereq_check_result);
                echo("Could not register in section: $seqids[$i]. The following prerequisites have not been met: <br />");
                //$error_messages[] = "Could not register in section: $seqids[$i]. The following prerequisites have not been met: <br />";
                for ($i = 0; $i < $count; $i++) {
                    echo("$prereq_check_result[$i] <br />");
                    //$error_messages[] = "$prereq_check_result[$i] <br />";
                }
            }
            if ($already_registered) {
                echo("Could not register in section: $seqids[$i]. You are already registered for that section. <br />");
            }
            if ($class_full) {
                echo("Could not register in section: $seqids[$i]. Section is full. <br />");
                //$error_messages[] = "Could not register in section: $seqids[$i]. Section is full. <br />";
            }
        }
    }
        
    echo("<br /> <a href = \"register.php?sessionid=$sessionid\">Back</a>");
}
?>
