<?php
    include_once("dbSetup.php");
        //errorflag determines if errors will be shown
        if(empty($_REQUEST)){
            $_SESSION["errorFlag"] = 0;
        }
        //if user tries posting variables through URL, errorflag will be set
        else{
            $_SESSION["errorFlag"] = 1;
        }
        
        //store passed in variables to session. This allows user to stay logged in between pages
        $_SESSION["email"] = db_escapestring($conn, $_REQUEST['email']);
        $_SESSION["password"] = db_escapestring($conn, $_REQUEST['password']);
        $_SESSION["password-confirm"] = db_escapestring($conn, $_REQUEST['password-confirm']);
        $validate = db_serverSideValidation($_SESSION["email"], $_SESSION["password"], $_SESSION["password-confirm"], $conn, $query);

        //validate is empty string if everything goes right.
        if(strcmp($validate, "") == 0){
            $_SESSION["errorFlag"] = 1;
            $errorMsg = "Registered Successfully";
            $query = "INSERT INTO ". $db . " (Email, Password)
            VALUES (  '". $_SESSION['email'] . "', '" . $_SESSION['password'] ."')";
            if (db_query($conn, $query)) {
                //echo "New record created successfully";
                header("Location: index.php");
            } else {
                $errorMsg = "Error: " . $query . "<br>" . db_error($conn);
              }
        }
        else {
            $errorMsg = $validate;
        }

?>
<!DOCTYPE html>
<html>

<head>
    <title>BirdFeeder</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <!--===============================================================================================-->
</head>
<script src="js/functions.js"></script>
<body>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form p-l-55 p-r-55 p-t-178" method="POST";>
                    <span class="login100-form-title">
						Sign Up
					</span>
                        <?php
                            if($_SESSION["errorFlag"] == 1) {
                                print "<p style='color: red;'>$errorMsg</p>";
                            }         
                        ?>
                    <div class="wrap-input100 validate-input m-b-16" data-validate="Please enter your Email">
                        <input class="input100" type="" name="email" placeholder="Email">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input m-b-16" data-validate="Please enter password">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Please confirm password">
                        <input class="input100"type="password" name="password-confirm" placeholder="Re-type Password">
                        <span class="focus-input100"></span>
                    </div>





                    <div class="text-right p-t-13 p-b-23">

                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
							Sign Up
						</button>
                    </div>

                    <div class="flex-col-c p-t-170 p-b-40">
                        <span class="txt1 p-b-9">
							Don’t have an account?
						</span>

                        <a href="index.php" class="txt3">
							Cancel Sign-up
						</a>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/countdowntime/countdowntime.js"></script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>

</html>