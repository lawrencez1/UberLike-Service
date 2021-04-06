<html>

<body>
<?php
    require_once ('connect.php');
    $ID = $_GET['ID'];
$stmt = "DELETE FROM CUSTOMERS WHERE POLICYID = '" . $ID . "'";
$stid = oci_parse($conn, $stmt);
oci_execute($stid);
oci_close($conn);
header('Location: Customer.php');

?>

</body>
</html>
