
<?php
    include_once("dbSetup.php");

    //not logged in
    if(!isset($_SESSION["user"]) || $_SESSION["user"] == null){
        header("Location: index.php");
    }

    //passing in billing info
    if(!empty($_REQUEST)){
        $billingEntry = handleBillingInfo($_REQUEST);
        $sql = ' UPDATE ' . $db . ' SET Billing="' . $billingEntry . '" WHERE email="' .
        $_SESSION["user"]["Email"].'"' ;
        if (db_query($conn, $sql)) {
            //echo "New record created successfully";
            
        }else {
            header("Location: billing.php");
        }
    }
    $billingArr = parseBillingInfo($conn, $query, $_SESSION["user"]["Email"]);
    if($billingArr == null){
        header("Location: billing.php");
    }
    $cardCensored = censorCard($billingArr[7]);
    $shipString = $billingArr[0]."<br>" . 
                  $billingArr[2]."<br>" .
                  $billingArr[3].", " . $billingArr[4] ." ". $billingArr[5]."<br><br>
                  Card: ". $cardCensored;



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>BirdFeeder Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="css/navbar.css">

</head>

<body>
    <!-- partial:index.partial.php -->
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Basket</title>
    </head>
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
                <a href="profile.php" class="profile-logo-float"> <img src="assets/profile.png"> </a>
            </li>
            <li class="link">
                <a href="checkout.php"> <img class="cart-logo" src="assets/cart1.png"></a>
            </li>
        </ul>
    </nav>
    <div class="padding-nav"></div>

    <body>
        <main>
           
            <div class="basket">
                    <form id="form1" action="confirm.php" method="POST">
                <div class="basket-module">
                    <label for="promo-code">Enter a promotional code</label>
                    <input id="promo-code" type="text" name="promo-code" maxlength="5" class="promo-code-field">
                    <button type="button" class="promo-code-cta">Apply</button>
                </div>
                <div class="basket-labels">
                    <ul>
                        <li class="item item-heading">Item</li>
                        <li class="price">Price</li>
                        <li class="quantity">Quantity</li>
                        <li class="subtotal">Subtotal</li>
                    </ul>
                </div>
                <?php $total=generateCheckout($_SESSION["cart"]); ?>
            </div>
            <aside>
                <div class="summary">
                    <div class="summary-total-items"><span class="total-items"></span> Items in your Bag</div>
                    <div class="summary-subtotal">
                        <div class="subtotal-title">Subtotal</div>
                        <div class="subtotal-value final-value" id="basket-subtotal"><?php echo($total);?></div>
                        <div class="summary-promo hide">
                            <div class="promo-title">Promotion</div>
                            <div class="promo-value final-value" id="basket-promo"></div>
                        </div>
                    </div>
                    <div class="summary-delivery">
                        <select name="delivery-collection" class="summary-delivery-selection">
              <option value="ASAP" selected="selected">Select Time for Delivery</option>
             <option value="ASAP">ASAP (30 minutes from now)</option>
             <option value="1">In 1 hour</option>
             <option value="2">In 2 hours</option>
             <option value="3">In 3 hours</option>
          </select>
          <br>Shipping to:<br> <?php echo($shipString);?>
                    </div>
                    <div class="summary-total">
                        <div class="total-title">Total</div>
                        <div name="total" class="total-value final-value" id="basket-total"><?php echo($total);?></div>
                    </div>
                    <div class="summary-checkout">
                        <button type="submit" form="form1" class="checkout-cta">Continue Checkout</button>
                    </div>
                </form>
                </div>
            
            </aside>
        
        </main>
    </body>

    </html>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
    <script src="js/checkout.js"></script>

</body>

</html>