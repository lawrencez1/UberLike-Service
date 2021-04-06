<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.  
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values
 
  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the 
  OCILogon below to be your ORACLE username and password -->

  <html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Driver Management</title>
    <?php include "header.inc.php";?>
</head>

<body>
<?php include "nav.inc.php";?>
<div class="container">

<h2>Hire an employee</h2>
        <p>Driver ID will auto increment.</p>

        <form method="POST" action="manageDrivers.php"> <!--goes back to home when clicked-->
            <input type="hidden" id="hireDriver" name="hireDriver">
            Employee ID: <input type="text" name="eId"> <br /><br />
            Shift Days: <input type="text" name="sD"> <br /><br />
            Shift Time: <input type="text" name="sT"> <br /><br />
            First Name: <input type="text" name="fN"> <br /><br />
            Last Name: <input type="text" name="lN"> <br /><br />
            Region: <input type="text" name="rId"> <br /><br />
            Type: <select name="dType">
                    <option value="taxi">Taxi Driver</option>
                    <option value="food">Food Driver</option>
                    <option value="both">Both</option>
                </select>

            <input type="submit" value="Update" name="addSubmit"></p>
        </form>

        <hr />

        <h2>Fire an employee</h2>

        <form method="POST" action="manageDrivers.php"> <!--goes back to home when clicked-->
            <input type="hidden" id="fireDriver" name="fireDriver">
            employeeID: <input type="text" name="oldName"> <br /><br />

            <input type="submit" value="Insert" name="deleteSubmit"></p>
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
            echo "<br>Retrieved data from table demoTable:<br>";
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

        function fireHandler() {
            global $db_conn;

            $old_name = $_POST['oldName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL(" DELETE FROM drivers WHERE DriverID ='" . $old_name . "'");
           
            OCICommit($db_conn);
        }

        function hireRequest() {
            global $db_conn;

            $tuple = array (
                ":bind1" => $_POST['eId'],
                ":bind2" => $_POST['sD'],
                ":bind3" => $_POST['sT'],
                ":bind4" => $_POST['fN'],
                ":bind5" => $_POST['lN'],
                ":bind6" => $_POST['dType'],
                ":bind7" => $_POST['rId']
            );

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("  INSERT INTO drivers (DriverID, shiftDays, shiftTime, firstName, lastName, DriverType, RegionID) VALUES (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)"); //got rid of $allTuples because I believe we don't ant that bewcause we auto increment driver
            
            $selected = $_POST['dType'];
            
            if ($selected == "taxi") {
                executePlainSQL("  INSERT INTO taxi(DriverID) VALUES (:bind6)");
            }
            

            if ($selected == "food") {
                executePlainSQL("  INSERT INTO food(DriverID) VALUES (:bind6)");
            }
            

            if ($selected == "both") {
                executePlainSQL("  INSERT INTO food(DriverID) VALUES (:bind6)");
                executePlainSQL("  INSERT INTO taxi(DriverID) VALUES (:bind6)");
            }
            

            OCICommit($db_conn);
        }
        

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('fireDriver', $_POST)) {
                    fireHandler();
                } else if (array_key_exists('hireDriver', $_POST)) {
                    hireRequest();
                }

                disconnectFromDB();
            }
        }


		if (isset($_POST['reset']) || isset($_POST['deleteSubmit']) || isset($_POST['addSubmit'])) {
            handlePOSTRequest();
        }
		?>
</div>
	</body>
</html>
