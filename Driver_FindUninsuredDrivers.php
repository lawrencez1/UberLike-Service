<?php
require_once "connect.php";
$stmt = "SELECT DriverID FROM Driver WHERE DriverId NOT IN (SELECT DriverID FROM Driver d natural right outer join covers)";
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
    <title>Driver Management</title>
    <?php include "header.inc.php";?>
</head>
<body>
<?php include "nav.inc.php";?>
<div class="container">
    <h2> Uninsured Drivers
      <small class="pull-right"><a class='btn btn-default' href="Driver.php">Back</a></small>
    </h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th>DriverID</th>
                <th>firstName</th>
                <th>lastName</th>
                <th>Rating</th>
                <th>shiftTime</th>
                <th>shiftDays</th>
                <th>DriverType</th>
                <th>VehicleID</th>
                <th>PolicyID</th>
                <th>RegionID</th>


            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['DRIVERID'];?></td>
                    <td><?php echo $row['FIRSTNAME'];?></td>
                    <td><?php echo $row['LASTNAME'];?></td>
                    <td><?php echo $row['RATING'];?></td>
                    <td><?php echo $row['SHIFTTIME'];?></td>
                    <td><?php echo $row['SHIFTDAYS'];?></td>
                    <td><?php echo $row['DRIVERTYPE'];?></td>
                    <td><?php echo $row['VEHICLEID'];?></td>
                    <td><?php echo $row['POLICYID'];?></td>
                    <td><?php echo $row['REGIONID'];?></td>


                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Driver_Delete.php?ID=<?php echo $row['DRIVERID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
<?php include "DriverInfo.php";
?>
</body>
</html>
