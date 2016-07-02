<?php_egg_logo_guid
if(isset($_POST))
{
$message="";
if(count($_POST)>0) {
$conn = mysql_connect("localhost","root","root");
mysql_select_db("socialnetwork",$conn);
$result = mysql_query("SELECT * FROM users WHERE email='" . $_POST["email"] . "' and password = '". $_POST["password"]."'");
$count  = mysql_num_rows($result);
if($count==0) {
$message = "Invalid email or Password!";
} else {
$message = "You are successfully authenticated!";
}
}
}

?>




<html>
<head>
<title>
login2
</title>
</head>
<body>
<div id="1">
<div id="left">
<form action="login2.php" method="post">
<p>Email</p>
<input type="text" id="email" name="email" maxlenght="50">
<p>Password</p>
<input type="password" id="password" name="password" maxlenght="50">
<br/><br/>
<input type="submit" id="submit" name="login" value="submit">
</form>
</div>
<div id="right">

</div>
<?php include_once("template_pageBottom.php");?>
</div>
</body>
</html>
