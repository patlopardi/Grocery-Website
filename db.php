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
        $_SESSION["user"] = null;
        $_SESSION["email"] = null;
        $_SESSION["password"] = null;
        header("Location: index.php");
    }


    
 ?>