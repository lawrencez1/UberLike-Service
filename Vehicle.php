<?php
    require_once "connect.php";
    $stmt = "SELECT * FROM VEHICLES";
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
    <title>Vehicle Management</title>
    <?php include "header.inc.php";?>
</head>
<body>
<div class="container">
    <h2> Vehicle Management <small class="pull-right"><a class='btn btn-default' href="Vehicle_Add.php">Add Vehicle</a></small></h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th> VehicleID</th>
                <th>LastServed</th>
                <th>VehicleType</th>
                <th>MechanicID</th>



            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['VEHICLEID'];?></td>
                    <td><?php echo $row['LASTSERVED'];?></td>
                    <td><?php echo $row['VEHICLETYPE'];?></td>
                    <td><?php echo $row['MECHANICID'];?></td>
                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Vehicle_Delete.php?ID=<?php echo $row['VEHICLEID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
