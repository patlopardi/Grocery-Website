<?php
    include_once("dbSetup.php");
    if(!isLoggedIn() || empty($_SESSION["final_cart"])){
        header("Location: checkout.php");
    }
    else{
        date_default_timezone_set('America/Chicago'); 
        $date = date("m/d/y h:i:sA");
        $dt = new DateTime($date);
        $dt->format("m/d/y h:i:sA");
        if(strcmp($_SESSION["delivery"], "ASAP")==0){
            $dt->modify("+30 minutes");
            $eta = $dt->format("m/d/y h:i:sA"); 
        }
        else{
            $hours = "+". $_SESSION["delivery"] . " hours";
            $dt->modify($hours);
            $eta = $dt->format("m/d/y h:i:sA");
        }
        //echo("ETA: " . $eta);
        $ten_spaces = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
        $string = $date . "|";
        foreach($_SESSION["final_cart"] as $item){
            $string .= $item["quantity"] . "|" . $item["name"] ."|" .
                       $item["price"] . "|" . $item["total"] . "|";
        }
        $string.= $_SESSION["total"];
        putOrderDB($_SESSION["user"]["Email"], $string, $_SESSION["user"]["Address"], $eta);
    }
?>