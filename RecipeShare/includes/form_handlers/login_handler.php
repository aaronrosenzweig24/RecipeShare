<?php

if(isset($_POST['logButton'])){

    $email = filter_var($_POST['logEmail'],FILTER_SANITIZE_EMAIL); // makes sure that the email is in the right format

    $_SESSION['logEmail'] = $email; // keeps the email inputted on screen
    $password = sha1($_POST['logPassword']); // get password

    $checkDB_query = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE email = '$email' AND password = '$password'");
    $checkLog_Query = mysqli_num_rows($checkDB_query);

    if($checkLog_Query == 1){
        $row = mysqli_fetch_array($checkDB_query);
        $username = $row['username'];

        $user_closed_query = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE email = '$email' AND user_closed = 'yes'"); // checks to see if an accoutn is closed 
        if(mysqli_num_rows($user_closed_query) == 1) {
            $reopen_account = mysqli_query($connect, "UPDATE amr235.RecipeUsers SET user_closed = 'no' WHERE email = '$email'");
        }
        
        $_SESSION['username'] = $username;
        header("Location: index.php"); // when they log in it will redirect to index.php
        exit;
    }
    else{
        array_push($error_array,"Email or Password was incorrect<br>");
    }
}

?>