<?php

function prereq_check($username, $seqid) {
    $sql = "select distinct p.reqcoursenumber from sections s, prereq p where s.coursenumber = p.basecoursenumber and s.seqid = '$seqid'";
    $result_array = execute_sql_in_mysql($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];
    
    if ($cursor == false) {
        die("SQL Execution problem.");
    }

    $prereqs = array();
    while ($values = mysqli_fetch_array($cursor)) {
        $prereqs[] = $values[0];
    }
      
    //oci_free_statement($cursor);

    if (count($prereqs) == 0) {
        return false;
    }
    
    $n = count($prereqs);
   
    $prereq_result = array();
    for ($i = 0; $i < $n; $i++) {
        $sql = "select * from users u, sections s, taken t, students d where u.username = d.username
            and d.id = t.id and t.seqid = s.seqid and u.username = '$username' and s.coursenumber = '$prereqs[$i]'
                and not exists (select * from taken n where d.id = n.id and n.grade is null)";
        $result_array = execute_sql_in_mysql($sql);
        $result = $result_array["flag"];
        $cursor = $result_array["cursor"];

        if ($cursor == false) {
            die("SQL Execution problem.");
        }
              
       if (!($values = mysqli_fetch_array($cursor))) {
        //    echo($values[0]);
            $prereq_result[] = $prereqs[$i];
        }
        //oci_free_statement($cursor);
    }
    $n = count($prereq_result);
    if ($n > 0) {
        return $prereq_result;
    }
    else {
        return false;
    }
}

?>
