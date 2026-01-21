<?php

	require_once 'conn.php'; 

	if(isset($_POST['save_details'])) {
		$fname = htmlspecialchars($_POST['firstname']);
		$surname = htmlspecialchars($_POST['surname']);
		$gender = htmlspecialchars($_POST['gender']);
		
		if($fname == "") {
			echo '<script>
					alert("Name is requored");
					window.location.href="";
				</script>';
		}
		
		if($surname == "") {
			echo '<script>
					alert("Surname is requored");
					window.location.href="";
				</script>';
		}
		
		$sql_insert = "INSERT INTO user(name, surname, gender) 
		VALUES('$fname', '$surname', $gender) ";
		$query_insert = mysqli_query($conn, $sql_insert) or die(mysqli_error($conn));
		
		if($query_insert) {
			echo '<script>
					alert("User details successfully added");
					window.location.href="";
				</script>';
		}
    }
	
	if(isset($_POST['update_details'])) {	
        //print_r($_POST);exit();	
		$uid = htmlspecialchars($_POST['uid']);
		$upname = htmlspecialchars($_POST['fname1']);
		$upsname = htmlspecialchars($_POST['lname']);
		$upgen = htmlspecialchars($_POST['gender_1']);
		
		// Prepare and bind
		$stmt = $conn->prepare("UPDATE user SET name = ?, surname = ?, gender = ? WHERE id = ?");
		$stmt->bind_param("sssi", $upname, $upsname, $upgen, $uid);

		if ($stmt->execute()) {			
			echo '<script>
					alert("User successfully updated");
					window.location.href="";
				</script>';		
		} 
		else {
			echo "Error: " . $stmt->error;
		}
		$stmt->close();
		
		
    }
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<h2 style="text-align: center;">Users Platform</h2>
		</div>
	</nav>
	
	<div class="container">
   		<h3 align="center">Enter User details</h3>   
   
		   <form name="frmLogin" method="post" action="" >    
			<div class="form-group">
				<label for="firstname">Firstname </label>
				<input type="text" name="firstname" class="form-control" id="username" required="required" maxlength="48">				
			</div>
		
			<div class="form-group">
				<label for="surname">Surname </label>
				<input type="text" name="surname" class="form-control" id="username" required="required" maxlength="64">				
			</div>  
	        
			<div class="form-group">
				<label>Gender</label>
				<select name="gender" class="form-control" >
					<option value="">Select</option>
					<option value="0">Male</option>
					<option value="1">Female</option>			
				</select>			
			</div>  
			
			<button type="submit" class="btn btn-info mb-5 " name="save_details">Save</button>

   		</form>
	
	
	
		<div class="col-md-3"></div>
		<br/><br/>
		
		<div class="col-md-6 well">
			<h3 class="text-primary">List of Users</h3>

			<form action="generatepdf.php" method="post">
				<button type="submit">Generate PDF</button>
			</form>
			<hr style="border-top:1px dotted #ccc;"/>
		
			<table class="table table-bordered">
				<thead class="alert-info">
					<tr>
					    <th>Id</th>
						<th>Firstname</th>
						<th>Lastname</th>
						<th>Gender</th>
						<th>Date modified</th>
						<th>Edit/Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php						
						
						$sql_stmt = "SELECT * FROM `user`";
						$sql_query = mysqli_query($conn, $sql_stmt) or die(mysqli_error($conn));					
						while($fetch = mysqli_fetch_assoc($sql_query)){
							//print_r($fetch);exit();
					?>
					<tr>
					    <td><?php echo $fetch['id']?></td>
						<td><?php echo $fetch['name']?></td>
						<td><?php echo $fetch['surname']?></td>
						<td><?php echo $fetch['gender']?></td>
						<td><?php echo $fetch['date_modified']?></td>
						<input type="hidden" name="fname" id="fname" value="<?php echo $fetch['name']?>">						
						<td>
							<!--<button type="button" class="btn btn-success" data-toggle="modal" data-target="#form_modal"><span class="glyphicon glyphicon-plus"></span>Edit user</button>
						    -->
							<button 
								type="button" 
								class="btn btn-success editBtn" 
								data-toggle="modal" 
								data-target="#form_modal"
								data-id="<?php echo htmlspecialchars($fetch['id']) ?>"
								data-name="<?php echo htmlspecialchars($fetch['name']) ?>"
								data-surname="<?php echo htmlspecialchars($fetch['surname']) ?>"
								data-gender="<?php echo htmlspecialchars($fetch['gender']) ?>"
							  >
								<span class="glyphicon glyphicon-plus"></span> Edit user
							</button>
						</td>
					</tr>
					<?php
						}
					?>
				</tbody>				
			</table>
		</div>		
		
    </div> <!--End of container div -->

<div class="modal fade" id="form_modal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="POST" action="">
				<div class="modal-header">
					<h3 class="modal-title">Update user details</h3>
				</div>
				<div class="modal-body">
					<div class="col-md-2"></div>
					<div class="col-md-8">
					
					    <input type="hidden" name="uid" class="form-control" >
						<div class="form-group">
						    
							<label>Firstname</label>
							<input type="text" name="fname1" class="form-control" required="required"/>
						</div>
						<div class="form-group">
							<label>Surname</label>
							<input type="text" name="lname" class="form-control" required="required"/>
						</div>						
						<div class="form-group">
							<label>Gender</label>
							<select name="gender_1" id="gender_1"  class="form-control" required="">
								<option value=""></option>
								<option value="0">Male</option>
								<option value="1">Female</option>								
							</select>
						</div>                        
					</div>
				</div>
				<br style="clear:both;"/>
				<div class="modal-footer">				
					<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
					<button class="btn btn-primary" name="update_details"><span class="glyphicon glyphicon-save"></span> Save</button>
				</div>
			</form>
		</div>
	</div>
</div>	

<script src="js/jquery-3.2.1.min.js"></script>	
<script src="js/bootstrap.js"></script>	

<script>
	$(document).ready(function() {
    $('.editBtn').on('click', function() {
		var id = $(this).data('id');
        var name = $(this).data('name');
        var surname = $(this).data('surname');
        var gender = $(this).data('gender');

        // Fill modal fields
		$('input[name="uid"]').val(id);
        $('input[name="fname1"]').val(name);
        $('input[name="lname"]').val(surname);
        $('select[name="gender_1"]').val(gender);
    });
});
        
</script>

</body>	
</html>