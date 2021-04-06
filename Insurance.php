<?php
    require_once "connect.php";
    $stmt = "SELECT * FROM INSURANCEPOLICIES";
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
    <title>Insurance Policies Management</title>
    <?php include "header.inc.php";?>
</head>
<body>
<div class="container">
    <h2> Insurance Policies Management <small class="pull-right"><a class='btn btn-default' href="Insurance_Add.php">Add Insurance Policy</a></small></h2>
    <hr/>
    <div class="rows">

        <table class="table table-striped">

            <thead>
            <tr>
                <th> PolicyID</th>
                <th>Kind</th>




            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>
                <tr>
                    <td><?php echo $row['POLICYID'];?></td>
                    <td><?php echo $row['KIND'];?></td>

                    <td>
                        <a onclick='return confirm("Wanna delete？")' href="Insurance_Delete.php?ID=<?php echo $row['POLICYID'];?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>
