<?php 
include("includes/form_handlers/header.php");

if(isset($_POST['post'])){
    $uploadOk = 1;
    $imageName = $_FILES['fileToUpload']['name'];
    $errorMessage = "";

    if($imageName != ""){
        $targetDir = "Assets/images/posts/";
        $imageName = $targetDir . uniqid() . basename($imageName);
        $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

        if($_FILES['fileToUpload']['size'] > 10000000){
            $errorMessage = "Sorry your file is too large";
            $uploadOk = 0;
        }

        if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg") {
            $errorMessage = "Sorry your file is too large";
            $uploadOk = 0;
        }
        if($uploadOk){
            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$imageName)){

            }
            else {
                $uploadOk = 0;
            }
        }

    }
    if($uploadOk){
        $post = new Post($connect,$userLoggedIn);
        $post->submitPost($_POST['post_text'], 'none', $imageName);
        header("Location: index.php");
    }
    else {
        echo "<div style ='text-align: center; color: black;' class = 'alert alert-danger' role = 'alert'>  
            $errorMessage
        </div>";
    }
    
}
?>
    
    <div class="user_details column">
        <a href="<?php echo 'profile.php?profile_username=' . $userLoggedIn ?>"><img src ="<?php echo $user['profile_pic']?>"></a>
    <div class = "user_details_left-right">
    <a href="<?php echo 'profile.php?profile_username=' . $userLoggedIn ?>">
    <?php
        echo $user['first_name'] . " " . $user['last_name'];
    ?>
    </a>
    <br>
    <?php
        echo "Recipes shared: " . $user["num_posts"] . "<br>";
        echo "Likes: " . $user['num_likes'];
    ?>
    </div>
</div>
    <div class = "main_column column">
        <form class = "post_form" action="index.php" method = "POST" enctype="multipart/form-data">
            <input type="file" name = "fileToUpload" id = "fileToUpload">
        <textarea name="post_text" id="post_text" placeholder = "Share a recipe!"></textarea>
        <input type="submit" name = "post" id = "post_button" value = "Share">
        <hr>

        </form>
        <?php
            $post = new Post($connect,$userLoggedIn);
            $post -> loadPostsFriends();            
        ?>
    </div>

    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>