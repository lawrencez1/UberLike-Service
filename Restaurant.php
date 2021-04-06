<?php
$conn=OCILogon("ora_zhylaw18", "a29568581", "dbhost.students.cs.ubc.ca:1522/stu");
$stmt = "SELECT * FROM PARTNERRESTAURANTS";
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
    <h2> Restaurant Management <small class="pull-right"><a class='btn btn-default' href="Restaurant_Add.php">Add Restaurant</a></small></h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th>RestaurantID</th>
                <th>Name</th>
                <th>Cuisine</th>
                <th>StreetAddress</th>
                <th>PostalCode</th>
                <th>Maps</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['RESTAURANTID'];?></td>
                    <td><?php echo $row['NAME'];?></td>
                    <td><?php echo $row['CUISINE'];?></td>
                    <td><?php echo $row['STREETADDRESS'];?></td>
                    <td><?php echo $row['POSTALCODE'];?></td>
<td>
    <a  href="https://www.google.com/maps/search/<?php echo $row['POSTALCODE'];?>" target='_blank'>Show</a>
</td>

                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Restaurant_Delete.php?ID=<?php echo $row['RESTAURANTID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
