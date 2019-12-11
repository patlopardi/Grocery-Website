<?php
require 'db.php';

class dbTest extends PHPUnit\Framework\TestCase
{
    function test_encryptPass(){
        $password = 'test';
        $encryptedPass = encryptPass($password);
        $this->assertEquals('dGVzdA==', $encryptedPass);
    }

    function test_decryptPass(){
        $encryptedPass = 'dGVzdA==';
        $password = decryptPass($encryptedPass);
        $this->assertEquals('test', $password);
    }

    function test_censorCard(){
        $cardNumber = '1111-1111-1111-1111';
        $censored = censorCard($cardNumber);
        $this->assertEquals($censored, '****-****-****-1111');
    }
    
    function test_printCart(){
        $testCart = array( 'keyOne' => 'valueOne', 'keyTwo' => 'valueTwo', 'keyThree' => 'valueThree', );
        $expected = 'Key: keyOne Value: valueOne<br>Key: keyTwo Value: valueTwo<br>Key: keyThree Value: valueThree<br>';
        $this->expectOutputString($expected);
        printCart($testCart);
    }

    function test_convertArr(){
        $arr = array('key1' => 'value1');
        $expectedArr = array('key1','value1');
        $convertedArr = convertArr($arr);
        $this->assertEquals($expectedArr, $convertedArr);
    }
    
    function test_addToCart(){
        $testItem = 'testItem';
        $testPrice = '1.23';
        $testCart = array('otherTestItem' => '5.55');
        $testCart = addToCart($testItem, $testPrice, $testCart);
        $expectedCart = array('otherTestItem' => '5.55', 'testItem' => '1.23');
        $this->assertEquals($expectedCart, $testCart);
    }

    function test_generateCheckout(){
        $expectedTotal = 5.55;
        $testItem = 'testItem';
        $testCart = array( $testItem => '5.55');
        $checkoutTotal = generateCheckout($testCart);
        $expectedPrint = '
            <div class="basket-product">
                <div class="item">
                    <div class="product-image">
                        <img src="assets/food/'.$testItem.'.png" alt="Placholder Image 2" class="product-frame">
                    </div>
                    <div class="product-details">
                        <h1><strong><span class="item-quantity">1</span> x ' .$testItem. '</strong></h1>
                    </div>
                </div>
                <div class="price">'.$testCart[$testItem].'</div>
                <div class="quantity">
                    <input name="'.$testItem.'"type="number" value="1" min="1" class="quantity-field">
                </div>
                <div class="subtotal">'.$testCart[$testItem].'</div>
                <div class="remove">
                    <button type="button">Remove</button>
                </div>
            </div>
            ';
        $this->expectOutputString($expectedPrint);
        $this->assertEquals($expectedTotal, $checkoutTotal);
    }
    
    function test_parseConfirmCheckout(){
        $cart = array( 'item1' => 1.11, 'item2' => 2.22, 'item3' => 3.33);
        $arr = array('item1' => 1, 'item2' => 2, 'item3' => 3);
        $testCart = parseConfirmCheckout($cart, $arr);
        $total = 'total';
        $price = 'price';
        $quantity = 'quantity';
        $name = 'name';
        $expectedCart = array('item1' => array($total => 1.11, $quantity => 1, $price => 1.11, $name => 'item1'), 'item2' => array($total => 4.44, $quantity => 2, $price => 2.22, $name => 'item2'), 'item3' => array($total => 9.99, $quantity => 3, $price => 3.33, $name => 'item3'),);
        $this->assertEquals($expectedCart, $testCart);
    }

    function test_generateConfirmCheckout(){
        $testItem = array('total' => 1.11, 'quantity' => 1, 'price' => 1.11, 'name' => 'item1');
        $testCart = array( 'item1' => $testItem);
        $testTotal = generateConfirmCheckout($testCart);
        $expectedTotal = 1.11;
        $expectedPrint = '
            <div class="basket-product">
                <div class="item">
                    <div class="product-image">
                        <img src="assets/food/'.$testItem["name"].'.png" alt="Placholder Image 2" class="product-frame">
                    </div>
                    <div class="product-details">
                        <h1><strong><span class="item-quantity">'.$testItem["quantity"].'</span> x ' .$testItem["name"]. '</strong></h1>
                    </div>
                </div>
                <div class="price">'.$testItem["price"].'</div>
                <div class="quantity">
                    <input disabled name="'.$testItem["name"].'"type="number" value="'.$testItem["quantity"].'" min="1" class="quantity-field">
                </div>
                <div class="subtotal">'.$testItem["total"].'</div>
                <div class="remove">
                </div>
            </div>
            ';
        $this->expectOutputString($expectedPrint);
        $this->assertEquals($expectedTotal, $testTotal);
    }
}
    ?>