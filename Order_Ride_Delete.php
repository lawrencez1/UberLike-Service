<html>

<body>
<?php
require_once ('connect.php');
$ID = $_GET['ID'];
$stmt = "DELETE FROM RIDES WHERE RIDEID = '" . $ID . "'";
$stid = oci_parse($conn, $stmt);
oci_execute($stid);
oci_close($conn);
header('Location: Order.php');

?>

</body>
</html>
