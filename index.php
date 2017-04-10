<html>
 <head>
  <title>Synced Online - Moderators Application</title>
 </head>
 <body>
  <center><b>Simple form for a moderator application.: </b><i style='color:red;'>Your IP Address is used to prevent spamming and will not be released publically to users.</i></center><br/>
  <?php
	$c = mysql_connect("...", "...", "...");
	
	$enabled = true;//Set to false to not accept moderator applications.
	
	if($enabled == true) {
		echo "Status: <b style='color:green;'>Opened</b><br/>";
	}else{
		die("Status: <b style='color:red;'>Closed</b>");
	}
	
	$myIP = $_SERVER['REMOTE_ADDR'];
	
	$hasPosted = "SELECT * FROM `twsicomm_syncedapp`.`moderator` WHERE synced_ip = '$myIP'";
	$e = mysql_query($hasPosted, $c);
	
	if(!$e) {
		die(mysql_error());
	}
	
	while($row = mysql_fetch_array($e)) {
		$posted = (int) "{$row['posted']}";
		$ipAddress = "{$row['synced_ip']}";
	}
	
	if($myIP !== $ipAddress) {
		$createIP = "INSERT INTO `twsicomm_syncedapp`.`moderator`(`synced_ip`) VALUES('$myIP')";
		$e3 = mysql_query($createIP, $c);
		if(!$e3) {
			die("Error creating ip for app: ".mysql_error());
		}
	}
	
	if($posted == false) {
		if(isset($_POST['submitModApp'])) {
			$userid = mysql_escape_string(stripslashes((int) $_POST['synced_id']));
			$age = mysql_escape_string(stripslashes((int) $_POST['synced_age']));
			$freetime = mysql_escape_string(stripslashes($_POST['synced_free']));
			$reasons = mysql_escape_string(stripslashes($_POST['synced_reason']));
			$responds = mysql_escape_string(stripslashes($_POST['synced_responds']));
			
			$insert = "UPDATE `twsicomm_syncedapp`.`moderator` SET synced_id = ".$userid.", synced_age = ".$age.", posted = 1, synced_free = '$freetime', synced_reason = '$reasons', synced_r = '$responds' WHERE synced_ip = '$myIP'";
			$e1 = mysql_query($insert, $c);
			if(!$e1) {
				die("Error with updating app: ".mysql_error());
			}
			
			echo "<p style='color:rgb(0,150,0);'>Thank you for submitting your application. Your application will not be shown to the public and will be under review by an admin.</p>";
		}
	}else{
		die( "<p style='color:rgb(150,0,0);'>You have already submitted your application.</p>" );
	}
  ?>
  <form action='' method='POST'>
   Synced Profile ID: <input type='text' name='synced_id' value='Type only numbers.' /><br/>
   Age (Must be 18 years old to be mod): <input type='text' name='synced_age' value='Type only numbers.' /><br/>
   When are you available to moderate (Provide your timezone): <input type='text' name='synced_free' value='Type the schedule.' /><br/>
   Reasons you want to be a mod: <br/><textarea cols='40' rows='6' name='synced_reason'></textarea><br/>
   A moderator will mail you on Synced for more questions. Will you respond?<br/>
   <input type="radio" name="responds" value="yes" checked> Yes<br/>
   <input type="radio" name="responds" value="no"> No<br/>
   <input type='submit' name='submitModApp' value='Submit your application.' />
  </form>
 </body>
</html>
