<?php
    // Start the session
    include_once("db.php");
        session_start();
         $url = 'easel2.fulgentcorp.com';
         $user= 'mof162';
         $pw = 'UlBUSHmv75iMJ69P0GvB';
         $db = 'mof162.bfUsers';
         $conn = db_open($url, $db, $user, $pw);
         $query = "SELECT * FROM mof162.bfUsers";
         //$errorMsg = "";                    
?>