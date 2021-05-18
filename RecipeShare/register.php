<?php
require 'connect.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register for Recipe Share</title>
    <link rel = "stylesheet" type = "text/css" href = "Assets/css/registerStyle.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="shortcut icon" type="image/jpg" href="Assets/images/salt.png"/>
</head>
<body>  
   <?php
    if(isset($_POST['Register'])){
        echo '
        <script>
        $(document).ready(function() {
            $("#first").hide();
            $("#second").show();
        });
        </script>
        ';
    }
   ?>
    <div class = "wrapper">
        <div class = "loginBox">
        <div class="loginHeader">
            <h1>Recipe Share</h1>
            <h2>Log in or sign up below!</h2>
        </div>
            <div id = "first">
                <form action="register.php" method = "POST">
                    <input type="email" name = 'logEmail' placeholder = 'Email Address' value = "<?php 
                    if(isset($_SESSION['logEmail'])){
                        echo $_SESSION['logEmail'];
                    }
                    ?>" reguired>
                    <br>
                    <input type="password" name = 'logPassword' placeholder = 'Password'>
                    <br>
                    <input type="submit" name = 'logButton' value = 'Login'>
                    <br>
                    <a href="#" id ="signup" class = "signup" onclick="showReg();">Need to sign up? Register here!</a><br>
                    <?php if(in_array("Email or Password was incorrect<br>",$error_array)){ echo "Email or Password was incorrect<br>";} ?>
                </form>
            </div>
            <div id="second">
                <form action="#" method = "POST">
                    <input type="text" name = "regFName" placeholder="First name" value = "<?php 
                    if(isset($_SESSION['regFName'])){
                        echo $_SESSION['regFName'];
                    }
                    ?>" required> 
                    <br>
                    <?php if(in_array('Your first name must be between 2 and 25 characters<br>',$error_array)){ echo 'Your first name must be between 2 and 25 characters<br>';} ?>
                    <input type="text" name = "regLName" placeholder="Last name" value = "<?php 
                    if(isset($_SESSION['regLName'])){
                        echo $_SESSION['regLName'];
                    } ?>" required>
                    <br>
                    <?php if(in_array('Your last name must be between 2 and 30 characters<br>',$error_array)){ echo 'Your last name must be between 2 and 30 characters<br>';} ?>
                    <input type="email" name = "regEmail" placeholder="Email" value = "<?php 
                    if(isset($_SESSION['regEmail'])){
                        echo $_SESSION['regEmail'];
                    }?>" required>
                    <br>
                    <?php if(in_array("Email already in use<br>",$error_array)){ echo "Email already in use<br>";}
                        else if(in_array("Emails dont match<br>",$error_array)){ echo "Emails dont match<br>";} 
                        else if(in_array('Invalid email format<br>',$error_array)){ echo 'Invalid email format<br>';} ?>
                    
                    <input type="email" name = "regEmail2" placeholder="Confirm Email" value = "<?php 
                    if(isset($_SESSION['regEmail2'])){
                        echo $_SESSION['regEmail2'];
                    }?>" required>
                    <br>
                    <input type="password" name = "regPassword" placeholder="Password" required><br>
                    <?php if(in_array('Your passwords do not match<br>',$error_array)){ echo 'Your passwords do not match<br>';} 
                        else if(in_array('Your password can only contain english characters or numbers<br>',$error_array)){ echo 'Your password can only contain english characters or numbers<br>';} 
                        else if(in_array('Password must be between 5 and 30 characters<br>',$error_array)){ echo 'Password must be between 5 and 30 characters<br>';} ?>
                    
                    <input type="password" name = "regPassword2" placeholder="Confirm Password" required>
                    <br>
                    <input type="submit" name = "Register" value = "Register">
                    <br>
                    <?php if(in_array( "<span style = 'color: #14C800;'>You're all set!",$error_array)){ echo  "<span style = 'color: #14C800;'>You're all set!";} ?>
                    <a href="#" id ="signin" class = "signin" onclick="showLog();">Already have an account? Sign in here</a>
                </form>
            </div>
        </div>
    </div>
    <script src="login.js"></script>
</body>
</html>
