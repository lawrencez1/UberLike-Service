<?php
require_once "connect.php";
$stmt = "SELECT * FROM DELIVERIES";
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
    <title>Orders Management</title>
    <?php include "header.inc.php";?>
</head>
<body>
<?php include "nav.inc.php";?>
<div class="container">
    <h2> Food Orders Management <small class="pull-right"><a class='btn btn-default' href="Order_Add.php">Add Food Order</a></small></h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th>OrderID</th>
                <th>Delivery Date</th>
                <th>CustomerID</th>
                <th>DriverID</th>
                <th>RestaurantID</th>
                <th>RegionID</th>


            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['ORDERID'];?></td>
                    <td><?php echo $row['DELIVERYDATE'];?></td>
                    <td><?php echo $row['CUSTOMERID'];?></td>
                    <td><?php echo $row['DRIVERID'];?></td>
                    <td><?php echo $row['RESTAURANTID'];?></td>
                    <td><?php echo $row['REGIONID'];?></td>

                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Order_Delete.php?ID=<?php echo $row['ORDERID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
<?php include "Order_Driver.php";
?>

</body>
</html>
