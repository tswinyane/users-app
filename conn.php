<?php
	$conn = mysqli_connect("localhost", "root", "letmein", "usersdb");
	
	// Connection message if it fails
	if(!$conn){
		die("Error: Failed to connect to database!");
	}
?>