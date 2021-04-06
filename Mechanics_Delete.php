<html>

<body>
<?php
    require_once ('connect.php');
    $ID = $_GET['ID'];
$stmt = "DELETE FROM MECHANICS WHERE MECHANICID = '" . $ID . "'";
$stid = oci_parse($conn, $stmt);
oci_execute($stid);
oci_close($conn);
header('Location: Driver.php');

?>

</body>
</html>
