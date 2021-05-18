<?php
require '../../connect.php';
include("../classes/User.php");
include("../classes/Post.php");

$limit = 35;
$posts = new Post($connect, $_REQUEST['userLoggedIn']);
$posts->loadProfilePosts($_REQUEST, $limit);

