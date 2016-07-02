<?php
include_once("php_includes/check_login_status.php");
// If the page requestor is not logged in, usher them away
if($user_ok != true || $log_username == ""){
	header("location: login.php");
    exit();
}


$notificationsthere="";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);

$sql2 = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";
$query2 = mysqli_query($db_conx, $sql2);
$numrows2 = mysqli_num_rows($query2);


if($numrows < 1||$numrows2 < 1){
	$notificationsthere='<img src="images/nonotifications.jpg">';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	
		/*$noteid = $row["id"];
		$initiator = $row["initiator"];
		$app = $row["app"];
		$note = $row["note"];
		$date_time = $row["date_time"];
		$date_time = strftime("%b %d, %Y", strtotime($date_time));
		$notification_list .= "<p><a href='user.php?u=$initiator'>$initiator</a> | $app<br />$note</p>";
		*/
	}
	$notificationsthere='<img src="images/notifications.gif">';
}
//mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?>












<link rel="stylesheet" href="style/style.css">
<div id="pageTop">

  <div id="pageTopWrap">
    <div id="pageTopLogo">
      <a href="index.php">
        <img src="images/logo3.png" alt="logo" title="Social Connect" height="100" width="100">
      </a>
        
    </div>
    <div id="pageTopRest">
      <div id="menu1">
        <div>
          <a href="logout.php">Logout</a>
        </div>
      </div>
      <div id="menu2">
        <div>
          <a href="login.php">
            <img src="images/home.png" alt="home" title="Home"  height="30" width="30">
          </a>
		  
          <a href="notifications.php"><?php echo $notificationsthere?></a>
          <a href="#">Menu_Item_2</a>
</div>
      </div>
    </div>
  </div>
 </div>