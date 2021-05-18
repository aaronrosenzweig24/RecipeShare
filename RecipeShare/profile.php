<?php 
include("includes/form_handlers/header.php");

$message_obj = new Message($connect, $userLoggedIn);

if(isset($_GET['profile_username'])){
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE username = '$username'");
    $user_array = mysqli_fetch_array($user_details_query);

    $num_friends = (substr_count($user_array['friend_array'], ",")) -1;

}   


if(isset($_POST['remove_friend'])){
    $user = new User($connect, $userLoggedIn);
    $user->removeFriend($username);

}
if(isset($_POST['add_friend'])){
    $user = new User($connect, $userLoggedIn);
    $user->sendRequest($username);
}
if(isset($_POST['respond_request'])){
    header("Location: requests.php");
}
if(isset($_POST['post_message'])) {
    if(isset($_POST['message_body'])) {
      $body = mysqli_real_escape_string($connect, $_POST['message_body']);
      $date = date("Y-m-d H:i:s");
      $message_obj->sendMessage($username, $body, $date);
    }
   
    $link = '#profileTabs a[href="#messages_div"]';
    echo "<script> 
            $(function() {
                $('" . $link ."').tab('show');
            });
          </script>";
   
   
  }
?>

<style>
    .wrapper{
        margin-left: 0px;
        padding-left: 0px;
        height: 400px;
    }
</style>
    <div class = "profile_left">
        <img src="<?php echo $user_array['profile_pic']; ?>" >

        <div class = "profile_info">
            <p><?php echo "Posts: " . $user_array['num_posts'];?></p>
            <p><?php echo "Likes: " . $user_array['num_likes'];?></p>
            <p><?php echo "Friends: " . $num_friends; ?></p>
        </div>
            <form action="profile.php?profile_username=<?php echo $username;?>" method = "POST">
            <?php
                    $profile_user_obj = new User($connect,$username);
                    if($profile_user_obj->isClosed()){
                        header("Location: user_closed.php");
                    }
                    
                    $logged_in_user_obj = new User($connect, $userLoggedIn); 

                    if($userLoggedIn != $username){

                        if($logged_in_user_obj->isFriend($username)){
                            echo '<input type = "submit" name = "remove_friend" class = "btn btn-danger" value = "Remove Friend"><br>';
                        }
                        else if($logged_in_user_obj->didReceiveRequest($username)){
                            echo '<input type = "submit" name = "respond_request" class = "btn btn-warning" value = "Respond to Request"><br>';
                        }
                        else if($logged_in_user_obj->didSendRequest($username)){
                            echo '<input type = "submit" name = "" class = "btn btn-primary" value = "Request Sent"><br>';
                        }
                        else{
                            echo '<input type = "submit" name = "add_friend" class = "btn btn-success" value = "Add Friend"><br>';
                        }
                    }
                    

            ?>

        </form>
        <input type="submit" class ="btn btn-primary" data-bs-toggle = "modal" data-bs-target = "#post_form" value = "Post Something">
        <?php
            if($userLoggedIn != $username){
                echo '<div class = "profile_info_bottom">';
                echo $logged_in_user_obj->getMutualFriends($username). " Mutual friends";
                echo '</div>';
            }
        ?>
    </div>
    
    
    <div class="profile_main_column column">
        <ul class="nav nav-tabs" roll = "tablist" id = "profileTabs">
            <li class="nav-item" roll = "presentation">
                <a class="nav-link active" id = "home-tab"  href="#RecipeFeed_div" aria-controls="RecipeFeed_div" type = "button" roll = "tab" data-bs-toggle="tab"  data-bs-target = "#RecipeFeed_div" aria-selected="true">RecipeFeed</a>
            </li>
            <li class="nav-item" roll = "presentation">
                <a class="nav-link" id = "message-tab" href="#messages_div" aria-controls="messages_div" type = "button" roll = "tab" data-bs-toggle="tab" data-bs-target = "#messages_div" aria-selected="false">Messages</a>
            </li>
        </ul>

        <div class = "tab-content">
                <div roll = "tabpanel" class = "tab-pane fade show active" id = "RecipeFeed_div" aria-labelledby="home-tab">
                    <div class = "posts_area"></div>
                </div>
        
            
                <div roll = "tabpanel" class = "tab-pane fade " id = "messages_div" aria-labelledby="message-tab">
                    <?php  
                        
                        echo "<h4>You and <a href='profile.php?profile_username=". $username. "'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";

                        echo "<div class='loaded_messages' id='scroll_messages'>";
                            echo $message_obj->getMessages($username);
                        echo "</div>";
                        
                        ?>
                        <div class="message_post">
                            <form action="" method="POST">
                                
                                    <textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
                                    <input type='submit' name='post_message' class='btn btn-success' id='message_submit' value='Send'>
                            </form>

                        </div>

                        <script>
                            let div = document.getElementById("scroll_messages");
                            div.scrollTop = div.scrollHeight;
                        </script>
                </div>
        </div>


	</div>


        <div class="modal fade" id="post_form" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Post Something</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>This post will appear on the user's profile page as well as on your feed for your friends to see</p>

                    <form action="" class = "profile_post" method = "POST">
                        <div class = "form-group">
                            <textarea name="post_body" class = "form-control"></textarea>
                            <input type="hidden" name = "user_from" value = "<?php echo $userLoggedIn; ?>">
                            <input type="hidden" name = "user_to" value = "<?php echo $username; ?>">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name = "post_button" id = "submit_profile_post">Post</button>
                </div>
                </div>
            </div>
        </div>
        <script>
        var userLoggedIn = '<?php echo $userLoggedIn; ?>';
        var profileUsername = '<?php echo $username; ?>';

        $(document).ready(function() {


        //Original ajax request for loading first posts 
        $.ajax({
        url: "includes/handlers/ajax_load_profile_posts.php",
        type: "POST",
        data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
        cache:false,

        success: function(data) {
            $('.posts_area').html(data);
        }
        });

        $(window).scroll(function() {
        var height = $('.posts_area').height(); //Div containing posts
        var scroll_top = $(this).scrollTop();
        var page = $('.posts_area').find('.nextPage').val();
        var noMorePosts = $('.posts_area').find('.noMorePosts').val();

        if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {

            var ajaxReq = $.ajax({
            url: "includes/handlers/ajax_load_profile_posts.php",
            type: "POST",
            data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
            cache:false,

            success: function(response) {
                console.log("SUCCESS");
            
                $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage
                $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage
                $('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage
        
                $(".posts_area").append(response);
            
                inProgress = false;
            
            }
                // error: function(XMLHttpRequest, textStatus, errorThrown) {
                // alert("Error: " + errorThrown);
                // }
            });

        } //End if 

        return false;

        }); //End (window).scroll(function())


    });

  </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>