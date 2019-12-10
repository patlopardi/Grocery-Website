
<?php
    include_once("dbSetup.php");
    if(!isLoggedIn()){
        header("Location: index.php");
    }
    else{
        $validatedUser = validateUserInfo($conn, $query, $_SESSION["email"], $_SESSION["password"]);
        $_SESSION["user"] = $validatedUser;
        $checkoutDirect = checkForBilling();
        //$array[0] = item name; $array[1] = item price
        if(!empty($_REQUEST)){
            $array = convertArr($_REQUEST);
            $_SESSION["cart"] = addToCart($array[0], $array[1], $_SESSION["cart"]);
        }
    }

?>
<html>
<main>
    <link rel="stylesheet" type="text/css" href="css/galleries.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="css/hover.css" rel="stylesheet" media="all">
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
                <a href="profile.php" class="profile-logo-float"> <img src="assets/profile.png"> </a>
            </li>
            <li class="link">
                <a href=<?php echo($checkoutDirect)?>> <img class="cart-logo" src="assets/cart1.png"></a>
            </li>
        </ul>
    </nav>
    <script src="js/responsiveNav.js"></script>
    <div class="padding"></div>
    <div class="search-container">
        <img src="assets/search.png" alt="">
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for items..">
    </div>
    <ul id="myUL">

        <form action="" method="POST">
            <li class="gallery-link">
                <p>Broccoli</p>
                <img class="item-img" height="131" src="assets/food/veggies/broccoli.png" alt="">
                <div class="bottom-gallery-link">
                    <p class="price">$0.89</p>
                    <button class="form-btn" name="broccoli" value="0.89"> <img class="" src="assets/add-to-cart.png"
                            alt=""></button>
                </div>
            </li>
        </form>
        <form action="" method="POST">
            <li class="gallery-link">
                <p>Potato</p>
                <img style="width:210px"class="item-img" src="assets/food/veggies/potato.png" alt="">
                <div class="bottom-gallery-link">
                    <p class="price">$0.09</p>
                    <button class="form-btn" name="potato" value="0.09"> <img class="" src="assets/add-to-cart.png"
                            alt=""></button>
                </div>
            </li>
        </form>
        <form action="" method="POST">
            <li class="gallery-link">
                <p>Eggplant</p>
            
                <img style="width:130px;"class="item-img" src="assets/food/veggies/eggplant.png" alt="">
            
                <div class="bottom-gallery-link">
                    <p class="price">$0.79</p>
                    <button class="form-btn" name="eggplant" value="0.79"> <img class="" src="assets/add-to-cart.png"
                            alt=""></button>
                </div>
            </li>
        </form>
        <form action="" method="POST">
            <li class="gallery-link">
                <p>Carrot</p>
                <img style="width: 90px;"class="item-img" src="assets/food/veggies/carrot_real.png" alt="">
                <div class="bottom-gallery-link">
                    <p class="price">$0.19</p>
                    <button class="form-btn" name="carrot" value="0.19"> <img class="" src="assets/add-to-cart.png"
                            alt=""></button>
                </div>
            </li>
        </form>

    </ul>
    <script src="js/searchbar.js"></script>

    <body>

    </body>
</main>

</html>