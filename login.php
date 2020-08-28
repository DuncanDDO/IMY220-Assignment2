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
	<meta name="author" content="Duncan Oosthuizen">
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
				
					echo 	"<form method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='hidden' id='loginEmail' class='form-control' value='" .$email. "' name='loginEmail'>
									<input type='hidden' id='loginPass' class='form-control' value='" .$pass. "' name='loginPass'>
									<input type='file' class='form-control' name='fileToUpload' id='fileToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
							</form>";
							
							
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

			
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
				$target_dir = "gallery/";
				$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

				if ($_FILES["fileToUpload"]["size"] > 1000000) {
					echo "Sorry, your file is too large.";
					$uploadOk = 0;
				}

				if($imageFileType != "jpg" && $imageFileType != "jpeg") {
					echo "Sorry, only JPG & JPEG files are allowed.";
					$uploadOk = 0;
				}

				if ($uploadOk == 0) {
					//echo "Your file was not uploaded.";
				} 
				else {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
						//echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
					} 
					else {
						//echo "Sorry, there was an error uploading your file.";
					}
				}

				$query1 = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$userid = $mysqli->query($query1);
				$row1 = mysqli_fetch_array($userid);
				$userids = $row1['user_id'];

				
				$imagename = $_FILES["fileToUpload"]["name"];

				$query2 = "INSERT INTO tbgallery (user_id, filename) VALUES ('$userids', '$imagename');";
				$res = mysqli_query($mysqli, $query2) == TRUE;

				echo "      <h2> Image Gallery </h2>
							<div class='row imageGallery'>";

							$query11 = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
							$userid1 = $mysqli->query($query11);
							$row11 = mysqli_fetch_array($userid1);
							$userids1 = $row11['user_id'];

							$sql = "SELECT * FROM tbgallery WHERE user_id = '$userids1'";
							$result = $mysqli->query($sql);
						
							$array = array();
							$count = 0;
						
							while($row = mysqli_fetch_assoc($result)) {
								$array[] = $row;
								$count++;
							}

							$k = 0;

							for($i = $count - 1; $i >= 0; $i--) {
								echo '  <div class="col-3" style="background-image: url(gallery/' .$array[$i]['filename']. ')">
										</div>';
								
								$k = $k + 1;
							}



						echo	"</div>
							  ";
			}


		?>
	</div>
</body>
</html>