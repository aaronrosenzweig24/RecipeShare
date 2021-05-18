<?php
ob_start(); // turns on output buffering 
session_start();
$timezone = date_default_timezone_set('America/New_York');
$host = "pacu.cs.pitt.edu";
$hostUsername = "amr235";
$hostPassword = "Student_4178358";
$connect = mysqli_connect($host, $hostUsername, $hostPassword);
if(mysqli_connect_errno()){
    echo "Failed to connect: " . mysqli_connect_errno();
}
?>