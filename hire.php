<html>
    <head>
        <title>CPSC 304 PHP/Oracle Demonstration</title>
    </head>

    <body>

        <hr />

        <h2>Fire an employee</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="Home.php"> <!--goes back to home when clicked-->
            <input type="hidden" id="hireDriver" name="hireDriver">
            Shift Days: <input type="text" name="sD"> <br /><br />
            Shift Time: <input type="text" name="sT"> <br /><br />
            First Name: <input type="text" name="fN"> <br /><br />
            Last Name: <input type="text" name="ln"> <br /><br />
            Region: <input type="text" name="rid"> <br /><br />
            Type: <select name="dType">
                    <option value="taxi">Taxi Driver</option>
                    <option value="food">Food Driver</option>
                    <option value="both">Both</option>
                </select>

            <input type="submit" value="Update" name="addSubmit"></p>
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

        function handleUpdateRequest() {
            global $db_conn;

            $tuple = array (
                ":bind1" => $_POST['sD'],
                ":bind2" => $_POST['sT']
                ":bind3" => $_POST['fN']
                ":bind4" => $_POST['lN']
                ":bind5" => $_POST['rid']
            );

            $selected = $_POST['Fruit'];
        echo 'You have chosen: ' . $selected;

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("  INSERT INTO Driver (shiftDays, shiftTime, firstName, lastName) VALUES (:bind1, :bind2, :bind3, :bind4, :bind5)"); //got rid of $allTuples because I believe we don't ant that bewcause we auto increment driver
            
            $selected = $_POST['dType'];
            
            if ($selected == "taxi") {
                executePlainSQL("  INSERT INTO TaxiDriver(Driver) VALUES (SELECT d1.DriverID FROM Driver d1, Driver d2 WHERE d1.DriverID >= d2.DriverID)");
            }
            

            if ($selected == "food") {
                executePlainSQL("  INSERT INTO FoodDriver(Driver) VALUES (SELECT d1.DriverID FROM Driver d1, Driver d2 WHERE d1.DriverID >= d2.DriverID)");
            }
            

            if ($selected == "both") {
                executePlainSQL("  INSERT INTO FoodDriver(Driver) VALUES (SELECT d1.DriverID FROM Driver d1, Driver d2 WHERE d1.DriverID >= d2.DriverID)");
                executePlainSQL("  INSERT INTO TaxiDriver(Driver) VALUES (SELECT d1.DriverID FROM Driver d1, Driver d2 WHERE d1.DriverID >= d2.DriverID)");
            }
            

            OCICommit($db_conn);
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('hireDriver', $_POST)) {
                    handleUpdateRequest();
                }

                disconnectFromDB();
            }
        }

        if (isset($_POST['addSubmit'])) {
            handlePOSTRequest();
        }
		?>
	</body>
</html>