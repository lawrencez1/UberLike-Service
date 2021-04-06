<html>
<head>
    <title>Add Ride Order</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <html lang="en">
<body>
<?php include "nav.inc.php";?>
<div class="container">
    <h2> Insert Ride Order <small class="pull-right"><a class='btn btn-default' href="Order_Driver.php">Back</a></small></h2>
    <hr />
    <form method="POST" action="Order_Ride_Add.php"> <!--refresh page when submitted-->
        <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">

        <h4>RideID</h4>
        <input type="text" name="insRideID" value="" placeholder="  Insert RideID"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
        <h4>RideDate</h4>
        <input type="text" name="insRideDate" value="" placeholder="  Insert RideDate"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
        <h4>Distance</h4>
        <input type="text" name="insDistance" value="" placeholder="  Insert Distance"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
        <h4>DriverID</h4>
        <input type="text" name="insDriverID" value="" placeholder="  Insert DriverID"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
        <h4>RegionID</h4>
        <input type="text" name="insRegionID" value="" placeholder="  Insert RegionID"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
        <h4>CustomerID</h4>
        <input type="text" name="insCustomerID" value="" placeholder="  Insert CustomerID"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />


        <input type="submit" value="Insert" name="insertSubmit" style="width:150px; height:40px;solid #FBFBFB;"></p>
    </form>


    <?php
    //this tells the system that it's no longer just parsing html; it's now parsing PHP

    $success = True; //keep track of errors so it redirects the page only if there are no errors
    $db_conn = NULL;
    $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

    function debugAlertMessage($message) {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
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



    function handleInsertRequest() {
        global $db_conn;

        //Getting the values from user and insert data into the table
        $tuple = array (
            ":bind1" => $_POST['insRideID'],
            ":bind2" => $_POST['insRideDate'],
            ":bind3" => $_POST['insDistance'],
            ":bind4" => $_POST['insDriverID'],
            ":bind5" => $_POST['insRegionID'],
            ":bind6" => $_POST['insCustomerID']

        );

        $alltuples = array (
            $tuple
        );

        executeBoundSQL("insert into RIDES values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
        OCICommit($db_conn);
        header('Location: Order.php');

    }

    // HANDLE ALL POST ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        global $db_conn;
        if (connectToDB()) {
            if (array_key_exists('insertQueryRequest', $_POST)) {
                handleInsertRequest();
            }

            OCILogoff($db_conn);
        }
    }


    if (isset($_POST['insertSubmit'])) {
        handlePOSTRequest();
    }

    ?>
</body>
</html>


