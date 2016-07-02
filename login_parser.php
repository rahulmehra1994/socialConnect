<?php_egg_logo_guid

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

?>
