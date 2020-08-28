<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false

	


?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Bheki Ndhlovu">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";

							echo 	"<form method='POST' action='login.php' enctype='multipart/form-data'>
							<div class='form-group'>
								<input type='hidden' id='pass' name='loginEmail' value=".$row['email'].">
								<input type='hidden' id='email' name='loginPass' value=".$row['password'].">
								<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
								<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
							</div>
						  </form>";
						$user = $row['user_id'];
						if(isset($_POST['submit']))
						{
							$pic = isset($_FILES["picToUpload"]);
							$filename = $_FILES['picToUpload'];
							$userId = $user;
							$indi = $pic["name"];
							$image = $_FILES["picToUpload"]["name"];
							$file_path = pathinfo($image);
							$file_ext = $file_path["extension"];
							$file_ext = strtolower($file_ext);
							$directory = "gallery/";
							$file_name = $file_path['filename']; 
							$temp_name = $_FILES['picToUpload']['tmp_name'];
							$path_filename_ext = $directory.$file_name.".".$file_ext;
							$target_dir= "gallery/";
							$uploadFile= $filename;
							$target_file= $target_dir. basename($uploadFile["name"]);
							$imageFileType= pathinfo($target_file,PATHINFO_EXTENSION);

							// Check if image is an actual image (Note that this method is unsafe)

							if(($uploadFile["type"] == "image/jpeg"	|| $uploadFile["type"] == "image/jpg")	&& $uploadFile["size"] < 1048576)
							{
								if($uploadFile["error"] > 0)
								{
									echo "Error: " . $uploadFile["error"] . "<br/>";
								} 
								else 
								{
									$insertgal = "INSERT INTO tbgallery (user_id, filename) VALUES ('$user', '$target_file');";
									$result = mysqli_query($mysqli,$insertgal)==TRUE;
									move_uploaded_file($temp_name,$path_filename_ext);
								}
							} 
							else
							{
								echo "Invalid file";
							}
						}
						

						$querygallery = "SELECT * FROM tbgallery WHERE user_id = '$user'";
						$res5 = $mysqli->query($querygallery);
						
					
						echo "<h1>Image Gallery</h1>";
				
							echo "<div class = 'row imageGallery'>";
						while($row5 = mysqli_fetch_array($res5))
						{
							
								echo "<div class = 'col-3' style = 'background-image: url(" . $row5['filename'] .")'></div>";	
							
						}
						echo "</div>";
				
				
				
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
	
</body>
</html>