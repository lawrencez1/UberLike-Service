<?php
    require_once "connect.php";
    $stmt = "SELECT * FROM REGIONS";
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
    <title>Region Management</title>
    <?php include "header.inc.php";?>
</head>
<body>
<?php include "nav.inc.php";?>
<div class="container">
    <h2> Region Management <small class="pull-right"><a class='btn btn-default' href="Region_Add.php">Add Region</a></small></h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th>RegionID</th>
                <th>Name</th>
                <th>Order</th>
                <th>FareMultiplier</th>
                <th>DeliveryFee</th>
                <th>City</th>
                <th>State</th>


            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['REGIONID'];?></td>
                    <td><?php echo $row['REGIONNAME'];?></td>
                     <td>
                         <a  href="Region_Delete.php?RegionID=<?php echo $row['REGIONID'];?>">Show</a>
                     </td>
                    <td><?php echo $row['FAREMULTIPLIER'];?></td>
                    <td><?php echo $row['DELIVERYFEE'];?></td>
                    <td><?php echo $row['CITY'];?></td>
                    <td><?php echo $row['STATE'];?></td>

                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Region_Delete.php?ID=<?php echo $row['REGIONID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
<?php include "Address.php";
    ?>

</body>
</html>
