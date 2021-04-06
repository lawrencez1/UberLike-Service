<?php
    require_once "connect.php";
    $stmt = "SELECT * FROM MECHANICS";
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
    <title>Mechanics Management</title>
    <?php include "header.inc.php";?>
</head>
<body>
<div class="container">
    <h2> Mechanics Management <small class="pull-right"><a class='btn btn-default' href="Mechanics_Add.php">Add Mechanics</a></small></h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th> MechanicID</th>
                <th>Street Address</th>
                <th>Postal Code</th>
                <th>Name</th>



            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['MECHANICID'];?></td>
                    <td><?php echo $row['STREETADDRESS'];?></td>
                    <td><?php echo $row['POSTALCODE'];?></td>
                    <td><?php echo $row['NAME'];?></td>
                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Mechanics_Delete.php?ID=<?php echo $row['MECHANICID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
