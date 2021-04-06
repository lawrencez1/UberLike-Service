<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Driver Management</title>
    <?php include "header.inc.php";?>
</head>

<body>
<?php include "nav.inc.php";?>
<div class="container">

    <h2>Find uninsured drivers</h2>
    <form method="GET" action="driverInfo.php"> <!--refresh page when submitted-->
        <input type="hidden" id="findUninsuredRequest" name="findUninsuredRequest">
        <input type="submit" name="findUninsured" style="width:150px; height:40px;solid #FBFBFB;"></p>
    </form>

    <hr />

    <h2>Find the number of drivers working a certain shift in a region</h2>
    <form method="GET" action="driverInfo.php"> <!--refresh page when submitted-->
        <input type="hidden" id="displayShiftRequest" name="displayShiftRequest">
        <h4>Shift Time</h4>
        <input type="text" name="sT" value="" placeholder="  Insert Shift Time(xx:00 to xx:00)"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />

        <h4>Shift Days</h4>
        <input type="text" name="sD" value="" placeholder="  Insert Shift Days"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />

        <h4>Region ID</h4>
        <input type="text" name="region" value="" placeholder="  Insert Region ID"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />

        <input type="submit" name="displayShift" style="width:150px; height:40px;solid #FBFBFB;"></p>
    </form>

    <hr />

    <h2>Find region with the highest drive rating</h2>
    <form method="GET" action="driverInfo.php"> <!--refresh page when submitted-->
        <input type="hidden" id="displayRegionRequest" name="displayRegionRequest">
        <input type="submit" name="displayRegion" style="width:150px; height:40px;solid #FBFBFB;"></p>
    </form>

    <hr />

    <?php
    //this tells the system that it's no longer just parsing html; it's now parsing PHP

    $success = True; //keep track of errors so it redirects the page only if there are no errors
    $db_conn = NULL; // edit the login credentials in connectToDB()
    $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

    function debugAlertMessage($message) {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }

    function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
        //echo "<br>running ".$cmdstr."<br>";
        global $db_conn, $success;

        $statement = OCIParse($db_conn, $cmdstr);
        //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
            echo htmlentities($e['message']);
            $success = False;
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        }

        return $statement;
    }

    function executeBoundSQL($cmdstr, $list) {
        /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
    In this case you don't need to create the statement several times. Bound variables cause a statement to only be
    parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
    See the sample code below for how this function is used */

        global $db_conn, $success;
        $statement = OCIParse($db_conn, $cmdstr);

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn);
            echo htmlentities($e['message']);
            $success = False;
        }

        foreach ($list as $tuple) {
            foreach ($tuple as $bind => $val) {
                //echo $val;
                //echo "<br>".$bind."<br>";
                OCIBindByName($statement, $bind, $val);
                unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                echo htmlentities($e['message']);
                echo "<br>";
                $success = False;
            }
        }
    }

    function printResult($result) { //prints results from a select statement
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
        }

        echo "</table>";
    }

    function connectToDB() {
        global $db_conn;

        // Your username is ora_(CWL_ID) and the password is a(student number). For example,
        // ora_platypus is the username and a12345678 is the password.
        $db_conn = OCILogon("ora_zhylaw18", "a29568581", "dbhost.students.cs.ubc.ca:1522/stu");

        if ($db_conn) {
            debugAlertMessage("Database is Connected");
            return true;
        } else {
            debugAlertMessage("Cannot connect to Database");
            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
            return false;
        }
    }

    function disconnectFromDB() {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }

    function insuredHandler() {
        global $db_conn;

        $result = executePlainSQL("SELECT d2.DriverID FROM drivers d2 WHERE NOT EXISTS ( d2.DriverId NOT IN (SELECT DriverID FROM drivers d natural right outer join covers)");

        printResult($result);
    }

    function shiftHandler() {
        global $db_conn;

        $shift_time = $_POST['sT'];
        $shift_days = $_POST['sD'];
        $region = $_POST['region'];

        $result = executePlainSQL("SELECT shiftTime, shiftDays, RegionID, Count(DriverID) FROM drivers WHERE shiftTime = '" . $shift_time . "', shiftDays = '" . $shift_days . "', RegionID = '" . $region . "' GROUP BY shiftTime, shiftDays, RegionID");

        printResult($result);
    }

    function regionHandler() {
        global $db_conn;

        $result = executePlainSQL("SELECT Temp.RegionID FROM(SELECT d.RegionID, AVG(d.driver) as average FROM drivers d GROUP BY d.RegionID) AS Temp WHERE Temp.average in (SELECT MAX(Temp.average) FROM Temp)");


        if (($row = oci_fetch_row($result)) != false) {
            echo "<br> The highest rated region has regionID: " . $row[0] . "<br>";
        }

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["RegionID"] . "</td><td>"; //or just use "echo $row[0]"
        }
    }


    // HANDLE ALL POST ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest() {
        if (connectToDB()) {
            if (array_key_exists('findUninsuredRequest', $_GET)) {
                insuredHandler();
            } else if (array_key_exists('displayShiftRequest', $_GET)) {
                shiftHandler();
            } else if (array_key_exists('displayRegionRequest', $_GET)) {
                regionHandler();
            }

            disconnectFromDB();
        }
    }


    if (isset($_GET['displayRegion']) || isset($_GET['displayShift']) || isset($_GET['findUninsured'])) {
        handleGETRequest();
    }
    ?>
</body>
</html>
