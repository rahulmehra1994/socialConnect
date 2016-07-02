
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Social Connect</title>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="style/style.css">
</head>
<body>


<?php include_once("php_includes/check_login_status.php");

if($user_ok==true)
{
 include_once("template_pageTop.php"); 
}
else
{
echo '
<div id="pageTop"">
  <div id="pageTopWrap" >
    <div id="pageTopLogo">
      <a href="index.php">
        <img src="images/logo3.png" alt="logo" title="Social Connect" height="100" width="100">
      </a>
        
    </div>
    <div id="pageTopRest">
      <div id="menu1">
        <div>
          <a href="#">Link</a>
        </div>
      </div>
      <div id="menu2">
        <div>
         
          <a href="login.php" style="font-size:25" >Login Here</a>
          <a href="signup.php" style="font-size:25" >Signup Here</a>
</div>
      </div>
    </div>
  </div>
 </div>

';

}

echo'<div id="pageMiddle">';
    echo"<br/><br/>";
	$counter=0;
	$sql="select * from users ORDER BY RAND() limit 12";
	$result = mysqli_query($db_conx, $sql);
	
	while($row=mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
	$username=$row['username'];
	$image=$row['avatar'];
	if($image!="")
	  {
	 	 echo '<a href="user.php?u='.$username.'"><img src="user/'.$username.'/'.$image.'" height="250" width="230"></a>';
	
      }
    else
		{
		echo '<a href="user.php?u='.$username.'"><img src="images/avatardefault.jpg" height="250" width="250"></a>';
		}
	$counter++;
	if($counter==4)
	  {
	  echo"<br/>";
	  $counter=0;
	  }
	
    }


  ?>
  
  
  
  
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>