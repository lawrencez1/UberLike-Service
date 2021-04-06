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
    <hr />
    <form method="POST" action="restaurantInfo.php"> <!--refresh page when submitted-->
        <input type="hidden" id="displayMoreThanTenRequest" name="displayMoreThanTenRequest">
        <input type="submit" value="submit" name="displayMoreThanTen" style="width:150px; height:40px;solid #FBFBFB;"></p>
    </form>

    <hr />

    <h2>Find the drivers who have made deliveries from a certain restaurant</h2>
    <form method="POST" action="restaurantInfo.php"> <!--refresh page when submitted-->
        <input type="hidden" id="displayDriversRequest" name="displayDriversRequest">
        <h4>Restaurant ID</h4>
        <input type="text" name="rid" value="" placeholder="  Insert Restaurant ID (int)"  maxlength="10" size="10" style="width:300px; margin:0px 0px 0px 12px;height:40px;border-radius:4px;border:1px solid #DBDBDB;"/><br /><br />
        <input type="submit" name="displayDrivers"></p>
    </form>

    <hr />

    <?php
    function moreThanHandler() {
    }

    function displayHandler() {
        $ID = $_POST['rid'];
        header('Location:Order_Driver.php?ID='.$ID);

    }


    function handleGETRequest() {
        if (array_key_exists('displayMoreThanTenRequest', $_POST)) {
            moreThanHandler();
        } else if (array_key_exists('displayDriversRequest', $_POST)) {
            displayHandler();
        }
    }


    if (isset($_POST['displayDrivers']) || isset($_POST['displayMoreThanTen'])) {
        handleGETRequest();
    }
    ?>
</div>
</body>
</html>
