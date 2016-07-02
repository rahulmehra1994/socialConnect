<?php
session_start();
include_once("db_conx.php");
// Files that inculde this file at the very top would NOT require 
// connection to database or session_start(), be careful.
// Initialize some vars
$user_ok = false;//for visitor (not the member) it will always be false
$log_id = "";
$log_username = "";
$log_password = "";
// User Verify function
function evalLoggedUser($conx,$id,$u,$p){
	$sql = "SELECT ip FROM users WHERE id='$id' AND username='$u' AND password='$p' LIMIT 1";
    $query = mysqli_query($conx, $sql);
    $numrows = mysqli_num_rows($query);
	if($numrows > 0){
		return true;
	}
}
//this code is run when session still exist
if(isset($_SESSION["userid"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])) {
	$log_id = preg_replace('#[^0-9]#', '', $_SESSION['userid']);
	$log_username = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
	$log_password = preg_replace('#[^a-z0-9]#i', '', $_SESSION['password']);
	// Verify the user
	$user_ok = evalLoggedUser($db_conx,$log_id,$log_username,$log_password);
} 
//this code is run when session is destroyed due to closing of the browser SO WE DEAL WITH THE COOKIE
//this code is for when the user comes after a week or two later
else if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
	$_SESSION['userid'] = preg_replace('#[^0-9]#', '', $_COOKIE['id']);
    $_SESSION['username'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['user']);
    $_SESSION['password'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['pass']);
	$log_id = $_SESSION['userid'];
	$log_username = $_SESSION['username'];
	$log_password = $_SESSION['password'];
	// Verify the user
	$user_ok = evalLoggedUser($db_conx,$log_id,$log_username,$log_password);
	if($user_ok == true){
		// Update their lastlogin datetime field
		$sql = "UPDATE users SET lastlogin=now() WHERE id='$log_id' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
	}
}
?>