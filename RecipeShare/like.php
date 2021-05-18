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
            font-family: Arial, Helvetica, sans-serif;
            background-color: white;
        }
        form {
        position: absolute;
        top: 0;
    }
    </style>
    <?php
    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
    }
    $get_likes = mysqli_query($connect, "SELECT likes, added_by FROM amr235.RecipePosts WHERE id = '$post_id'");
    $row = mysqli_fetch_array($get_likes);
    $total_likes = $row['likes'];
    $user_liked = $row['added_by'];

    $user_details_query = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE username = '$user_liked'");
    $row = mysqli_fetch_array($user_details_query);
    $user_likes = $row['num_likes'];

    //like button 
    if(isset($_POST['like_button'])) {
        $total_likes++;
        $query = mysqli_query($connect, "UPDATE amr235.RecipePosts SET likes = '$total_likes' WHERE id = '$post_id'");
        $total_user_likes++;
        $user_likes = mysqli_query($connect, "UPDATE amr235.RecipeUsers SET num_likes='$total_user_likes' WHERE username = '$user_liked'");
        $insert_user = mysqli_query($connect, "INSERT INTO amr235.RecipeShareLikes VALUES('0','$userLoggedIn','$post_id')");
        echo mysqli_error($connect);
        //for notifications
    }

    //to unlike
    if(isset($_POST['unlike_button'])) {
        $total_likes--;
        $query = mysqli_query($connect, "UPDATE amr235.RecipePosts SET likes = '$total_likes' WHERE id = '$post_id'");
        $total_user_likes--;
        $user_likes = mysqli_query($connect, "UPDATE amr235.RecipeUsers SET num_likes='$total_user_likes' WHERE username = '$user_liked'");
        $insert_user = mysqli_query($connect, "DELETE FROM amr235.RecipeShareLikes WHERE username = '$userLoggedIn' AND post_id = '$post_id'");
    }

    //check for previous likes
    $check_query = mysqli_query($connect, "SELECT * FROM amr235.RecipeShareLikes WHERE username = '$userLoggedIn' AND post_id = '$post_id'");
    $num_rows = mysqli_num_rows($check_query);

    if($num_rows > 0){
        echo '<form action = "like.php?post_id=' . $post_id . '" method = "POST">
                <input type = "submit" class = "comment_like" name = "unlike_button" value = "Unlike">
                <div class = "like_value">
                    ' . $total_likes . mysqli_error($connect).' Likes
                </div>
            </form>'
        ;
    }
    else {
        echo '<form action = "like.php?post_id=' . $post_id . '" method = "POST">
                <input type = "submit" class = "comment_like" name = "like_button" value = "Like">
                <div class = "like_value">
                    ' . $total_likes .' Likes
                </div>
            </form>'
        ;
    }

    ?>
</body>
</html>