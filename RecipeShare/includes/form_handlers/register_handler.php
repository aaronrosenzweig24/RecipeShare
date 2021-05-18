<?php
// declare variables 
$fname = "";
$lname = "";
$email = "";
$email2 = "";
$password = "";
$password2 = "";
$date = "";
$error_array = array();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['Register'])){
        // add values to each variable
        //for first name
        $fname = strip_tags($_POST['regFName']); // using strip tags to take away the html tags from the inputs security measure so people dont submit html tags in
        $fname = str_replace(' ','',$fname); // replaces any spaces found in first names with no spaces. helps to get rid of spaces after names
        $fname = ucfirst(strtolower($fname)); // converts everything to lowercase letters except for the first letter.
        $_SESSION['regFName'] = $fname; // stores the first name variable into the session
        // for last name
        $lname = strip_tags($_POST['regLName']);
        $lname = str_replace(' ','',$lname);
        $lname = ucfirst(strtolower($lname));
        $_SESSION['regLName'] = $lname;
        //for email 
        $email = strip_tags($_POST['regEmail']);
        $email = str_replace(' ','',$email);
        $email = ucfirst(strtolower($email));
        $_SESSION['regEmail'] = $email;
        // for confirm email
        $email2 = strip_tags($_POST['regEmail2']);
        $email2 = str_replace(' ','',$email2);
        $email2 = ucfirst(strtolower($email2));
        $_SESSION['regEmail2'] = $email2;
        // for password dont need to get rid of spaces or get rid of uppercases
        $password = strip_tags($_POST['regPassword']);
        $password2 = strip_tags($_POST['regPassword2']);

        $date = date("Y-m-d"); // gets the date when the user signs up 
        if($email == $email2){

            if(filter_var($email,FILTER_VALIDATE_EMAIL)){ // checks to see if everything is in the right email foramt
                $email = filter_var($email, FILTER_VALIDATE_EMAIL);

                // check if the email already exists
                $email_check = mysqli_query($connect,"SELECT email FROM amr235.RecipeUsers WHERE email = '$email'");
                

                //count num of rows
                $num_rows = mysqli_num_rows($email_check);

                if($num_rows > 0){ // if email already exists
                    array_push($error_array,"Email already in use<br>"); 
                }
            } else {
                array_push($error_array,'Invalid email format<br>');
            }
        }
        else {
            array_push($error_array,"Emails dont match<br>");
        }

        if(strlen($fname) > 25 || strlen($fname) < 2){ // if the name is more than 25 chars
            array_push($error_array,'Your first name must be between 2 and 25 characters<br>');
        }
        if(strlen($lname) > 30 || strlen($lname) < 2){ // if the name is more than 25 chars
            array_push($error_array,'Your last name must be between 2 and 30 characters<br>');
        }
        if($password != $password2){
            array_push($error_array,'Your passwords do not match<br>');
        }
        else {
            if(preg_match('/[^A-Za-z0-9]/', $password)){
                array_push($error_array,'Your password can only contain english characters or numbers<br>');
            }
        }
        if(strlen($password > 30) || strlen($password) < 5){
            array_push($error_array,'Password must be between 5 and 30 characters<br>');
        }

        if(empty($error_array)){
            $password = sha1($password); // encrypts password using sha1 encryption

            //Generate username which in this case will be first and lastname concated
            $username = strtolower($fname . "_". $lname);
            $check_username_query = mysqli_query($connect, "SELECT username FROM amr235.RecipeUsers WHERE username = '$username");

            $i = 0;
            $temp_username = $username; //Temporary username variable used to find unique username
 
            //If username already exists, add number to end and check again
            while(mysqli_num_rows($check_username_query) != 0){
                $temp_username = $username; //Reset temporary username back to original username
                $i++;
                $temp_username = $username . "_" . $i;
                $check_username_query = mysqli_query($connect, "SELECT username FROM amr235.RecipeUsers WHERE username='$temp_username'");
            }
            
            $username = $temp_username; //$temp_username will now contain the unique username

            //random picture assignment 
            $rand = rand(1,9); //choses a random number between 1 and 9
            switch($rand) {
                case 1:
                    $profile_pic = "Assets/images/profile_pics/defaults/1.png";
                    break;
                case 2:
                    $profile_pic = "Assets/images/profile_pics/defaults/2.png";
                    break;
                case 3:
                    $profile_pic = "Assets/images/profile_pics/defaults/3.png";
                    break;
                case 4:
                    $profile_pic = "Assets/images/profile_pics/defaults/4.png";
                    break;
                case 5:
                    $profile_pic = "Assets/images/profile_pics/defaults/5.png";
                    break;
                case 6:
                    $profile_pic = "Assets/images/profile_pics/defaults/6.png";
                    break;
                case 7:
                    $profile_pic = "Assets/images/profile_pics/defaults/7.png";
                    break;
                case 8:
                    $profile_pic = "Assets/images/profile_pics/defaults/8.png";
                    break;
                case 9:
                    $profile_pic = "Assets/images/profile_pics/defaults/9.png";
                    break;
                default:
                echo 'image not found';
            }
            $query = mysqli_query($connect, "INSERT INTO amr235.RecipeUsers VALUES ('0','$fname','$lname','$username', '$email','$password','$date','$profile_pic','0','0','no',',')");
            
            array_push($error_array, "<span style = 'color: #14C800;'>You're all set!");
            
            //clear the session variables
            $_SESSION['regFName'] = "";
            $_SESSION['regLName'] = "";
            $_SESSION['regEmail'] = "";
            $_SESSION['regEmail2'] = "";
            
        }
    }
}
?>
