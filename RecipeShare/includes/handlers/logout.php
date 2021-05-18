<?php
session_start();
session_destroy();
header("Location: ../../register.php");
?>

// delete_post.php
<?php
require '../../connect.php';

    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];

    }
    if(isset($_POST['result'])){
        if($_POST['result'] == 'true'){
            $query = mysqli_query($connect, "UPDATE amr235.RecipePosts SET deleted = 'yes' WHERE id = '$post_id'");
        }
    }
?>