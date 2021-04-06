<html>
<head>
    <title>Add Customer</title>

<html lang="en">
<body>
<?php include "nav.inc.php";?>
<div class="container">
    <h2> Insert Customer <small class="pull-right"><a class='btn btn-default' href="Customer.php">Back</a></small></h2>
<hr />
<form method="POST" action="Customer_Add.php"> <!--refresh page when submitted-->
<input type="hidden" id="insertQueryRequest" name="insertQueryRequest">

    <h4> CustomerID</h4>
    <input type="text" name="insCustomerID" value="" placeholder="  Insert DriverID"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
<h4> First Name</h4>
<input type="text" name="insfirstName" value="" placeholder="  Insert First Name Date"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br /><h4> Last Name</h4>
<input type="text" name="inslastName" value="" placeholder="  Insert Last Name"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />

<h4> Email</h4>
<input type="text" name="insEmail" value="" placeholder="  Insert Email"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br>

<h4>Phone</h4>
<input type="text" name="insPhone" value="" placeholder="  Insert Phone Number"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
<h4> Credit Card</h4>
<input type="text" name="insCreditCard" value="" placeholder="  Insert Credit Card Number"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />



<h4> Street Address</h4>
<input type="text" name="insStreetAddress" value="" placeholder="  Insert Street Address"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
<h4> Postal Code</h4>
<input type="text" name="insPostalCode" value="" placeholder="  Insert Postal Code "  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
<h4> Customer Type</h4>

<select name="name="insCustomerType"">
    <option value="taxi">Taxi Customer</option>
    <option value="food">Food Customer</option>
    <option value="both">Both</option>
</select>
<br /><br />

<h4> RegionID</h4>
<input type="text" name="insRegionID" value="" placeholder="  Insert RegionID (int))"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
<hr />

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
        ":bind1" => $_POST['insCustomerID'],
        ":bind2" => $_POST['insfirstName'],
        ":bind3" => $_POST['inslastName'],
        ":bind4" => $_POST['insEmail'],
                    ":bind5" => $_POST['insPhone'],
                    ":bind6" => $_POST['insCreditCard'],
                    ":bind7" => $_POST['insStreetAddress'],
                    ":bind8" => $_POST['insPostalCode'],
                    ":bind9" => $_POST['insCustomerType'],
                    ":bind10" => $_POST['insRegionID']


    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into CUSTOMERS values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8, :bind9, :bind10)", $alltuples);
    OCICommit($db_conn);
    header('Location: Customer.php');

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

