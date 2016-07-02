//CRON JOB PAGE this is need to see the video


<?php
require_once("../php_includes/db_conx.php");
// This block deletes all accounts that do not activate after 3 days
$sql = "SELECT id, username FROM users WHERE signup<=CURRENT_DATE - INTERVAL 3 DAY AND activated='0'";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows > 0){
	while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	  $id = $row['id'];
	  $username = $row['username'];
	  $userFolder = "../user/$username";
	  if(is_dir($userFolder)) {
          rmdir($userFolder);
      }
	  mysqli_query($db_conx, "DELETE FROM users WHERE id='$id' AND username='$username' AND activated='0' LIMIT 1");
	  mysqli_query($db_conx, "DELETE FROM useroptions WHERE username='$username' LIMIT 1");
    }
}
?>