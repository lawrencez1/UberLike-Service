<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Home</title>
    <?php include "header.inc.php";
    ?>
</head>
<body>
<?php include "nav.inc.php"; ?>
<div class="container">
<?php
include "Parsedown.php";
$contents = file_get_contents('README.md');
$Parsedown = new Parsedown();
echo $Parsedown->text($contents);
    ?>
</div>

</body>
