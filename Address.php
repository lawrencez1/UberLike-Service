<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <?php
        require_once "Region.php";
?>


<title>Address Management</title>

</head>


<body>

<?php
    require_once "connect.php";
$stmt = "SELECT * FROM ADDRESSES";
$rows=array();
$stid = oci_parse($conn, $stmt);
oci_execute($stid);

while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
    $rows[]=$row;
}
?>

<div class="container">
    <h2> Address Management <small class="pull-right"><a class='btn btn-default' href="Address_Add.php">Add Address</a></small></h2>
    <hr/>
    <div class="rows">



        <table class="table table-striped">

            <thead>
            <tr>
                <th>StreetAddress</th>
                <th>PostalCode</th>
                <th>RegionID</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['STREETADDRESS'];?></td>
                    <td><?php echo $row['POSTALCODE'];?></td>
                    <td><?php echo $row['REGIONID'];?></td>
             <td>
                        <a onclick='return confirm("Wanna delete？")' href="Delete_Address.php?ID=<?php echo $row['POSTALCODE'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>

</div>
</body>
</html>
