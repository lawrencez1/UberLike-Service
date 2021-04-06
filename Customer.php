<?php
require_once "connect.php";
$stmt = "SELECT * FROM CUSTOMERS";
$rows=array();
$stid = oci_parse($conn, $stmt);
oci_execute($stid);

while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    $rows[]=$row;

}


?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Customer Management</title>
    <?php include "header.inc.php";?>
</head>
<body>
<?php include "nav.inc.php";?>
<div class="container">
    <h2> Customer Management
        <small class="pull-right"><a class='btn btn-default' href="Customer_Add.php">Add Customer</a></small>
    </h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th> CustomerID</th>
                <th> First Name</th>
                <th> Last Name</th>
                <th> Email</th>
                <th> Phone</th>
                <th> CreditCard</th>
                <th> Street Address</th>
                <th> Postal Code</th>
                <th> Customer Type</th>
<th> RegionID</th>


            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['CUSTOMERID'];?></td>
                    <td><?php echo $row['FIRSTNAME'];?></td>
                    <td><?php echo $row['LASTNAME'];?></td>
                    <td><?php echo $row['EMAIL'];?></td>
                    <td><?php echo $row['PHONE'];?></td>
                    <td><?php echo $row['CREDITCARD'];?></td>
                    <td><?php echo $row['STREETADDRESS'];?></td>
                    <td><?php echo $row['POSTALCODE'];?></td>
                    <td><?php echo $row['CUSTOMERTYPE'];?></td>
<td><?php echo $row['REGIONID'];?></td>


                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Customer_Delete.php?ID=<?php echo $row['CUSTOMERID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
