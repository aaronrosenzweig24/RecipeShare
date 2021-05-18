<?php
require 'connect.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE username = '$userLoggedIn'");
    
    $user = mysqli_fetch_array($user_details_query);
}
else {
    header("Location: register.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" type = "text/css" href = "Assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <title></title>
</head>
<body>
    <style>
        *{
            font-size: 12px;
            font-family:Arial, Helvetica, sans-serif;
            background-color: white !important;

        }
    </style>

<script>
    function toggle(){
        let element = document.getElementByID("comment_section");
        
        if(element.style.display == "block"){
            element.style.display == "none";
        }
        else{
            element.style.display = "block";
        }
    }
</script>
<?php

if(isset($_GET['post_id'])){
    $post_id = $_GET['post_id'];
}

    $user_query = mysqli_query($connect, "SELECT added_by, user_to FROM amr235.RecipePosts WHERE id ='$post_id'");
    $row = mysqli_fetch_array($user_query);

    $posted_to = $row['added_by'];
    if(isset($_POST['postComment' . $post_id])){
        $post_body = $_POST['post_body'];
        $post_body = mysqli_escape_string($connect,$post_body);
        $date_time_now = date("Y-m-d H:i:s");
        $insert_post = mysqli_query($connect, "INSERT INTO amr235.comments VALUES ('0','$post_body', '$userLoggedIn', '$posted_to' , '$date_time_now', 'no', '$post_id')");
        echo "<p>Comment Posted! </p>" .mysqli_error($connect);
    }
?>
    <form action="comment_frame.php/?post_id=<?php echo $post_id;?>" id = "comment_form" name = "postComment<?php echo $post_id; ?>" method="POST">
        <textarea name="post_body"></textarea>
        <input type="submit" name ="postComment<?php echo $post_id; ?>" value = "Submit Comment">
    </form>

    <?php
    $get_comments = mysqli_query($connect, "SELECT * FROM amr235.comments WHERE post_id = '$post_id' ORDER BY id ASC");
    $count = mysqli_num_rows($get_comments);
        if($count != 0){
            while($comment = mysqli_fetch_array($get_comments)){

                $comment_body = $comment['post_body'];
                $posted_to = $comment['posted_to'];
                $posted_by = $comment['posted_by'];
                $date_added = $comment['date_added'];
                $remove = $comment['removed'];


                 //TimeFrame
                 $date_time_now = date('Y-m-d H:i:s');
                 $start_date = new DateTime($date_added); // time of post
                 $end_date = new DateTime($date_time_now); // current time
                 $interval = $start_date->diff($end_date); // difference between the two dates
                 if($interval -> y >= 1){
                     if($interval == 1){
                         $time_message = $interval->y . " year ago"; // produce 1 year ago
                     } else {
                         $time_message = $interval->y . " years ago";
                     }
                 }
                    else if ($interval -> m >= 1){
                        if($interval->d == 0){
                            $days = " ago";
                        }
                        else if($interval->d == 1){
                            $days = $interval->d . " day ago";
                        }
                        else{
                            $days = $interval->d . " days ago";
                        }
    
                        if($interval->m ==1){
                            $time_message = $interval->m . " month" . $days;
                        }
                        else {
                            $time_message = $interval->m . " months" . $days;
                        }
                    }
                    else if($interval ->d >= 1){
                        if($interval->d == 1){
                            $time_message = "Yesterday";
                        }
                        else{
                            $time_message = $interval->d . " days ago";
                        }
                    }
                    else if($interval->h >= 1){
                        if($interval->h == 1){
                            $time_message = $interval->h . " hour ago";
                        }
                        else{
                            $time_message = $interval->h . " hours ago";
                        }
                    }
                    else if($interval->i >= 1){
                        if($interval->i == 1){
                            $time_message = $interval->i . " minute ago";
                        }
                        else{
                            $time_message = $interval->i . " minutes ago";
                        }
                    }
                    else{
                        if($interval->s < 30){
                            $time_message = "Just Now";
                        }
                        else{
                            $time_message = $interval->s . " seconds ago";
                        }
                    }

                    $user_obj = new User($connect, $posted_by);
                    ?>
                    <div class = "comment_section">
                    <a href="profile.php?<?php echo $posted_by;?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic();?>" title = "<?php $posted_by;?>" style = "float:left;" height = "30"></a>
                    <a href="profile.php?<?php echo $posted_by;?>" target="_parent"><b><?php echo $user_obj->getFirstAndLastName();?></b></a>
                    &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message . "<br>" . $comment_body;?>
                    <hr>
                </div>
                <?php
                }
        }
        else{
            echo "<center><br><br>No Comments to load</center>";
        }
    ?>
        
</body>
</html>
