<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Restaurant Management</title>
    <?php include "header.inc.php";?>
</head>

<body>
<?php include "nav.inc.php";?>

<div class="container">

    <h2>Find restaurants who have had over 10 orders from them</h2>
    <form method="GET" action="restaurantInfo.php"> <!--refresh page when submitted-->
        <input type="hidden" id="displayMoreThanTenRequest" name="displayMoreThanTenRequest">
        <input type="submit" name="displayMoreThanTen"></p>
    </form>

    <hr />

    <h2>Find the drivers who have made deliveries from a certain restaurant</h2>
    <form method="GET" action="restaurantInfo.php"> <!--refresh page when submitted-->
        <input type="hidden" id="displayDriversRequest" name="displayDriversRequest">
        Restaurant (id): <input type="text" name="rid"> <br /><br />
        <input type="submit" name="displayDrivers"></p>
    </form>

    <hr />

    <?php
    function moreThanHandler() {
    }

    function displayHandler() {
        $ID = $_POST['rid'];
        header('Location:Order_Driver.php?ID=2');

    }


    function handleGETRequest() {
        if (array_key_exists('displayMoreThanTenRequest', $_GET)) {
            moreThanHandler();
        } else if (array_key_exists('displayDriversRequest', $_GET)) {
            displayHandler();
        }
    }


    if (isset($_GET['displayDrivers']) || isset($_GET['displayMoreThanTen'])) {
        handleGETRequest();
    }
    ?>
</div>
</body>
</html>
