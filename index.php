<?php
    include_once("dbSetup.php");
    if(isLoggedIn()){
        $validatedUser = validateUserInfo($conn, $query, $_SESSION["email"], $_SESSION["password"]);
        $_SESSION["user"] = $validatedUser;
        $personDirect = '"profile.php"';
        $checkoutDirect = checkForBilling();
        $bakeryDirect = '"bakery.php"';
        $fruitsDirect = '"fruit.php"';
        $veggiesDirect = '"veggies.php"';
        $deliDirect = '"deli.php"';
    }
    else{
        $personDirect = "'login.php'";
        $checkoutDirect = '"login.php"';
        $bakeryDirect = '"login.php"';
        $fruitsDirect = '"login.php"';
        $veggiesDirect = '"login.php"';
        $deliDirect = '"login.php"';
    }
?>

<!DOCTYPE html>
<!----------------------------PROJECT-------------------------------->
<html>

<head>
    <title>BirdFeeder</title>
</head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/navbar.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/home-title.css">
<link rel="stylesheet" type="text/css" href="css/catagory.css">
<link href="css/hover.css" rel="stylesheet" media="all">
<!--BEG: NAVBAR-->
<nav class="navbar">
    <div class="logo">
        <div class="logo-left">
            <a href="index.php" class="logo">
                <img id="header-img" src="assets/birdfeeder-logo.png" alt="freeCodeCamp logo">

            </a>

        </div>
        <div class="logo-burger">
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i> </a>
        </div>
    </div>


    <ul class="nav-links" id="linkChange">
        <li class="link">
            <a href=<?php echo($personDirect); ?> class="profile-logo-float"> <img src="assets/profile.png"> </a>
        </li>
        <li class="link">
            <a href=<?php echo($checkoutDirect); ?>> <img class="cart-logo" src="assets/cart1.png"></a>
        </li>
    </ul>
</nav>
<script src="js/responsiveNav.js"></script>

<section class="home-title">
    <div>Welcome to the BirdFeeder</div>
    <div class="lower-home-title"> Check out our selection </div>
</section>

<section class="catagories">
    <ul class="cata-list">
        <a href=<?php echo($bakeryDirect); ?>>
            <li class="cata-link">bakery<img src="assets/food/bread/pie.png" alt=""></li>
        </a>
        <a href=<?php echo($fruitsDirect); ?>>
            <li class="cata-link">fruits<img src="assets/food/fruit/apple1.png" alt=""></li>
        </a>
        <a href=<?php echo($veggiesDirect); ?>>
            <li class="cata-link">veggies<img src="assets/food/veggies/carrot.png" alt=""></li>
        </a>
        <a href=<?php echo($deliDirect); ?>>
            <li class="cata-link">meats<img src="assets/food/meat/tbone.png" alt=""></li>
        </a>
    </ul>
</section>
<div class="foot"></div>

</footer>

<body>

</body>
</main>

</html>
</html>