<?

// Contains commonly used functions.
//********************
// Run the sql, and return the error flag and the cursor in an array
// The array index "flag" contains the flag.
// The array index "cursor" contains the cursor.
//********************
function execute_sql_in_mysql($sql) {
    //putenv("ORACLE_HOME=/home/oracle/OraHome1");
    //putenv("ORACLE_SID=orcl");

    $connection = mysqli_connect("pdb7.awardspace.net", "1561537_test", "mydbtest1", "1561537_test");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $cursor = mysqli_query($connection, $sql);

    if ($cursor == false) {
        //display_oracle_error_message($connection);
        //oci_close ($connection);
        // sql failed 
        die("SQL Parsing Failed");
    }

    mysqli_commit($connection);
    mysqli_close($connection);

    /*
      $result = oci_execute($cursor);

      if ($result == false) {
      display_oracle_error_message($cursor);
      oci_close ($connection);
      // sql failed
      die("SQL execution Failed");
      }

      // commit the result
      oci_commit ($connection);

      // close the connection with oracle
      oci_close ($connection);

      $return_array["flag"] = $result;
     * 
     */
    $return_array["cursor"] = $cursor;

    return $return_array;
}

function execute_sqls_in_mysql($sql) {
    //putenv("ORACLE_HOME=/home/oracle/OraHome1");
    //putenv("ORACLE_SID=orcl");

    $connection = mysqli_connect("pdb7.awardspace.net", "1561537_test", "mydbtest1", "1561537_test");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    foreach ($sql as $value) {
        $cursor = mysqli_query($connection, $value);
        
        if ($cursor == false) {
            //display_oracle_error_message($cursor);
            mysqli_close($connection);
            // sql failed 
            die("SQL execution Failed");
        }
        // commit the result
        mysqli_commit($connection);
    }

    mysqli_close($connection);

    $return_array["flag"] = $result;
    $return_array["cursor"] = $cursor;

    return $return_array;
}

function execute_sqls_in_mysql_without_commit($sql) {
    //putenv("ORACLE_HOME=/home/oracle/OraHome1");
    //putenv("ORACLE_SID=orcl");
    $return_array["flag"] = "";
    $return_array["cursor"] = "";
    $cursor = "";
    $connection = mysqli_connect("pdb7.awardspace.net", "1561537_test", "mydbtest1", "1561537_test");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    foreach ($sql as $value) {
        $cursor = mysqli_query($connection, $value);
        
        if ($cursor == false) {
            //display_oracle_error_message($cursor);
            mysqli_close($connection);
            // sql failed 
            die("SQL execution Failed");
        }
        
        $return_array["flag"] = $result;
        $return_array["cursor"] = $cursor;
    }      
    return $return_array;
}

//********************
// Verify the session id and access level of user. Access level of each page is sent by caller.  
// Return normally if id and access level are appropriate.
// Terminate the script otherwise.
//********************
function verify_session($sessionid, $usertype) {
    // lookup the sessionid in the session table to ascertain the clientid 
    if ($usertype == 's' or $usertype == 'b') {
        $sql = "select u.username, id, isstudent, isadmin " .
                "from users u, usersession s, students t " .
                "where u.username=s.username and u.username = t.username and sessionid='$sessionid'";
    } else {
        $sql = "select u.username, isstudent, isadmin " .
                "from users u, usersession s " .
                "where u.username=s.username and sessionid='$sessionid'";
    }

    $result_array = execute_sql_in_mysql($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    if ($cursor == false) {
        //display_oracle_error_message($cursor);
        die("SQL Execution problem.");
    }

    if (!($values = mysqli_fetch_array($cursor))) {
        // no active session - clientid is unknown
        die("Invalid client!");
    }

    if ($usertype == 's' and ($values[2] != 'y' and $values[2] != 'Y')) {
        //user type not allowed on this page
        die("Invalid usertype!");
    } else if ($usertype == 'a' and ($values[2] != 'y' and $values[2] != 'Y')) {
        //user type not allowed on this page
        die("Invalid usertype!");
    } else if ($usertype == 'b' and ($values[2] != 'y' and $values[2] != 'Y') and ($values[3] != 'y' and $values[3] != 'Y')) {
        //user type not allowed on this page
        die("Invalid usertype!");
    }
    if ($usertype == 's' or $usertype == 'b') {
        $user_info = array($values[0], $values[1]);
    } else {
        $user_info = $values[0];
    }
    //oci_free_statement($cursor);
    return $user_info;
}

//********************
// Verify client id is a student-admin usertype. Return TRUE if clientid is a student-admin, FALSE if not.  
//********************
function student_admin_nav_check($sessionid) {
// lookup the sessionid in the session table to ascertain the clientid, then use client id to verify student-administrator access level
    $sql = "select u.username, isstudent, isadmin " .
            "from users u, usersession s " .
            "where u.username=s.username and sessionid='$sessionid' and (isstudent='y' or isstudent='Y') and (isadmin='y' or isadmin='Y')";

    $result_array = execute_sql_in_mysql($sql);
    $result = $result_array["flag"];
    $cursor = $result_array["cursor"];

    //$result = oci_execute($cursor);
    /*
      if ($result == false){
      //display_oracle_error_message($cursor);
      die("SQL Execution problem.");
      }
     * 
     */

    $return_variable = "";
    if (!($values = mysqli_fetch_array($cursor))) {
        // no record found with student-administrator usertype
        $return_variable = FALSE;
    } else {
        $return_variable = TRUE;
    }
    //oci_free_statement($cursor);
    return $return_variable;
}

//********************
// Takes an executed errored oracle cursor as input.
// Display an initerpreted error message.
//********************
function display_oracle_error_message($resource) {
    if (is_null($resource))
        $err = oci_error();
    else
        $err = oci_error($resource);

    echo "<BR />";
    echo "Oracle Error Code: " . $err['code'] . "<BR />";
    echo "Oracle Error Message: " . $err['message'] . "<BR />" . "<BR />";

    if ($err['code'] == 1)
        echo("Duplicate Values.  <BR /><BR />");
    else if ($err['code'] == 984 or $err['code'] == 1861 or $err['code'] == 1830 or $err['code'] == 1839 or $err['code'] == 1847 or $err['code'] == 1858 or $err['code'] == 1841)
        echo("Wrong type of value entered.  <BR /><BR />");
    else if ($err['code'] == 1400 or $err['code'] == 1407)
        echo("Required field not correctly filled.  <BR /><BR />");
    else if ($err['code'] == 2292)
        echo("Child records exist.  Need to delete or update them first.  <BR /><BR />");
}

?>