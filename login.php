<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away TO its main page
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?>


<?php

// AJAX CALLS THIS LOGIN CODE TO EXECUTE
if(isset($_POST["e"])){
	// CONNECT TO THE DATABASE
	include_once("php_includes/db_conx.php");
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$p = $_POST['p'];
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// FORM DATA ERROR HANDLING
	if($e == "" || $p == ""){
		echo "login_failed";
        exit();
	} else {
	// END FORM DATA ERROR HANDLING
		$sql = "SELECT id, username, password FROM users WHERE email='$e' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
		$db_id = $row[0];
		$db_username = $row[1];
        $db_pass_str = $row[2];
		if($p != $db_pass_str){
			echo "login_failed";
            exit();
		} else {
			// CREATE THEIR SESSIONS AND COOKIES
			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['password'] = $db_pass_str;
			setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
    		setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE); 
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			$sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
			echo $db_username;
		    exit();
		}
	}
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Log In</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
<style type="text/css">
#loginform{
	margin-top:24px;	
}
#loginform > div {
	margin-top: 12px;	
}
#loginform > input {
	width: 200px;
	padding: 3px;
	background: #F3F9DD;
}
#loginbtn {
	font-size:15px;
	padding: 10px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var e = _("email").value;
	var p = _("password").value;
	if(e == "" || p == ""){
		_("status").innerHTML = "Fill out all of the form data";
	} else {
		_("loginbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "login_failed"){
					_("status").innerHTML = "Login unsuccessful, please try again.";
					_("loginbtn").style.display = "block";
				} else {
					window.location = "user.php?u="+ajax.responseText;
				}
	        }
        }
        ajax.send("e="+e+"&p="+p);
	}
}
</script>
</head>
<body id="back" background="images/1.jpg">
<?php
if($user_ok==true)
{
include_once("template_pageTop.php"); 
}

else
{
include_once("template_pageTop2.php");
}

?>
<div id="pageMiddle">
<br/>
<br/>
  <h3>Log In Here</h3>
  <!-- LOGIN FORM -->
  <form id="loginform" onsubmit="return false;">
    <div>Email Address:</div>
    <input type="text" id="email" onfocus="emptyElement('status')" maxlength="88">
    <div>Password:</div>
    <input type="password" id="password" onfocus="emptyElement('status')" maxlength="100">
    <br /><br />
    <button id="loginbtn" onclick="login()">Log In</button> 
    <p id="status"></p>
    <a href="">Forgot Your Password?</a>
  </form>
  <!-- LOGIN FORM -->
</div>
<?php //include_once("template_pageBottom.php"); ?>
</body>
</html>