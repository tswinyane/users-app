<?php
	$conn = mysqli_connect("localhost", "root", "letmein", "usersdb");
	
	if(!$conn){
		die("Error: Failed to connect to database!");
	}
?>