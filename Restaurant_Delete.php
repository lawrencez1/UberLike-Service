<html>

<body>
<?php
$conn=OCILogon("ora_zhylaw18", "a29568581", "dbhost.students.cs.ubc.ca:1522/stu");
$ID = $_GET['ID'];
$stmt = "DELETE FROM PARTNERRESTAURANTS WHERE RESTAURANTID = '" . $ID . "'";
$stid = oci_parse($conn, $stmt);
oci_execute($stid);
oci_close($conn);
header('Location: Restaurant.php');
        
?>

</body>
</html>
