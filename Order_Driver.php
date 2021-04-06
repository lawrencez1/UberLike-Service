<?php
require_once "connect.php";
$ID = $_GET['ID'];
$stmt = "SELECT DRIVERS.DRIVERID,DRIVERS.FIRSTNAME,DRIVERS.LASTNAME,DELIVERIES.ORDERID,DELIVERIES.DELIVERYDATE,DELIVERIES.RESTAURANTID,DELIVERIES.REGIONID from DRIVERS,DELIVERIES WHERE RESTAURANTID= '" . $ID . "' AND DRIVERS.DRIVERID=DELIVERIES.DRIVERID";
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
    <title>1</title>
    <?php include "header.inc.php";?>
</head>
<body>
<?php include "nav.inc.php";?>
<div class="container">
    <h2> List <small class="pull-right"><a class='btn btn-default' href="restaurantInfo.php">Back</a></small></h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th>DriverID</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>OrderID</th>
                <th>DeliveryDate</th>
                <th>RestaurantID</th>
                <th>RegionID</th>



            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['DRIVERID'];?></td>
                    <td><?php echo $row['FIRSTNAME'];?></td>
                    <td><?php echo $row['LASTNAME'];?></td>
                    <td><?php echo $row['ORDERID'];?></td>
                    <td><?php echo $row['DELIVERYDATE'];?></td>
                    <td><?php echo $row['RESTAURANTID'];?></td>
                    <td><?php echo $row['REGIONID'];?></td>

                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
