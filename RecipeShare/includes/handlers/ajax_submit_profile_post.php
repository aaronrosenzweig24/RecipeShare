<?php
require '../../connect.php';
include("../classes/User.php");
include("../classes/Post.php");

if(isset($_POST['post_body'])) {
    $post = new Post($connect,$_POST['user_from']);
    $post->submitPost($_POST['post_bodu'], $_POST['user_to'],'');
}

?>

// ajax_search.php
<?php
include("../../connect.php");
include("../../includes/classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

//If query contains an underscore, assume user is searching for usernames
if(strpos($query, '_') !== false) {
	$usersReturnedQuery = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
}
//If there are two words, assume they are first and last names respectively
else if(count($names) == 2){
	$usersReturnedQuery = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
}
//If query has one word only, search first names or last names 
else {
	$usersReturnedQuery = mysqli_query($connect, "SELECT * FROM amr235.RecipeUsers WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
}

if($query != ""){

	while($row = mysqli_fetch_array($usersReturnedQuery)) {
		$user = new User($connect, $userLoggedIn);

		if($row['username'] != $userLoggedIn){
			$mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
        }
		else {
			$mutual_friends == "";
        }

		echo "<div class='resultDisplay'>
				<a href='" . $row['username'] . "' style='color: #1485BD'>
					<div class='liveSearchProfilePic'>
						<img src='" . $row['profile_pic'] ."'>
					</div>

					<div class='liveSearchText'>
						" . $row['first_name'] . " " . $row['last_name'] . "
						<p>" . $row['username'] ."</p>
						<p id='grey'>" . $mutual_friends ."</p>
					</div>
				</a>
				</div>";

	}

}

?>