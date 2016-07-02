<?php
include_once("php_includes/check_login_status.php");
// If the page requestor is not logged in, usher them away
if($user_ok != true || $log_username == ""){
	header("location: login.php");
    exit();
}
$notification_list = "";
$sql = "SELECT * FROM notifications WHERE username LIKE BINARY '$log_username' ORDER BY date_time DESC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$notification_list = "You do not have any notifications";
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$noteid = $row["id"];
		$initiator = $row["initiator"];
		$app = $row["app"];
		$note = $row["note"];
		$date_time = $row["date_time"];
		$date_time = strftime("%b %d, %Y", strtotime($date_time));
		$notification_list .= "<p><a href='user.php?u=$initiator'>$initiator</a> | $app<br />$note</p>";
	}
	
}
mysqli_query($db_conx, "UPDATE users SET notescheck=now() WHERE username='$log_username' LIMIT 1");
?>

<?php
$friend_requests = "";
$sql = "SELECT * FROM friends WHERE user2='$log_username' AND accepted='0' ORDER BY datemade ASC";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows < 1){
	$friend_requests = 'No friend requests';
} else {
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$reqID = $row["id"];
		$user1 = $row["user1"];
		$datemade = $row["datemade"];
		$datemade = strftime("%B %d", strtotime($datemade));
		$thumbquery = mysqli_query($db_conx, "SELECT avatar FROM users WHERE username='$user1' LIMIT 1");
		$thumbrow = mysqli_fetch_row($thumbquery);
		$user1avatar = $thumbrow[0];
		$user1pic = '<img src="user/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
		if($user1avatar == NULL){
			$user1pic = '<img src="images/avatardefault.jpg" alt="'.$user1.'" class="user_pic">';
		}
		$friend_requests .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
		$friend_requests .= '<a href="user.php?u='.$user1.'">'.$user1pic.'</a>';
		$friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="user.php?u='.$user1.'">'.$user1.'</a> requests friendship<br /><br />';
		$friend_requests .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">accept</button> or ';
		$friend_requests .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">reject</button>';
		$friend_requests .= '</div>';
		$friend_requests .= '</div>';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notifications and Friend Requests</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<style type="text/css">
div#notesBox{float:left; width:430px; border:#F0F 1px dashed; margin-right:60px; padding:10px;}
div#friendReqBox{float:left; width:430px; border:#F0F 1px dashed; padding:10px;}
div.friendrequests{height:74px; border-bottom:#CCC 1px solid; margin-bottom:8px;}
img.user_pic{float:left; width:68px; height:68px; margin-right:8px;}
div.user_info{float:left; font-size:14px;}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script type="text/javascript">
function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to '"+action+"' this friend request.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Request Accepted!</b><br />Your are now friends";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject friendship with this user";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
}
</script>
</head>
<body>


<?php
if($user_ok==true)
{
//include_once("template_pageTop.php"); 


echo'<link rel="stylesheet" href="style/style.css">
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
		  
          <a href="notifications.php"><img src="images/nonotifications.jpg"></a>
          <a href="#">Menu_Item_2</a>
</div>
      </div>
    </div>
  </div>
 </div>';




}

else
{
include_once("template_pageTop2.php");
}
?>


<br/>
<br/>
<div id="pageMiddle">
  <!-- START Page Content -->
  <div id="notesBox"><h2>Notifications</h2><?php echo $notification_list; ?></div>
  <div id="friendReqBox"><h2>Friend Requests</h2><?php echo $friend_requests; ?></div>
  <div style="clear:left;"></div>
  <!-- END Page Content -->
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>