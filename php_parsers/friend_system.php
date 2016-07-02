<?php
include_once("../php_includes/check_login_status.php");
if($user_ok != true || $log_username == "") {
	exit();
}
?><?php
if (isset($_POST['type']) && isset($_POST['user'])){
	$user = preg_replace('#[^a-z0-9]#i', '', $_POST['user']);
	$sql = "SELECT COUNT(id) FROM users WHERE username='$user' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$exist_count = mysqli_fetch_row($query);
	if($exist_count[0] < 1){
		mysqli_close($db_conx);
		echo "$user does not exist.";
		exit();
	}
	if($_POST['type'] == "friend"){
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND accepted='1' OR user2='$user' AND accepted='1'";
		$query = mysqli_query($db_conx, $sql);
		$friend_count = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$user' AND blockee='$log_username' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$blockcount1 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM blockedusers WHERE blocker='$log_username' AND blockee='$user' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$blockcount2 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count1 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count2 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='0' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count3 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='0' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count4 = mysqli_fetch_row($query);
	    if($friend_count[0] > 99){
            mysqli_close($db_conx);
	        echo "$user currently has the maximum number of friends, and cannot accept more.";
	        exit();
        } else if($blockcount1[0] > 0){
            mysqli_close($db_conx);
	        echo "$user has you blocked, we cannot proceed.";
	        exit();
        } else if($blockcount2[0] > 0){
            mysqli_close($db_conx);
	        echo "You must first unblock $user in order to friend with them.";
	        exit();
        } else if ($row_count1[0] > 0 || $row_count2[0] > 0) {
		    mysqli_close($db_conx);
	        echo "You are already friends with $user.";
	        exit();
	    } else if ($row_count3[0] > 0) {
		    mysqli_close($db_conx);
	        echo "You have a pending friend request already sent to $user.";
	        exit();
	    } else if ($row_count4[0] > 0) {
		    mysqli_close($db_conx);
	        echo "$user has requested to friend with you first. Check your friend requests.";
	        exit();
	    } else {
	        $sql = "INSERT INTO friends(user1, user2, datemade) VALUES('$log_username','$user',now())";
		    $query = mysqli_query($db_conx, $sql);
			mysqli_close($db_conx);
	        echo "friend_request_sent";
	        exit();
		}
	} else if($_POST['type'] == "unfriend"){
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count1 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count2 = mysqli_fetch_row($query);
	    if ($row_count1[0] > 0) {
	        $sql = "DELETE FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			mysqli_close($db_conx);
	        echo "unfriend_ok";
	        exit();
	    } else if ($row_count2[0] > 0) {
			$sql = "DELETE FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			mysqli_close($db_conx);
	        echo "unfriend_ok";
	        exit();
	    } else {
			mysqli_close($db_conx);
	        echo "No friendship could be found between your account and $user, therefore we cannot unfriend you.";
	        exit();
		}
	}
}
?><?php
if (isset($_POST['action']) && isset($_POST['reqid']) && isset($_POST['user1'])){
	$reqid = preg_replace('#[^0-9]#', '', $_POST['reqid']);
	$user = preg_replace('#[^a-z0-9]#i', '', $_POST['user1']);
	$sql = "SELECT COUNT(id) FROM users WHERE username='$user' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$exist_count = mysqli_fetch_row($query);
	if($exist_count[0] < 1){
		mysqli_close($db_conx);
		echo "$user does not exist.";
		exit();
	}
	if($_POST['action'] == "accept"){
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$log_username' AND user2='$user' AND accepted='1' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count1 = mysqli_fetch_row($query);
		$sql = "SELECT COUNT(id) FROM friends WHERE user1='$user' AND user2='$log_username' AND accepted='1' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$row_count2 = mysqli_fetch_row($query);
	    if ($row_count1[0] > 0 || $row_count2[0] > 0) {
		    mysqli_close($db_conx);
	        echo "You are already friends with $user.";
	        exit();
	    } else {
			$sql = "UPDATE friends SET accepted='1' WHERE id='$reqid' AND user1='$user' AND user2='$log_username' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			mysqli_close($db_conx);
	        echo "accept_ok";
	        exit();
		}
	} else if($_POST['action'] == "reject"){
		mysqli_query($db_conx, "DELETE FROM friends WHERE id='$reqid' AND user1='$user' AND user2='$log_username' AND accepted='0' LIMIT 1");
		mysqli_close($db_conx);
		echo "reject_ok";
		exit();
	}
}
?>