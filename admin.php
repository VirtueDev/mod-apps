<?php
	session_start();
	
	$actual_username = '...';
	$actual_password = hash('sha512', '...');
	
	if(!$_SESSION['adminOn']) {
		echo "<form action='' method='post'>Admin's username: <input type='text' name='username' /><br/>Admin's password: <input type='password' name='password' /><br/><input type='submit' name='submitLogin' value='Login' /></form>";
	}else{
		$c = mysql_connect("...", "...", "...");
		
		$grab_app = "SELECT * FROM `syncedapp`.`moderator`";
		$e = mysql_query($grab_app, $c);
		
		if(!$e) {
			die("Error grabbing applications for moderators: ".mysql_error());
		}
		echo "<b>Synced Id's and such will appear as 0 or blank because it is not submitted yet.</b><br/><table border='1' style='background-color: gray;'><tr><td>Synced ID</td><td>User's Age</td><td>Has Posted</td><td>Reasons why</td><td>Ip Address</td></tr>";
		while($row = mysql_fetch_array($e)) {
			echo "<tr><td>{$row['synced_id']}</td><td>{$row['synced_age']}</td><td>{$row['posted']}</td><td>{$row['synced_reason']}</td><td>{$row['synced_ip']}</td><td><a href='admin.php?deletePost=deleted&id={$row['id']}'>Delete Appliaction/Ignore Application</a></td></tr>";
		}
		echo "</table>";
	}
	
	if(isset($_POST['submitLogin'])) {
		$username = mysql_escape_string(stripslashes($_POST['username']));
		$password = mysql_escape_string(stripslashes(hash('sha512', $_POST['password'])));
		
		if($username == $actual_username && $password == $actual_password) {
			$_SESSION['adminOn'] = true;
			header("Location: admin.php");
		}
	}
	
	if(isset($_GET['deletePost'])) {
		$id = $_GET['id'];
		
		$delete = "DELETE FROM `syncedapp`.`moderator` WHERE id = $id";
		$e = mysql_query($delete, $c);
		
		if(!$e) {
			die("Failed to delete post ($id): ".mysql_error());
		}
		header("Location: admin.php");
	}
		//$submittedusr = hash('sha512', $username);
		//$submiitedpsw = hash('sha512', $password);
?>