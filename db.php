<?php
    
#Open up database
      function db_open($url, $db, $user, $pw)
      {
          $db_link = mysqli_connect($url, $user, $pw);
          mysqli_select_db($db_link, $db);
          mysqli_query($db_link, 'SET NAMES UTF8;');
          return $db_link;
      }
  #Close database
      function db_close($db_link)
      {
          mysqli_close($db_link);
      }
  #Failure get error numb
      function db_errno($db_link)
      {
         return mysqli_errno($db_link);
      }
  #If failed get error mes
     function db_error($db_link)
      {
          return mysqli_error($db_link);
      }
  #PErform something
     function db_query($db_link, $query)
      {
         $result = mysqli_query($db_link, $query);
          if ($result === FALSE) die(mysqli_error($db_link));
          return $result;
      }
  #Following a query, call this
      function db_fetch($query)
      {
          return mysqli_fetch_array($query);
      }
  #following  aquery call rows
      function db_num_rows($array)
      {
          return mysqli_num_rows($array);
      }
  #load csv file full of comma spe values
      function db_load_data($db_link, $file, $table)
      {
          $query = "LOAD DATA LOCAL INFILE '" . $file . "' INTO TABLE " . $table .
              " FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\\\' LINES TERMINATED BY '\\r\\n' IGNORE 1 LINES";
          $result = mysqli_query($db_link, $query);
          if ($result === FALSE) die(mysqli_error($db_link));
          return mysqli_affected_rows($db_link);
      }
 
     //escapes the string passed in to prevent SQL injections
     function db_escapestring($db_link, $db_string)
      {
        return mysqli_real_escape_string($db_link, $db_string);
      }

      //returns null, if user is not found, otherwise returns the user
      function validateUserInfo($conn, $query, $email, $psw){
        $result = db_query($conn, $query);
        $array = db_fetch($query);
        $match = null;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if((strcmp($row["Email"], $email) == 0) && (strcmp($email, "") != 0)){
                    if(strcmp($row["Password"], $psw) == 0){
                        $match = $row;
                    break;
                    }
                }
            }
        }
        return $match;
      }

      //checks the database for the User, returns 0 if there is no match, or 1 if there is
      function db_userCheck($conn, $query, $currUser){
        $result = db_query($conn, $query);
        $array = db_fetch($query);
        $match = 0;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if(strcmp($row["Email"], $currUser) == 0){
                    $match = 1;
                    break;
                }
            }
        }
        return $match;
    }

    //useful for registration. checks for valid email, password
    function db_serverSideValidation(
        $email,
        $psw,
        $psw_confirm,
        $conn,
        $query)
        {
        $errorMsg = "";


        if(!(isset ($email)) || (strcmp($email, "") == 0
                             || (!preg_match("/.+@.+\..+/", $email)))){
            $errorMsg .= "Valid Email is required!<br>";
        }
        if(isset($email)){
            $match = db_userCheck($conn, $query, $email);
            if($match == 1){
                $errorMsg .= "Email is taken!<br>";  
            }
        }
        if(!(isset ($psw)) || (strcmp($psw, "") == 0)){
            $errorMsg .= "Password is required!<br>";
        }
        if(strcmp($psw, $psw_confirm)!=0){
            $errorMsg .= "Passwords must match!<br>";
        }

        return $errorMsg;

    }

    function encryptPass($passOld){
        $passNew = base64_encode($passOld);
        return $passNew;
    }

    function decryptPass($passOld){
        $passNew = base64_decode($passOld);
        return $passNew;
    }
    function deleteCookie($cookieName){
        setcookie($cookieName, "", time() - (86400 * 7));
    }

    function logout(){
        $_SESSION = array();
        header("Location: index.php");
    }

    function isLoggedIn(){
        if(isset($_SESSION["user"])){
            if($_SESSION["user"]!=null){
                return true;
            }
        }
        return false;
    }
    //returns "billing.php" if user has not setup billing info yet. Otherwise returns "checkout.php"
    function checkForBilling(){
        if(!isset($_SESSION["user"]["Billing"]) || $_SESSION["user"]["Billing"] == null){
            $checkoutDirect = '"billing.php"';
        }
        else{
            $checkoutDirect = '"checkout.php"';
        }
        return $checkoutDirect;
    }

    function handleBillingInfo($parameters){
        $fullName = $parameters["fullname"];
        $email = $parameters["email"];
        $address = $parameters["address"];
        $city = $parameters["city"];
        $state = $parameters["state"];
        $zip = $parameters["zip"];
        $cardName = $parameters["cardname"];
        $cardNumber = $parameters["cardnumber"];
        $expMonth = $parameters["expmonth"];
        $expYear = $parameters["expyear"];
        $cvv = $parameters["cvv"];
        $sameAdr = $parameters["sameadr"];
        
        //TODO: validate billing info put in.

        //concatonates everything into 1 string for use in database.
        $billingEntry = $fullName . "|" . $email . "|" .
                        $address . "|" . $city . "|" .
                        $state . "|" . $zip . "|" .
                        $cardName . "|" . $cardNumber . "|" .
                        $expMonth . "|" . $expYear . "|" .
                        $cvv . "|" . $sameAdr;
   
        return $billingEntry;
        


    }
    /* returns array of billing info
     * [0] = fullName; [1] = email; [2] = address; [3] = city; [4] = state; [5] = zip;
     * [6] = cardName; [7] = cardNumber; [8] = expMonth; [9] = expYear; [10] = cvv;
     * [11] = sameAdr;
     */
    function parseBillingInfo($conn, $query, $currEmail){
        $result = db_query($conn, $query);
        $array = db_fetch($query);
        $match = null;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if(strcmp($row["Email"], $currEmail) == 0){
                    $billing = $row["Billing"];
                    $match = explode("|", $billing);
                    break;
                }
            }
        }
        return $match;
    }

    function censorCard($cardNumber){
        $retString = preg_replace("/([0-9]{4}).*([0-9]{4}).*([0-9]{4}).*([0-9]{4})/", "****-****-****-$4", $cardNumber);
        return $retString;
    }

    function printCart($cart){
        foreach($cart as $key => $value) {
            echo("Key: ". $key . " Value: ". $value ."<br>");
        }
    }

    //$array[0] = item name; $array[1] = item price
    function convertArr($arr){
        foreach($arr as $key => $value) {
            //echo("Key: ". $key . "Value: ". $value ."<br>");
            $array[0] = $key;
            $array[1] = $value;
        }
        return $array;
    }
    $weekFromToday =  date("d")+7;
    
    function addToCart($item, $price, $cart){
        foreach ($cart as $key => $value){
            if(strcmp($key, $item) == 0){
                return $cart;
            }
        }
        $cart[$item] = $price;
        return $cart;
    }

    //$key = itemName; $value = itemPrice;
    function generateCheckout($cart){
        $total = 0.00;  
        foreach($cart as $key => $value) {
            print'
            <div class="basket-product">
                <div class="item">
                    <div class="product-image">
                        <img src="assets/food/'.$key.'.png" alt="Placholder Image 2" class="product-frame">
                    </div>
                    <div class="product-details">
                        <h1><strong><span class="item-quantity">1</span> x ' .$key. '</strong></h1>
                    </div>
                </div>
                <div class="price">'.$value.'</div>
                <div class="quantity">
                    <input name="'.$key.'"type="number" value="1" min="1" class="quantity-field">
                </div>
                <div class="subtotal">'.$value.'</div>
                <div class="remove">
                    <button type="button">Remove</button>
                </div>
            </div>
            ';
            $total = $total + $value;        
        }
        return $total;
    }

    //takes cart in current state cart, array of Post variables,
    //parses them, sets discount, and delivery preference
    //returns finalCart with adjusted values
    function parseConfirmCheckout($cart, $arr){
        foreach($arr as $item => $quantity) {
            if(strcmp($item,"promo-code")==0){
                if(strcmp($quantity,"10off")==0 || strcmp($quantity,"10OFF")==0){
                    $_SESSION["discount"] = 10;
                }
            }
            else if(strcmp($item,"delivery-collection")==0){
                $_SESSION["delivery"] = $quantity;
            }
            else{
                $finalCart[$item]["total"] = $quantity * $cart[$item];
                $finalCart[$item]["quantity"] = $quantity;
                $finalCart[$item]["price"] = $cart[$item];
                $finalCart[$item]["name"] = $item; 
            }
        }
        return $finalCart;
    }

    //generates confirmCheckout screen
    function generateConfirmCheckout($cart){
        $total = 0.00;  
        foreach($cart as $item) {
            print'
            <div class="basket-product">
                <div class="item">
                    <div class="product-image">
                        <img src="assets/food/'.$item["name"].'.png" alt="Placholder Image 2" class="product-frame">
                    </div>
                    <div class="product-details">
                        <h1><strong><span class="item-quantity">'.$item["quantity"].'</span> x ' .$item["name"]. '</strong></h1>
                    </div>
                </div>
                <div class="price">'.$item["price"].'</div>
                <div class="quantity">
                    <input disabled name="'.$item["name"].'"type="number" value="'.$item["quantity"].'" min="1" class="quantity-field">
                </div>
                <div class="subtotal">'.$item["total"].'</div>
                <div class="remove">
                </div>
            </div>
            ';
            $total = $total + $item["total"];        
        }
        $total = sprintf('%0.2f', $total);
        return $total;
    }

    //checks if discount is active
    function checkDiscount($total){
        if(isset($_SESSION["discount"]) && $total >= 10){
            $total = $total - $_SESSION["discount"];
            echo'<div class="promo-title">Promotion</div>
                 <div class="promo-value final-value" id="basket-promo">10.00</div>';
        }
        $total = sprintf('%0.2f', $total);
        return $total;
    }

    //places order into Order Database
    function putOrderDB($email, $string, $address, $arrival){
        $url = 'easel2.fulgentcorp.com';
        $user= 'mof162';
        $pw = 'UlBUSHmv75iMJ69P0GvB';
        $db = 'mof162.bfOrders';
        $conn = db_open($url, $db, $user, $pw);
        $query = "SELECT * FROM mof162.bfOrders";
        $sql = "INSERT INTO ". $db . " (Email, GroceriesOrder, Address, Arrival)
            VALUES (  '". $email . "', '". $string . "', '". $address . "', '". $arrival . "')";
            if (db_query($conn, $sql)) {
                //echo "New record created successfully";
                $_SESSION["final_cart"] = array();
                header("Location: profile.php");
            } else {
                $errorMsg = "Error: " . $sql . "<br>" . db_error($conn);
            }
    }

    //returns array of orders from database connected to user's Email
    function orderHistory($email){
        $url = 'easel2.fulgentcorp.com';
        $user= 'mof162';
        $pw = 'UlBUSHmv75iMJ69P0GvB';
        $db = 'mof162.bfOrders';
        $conn = db_open($url, $db, $user, $pw);
        $query = "SELECT * FROM mof162.bfOrders";
        $result = db_query($conn, $query);
        $i = 0;
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if(strcmp($row["Email"], $email) == 0){
                    $orders[$i]=$row;
                    $i++;
                }
            }
        }
        return $orders;
    }

    //returns number of active orders and sets session active orders
    function activeOrders($orders){
        $count = 0;
        $date = date("m/d/y h:i:sA");
        foreach($orders as $order){
            $arrival = preg_replace("/[^0-9]/", "", $order["Arrival"]);
            $dateNum = preg_replace("/[^0-9]/", "", $date);
            if(($arrival - $dateNum) >= 0){
                $_SESSION["Arrival"][$count] = $order["Arrival"];
                $count++;
            }
        }
        return $count;
    }

    //parse Orders and print them in table
    function parseOrders($orders){
        foreach($orders as $order){
            $orderArr = explode("|", $order["GroceriesOrder"]);
            $arraySize = sizeof($orderArr);
            $date;
            $total;
            //print header for each table needed (each order)
            print'<div class="wrap-all-the-things">
            <div class="pro-container">
                <div class="limiter">
                    <div class="container-table100">
                        <div class="wrap-table100">
                            <div class="table">
    
                                <div class="row header">
                                    <div class="cell">
                                        Item Name
                                    </div>
                                    <div class="cell">
                                        Quantity
                                    </div>
                                    <div class="cell">
                                        Price
                                    </div>
                                    <div class="cell">
                                        Subtotal
                                    </div>
                                </div>
                                    ';
            for($i = 0; $i<$arraySize; $i++){
                if($i == 0){
                    $date = $orderArr[$i];
                }
                else if($i == $arraySize-1){
                    $total = $orderArr[$i];
                    print'<div class="row">
                            <div class="cell" data-title="Full Name">
                                <b>Total</b>
                            </div>
                            <div class="cell" data-title="Age">
                            </div>
                            <div class="cell" data-title="Job Title">
                            </div>
                            <div class="cell" data-title="Location">
                                <b>'.$total.'</b>
                            </div>
                        </div>';
                }
                else{
                    $quantity = $orderArr[$i];
                    $i++;
                    $name = $orderArr[$i];
                    $i++;                    
                    $price = $orderArr[$i];
                    $i++;                    
                    $subtot = $orderArr[$i];
                    //print row in table
                    print'<div class="row">
                            <div class="cell" data-title="Full Name">
                                '.$name.'
                            </div>
                            <div class="cell" data-title="Age">
                                '.$quantity.'
                            </div>
                            <div class="cell" data-title="Job Title">
                                '.$price.'
                            </div>
                            <div class="cell" data-title="Location">
                                '.$subtot.'
                            </div>
                        </div>
                            ';
                }
            }
            print'</div><br><p>Order Placed at:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp '.$date.'</p></div></div>';
        }
        print'                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }
 ?>