<?php
	require "assets/php/dp.php";


	$target_dir = "bookaholic/books/";
	$file_name = date('YmdHis') . '_' . uniqid(rand(),false);
	$temp_name = $_FILES["author_image"]["tmp_name"];
	$target_file_name = $target_dir . $file_name;
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($_FILES["author_image"]["name"],PATHINFO_EXTENSION));
	$target_file = $target_file_name. '.' .$imageFileType;
	$file_name_for_db = $file_name. '.' .$imageFileType;

	// echo $file_name_for_db. "<br>";

	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($temp_name);
			if($check !== false) {
			// echo "File is an image - " . $check["mime"] . ". <br>";
			$uploadOk = 1;
		} else {
			// echo "File is not an image. <br>";
			$uploadOk = 0;
		}
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
		// echo "Sorry, only JPG, JPEG & PNG files are allowed. <br>";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		// echo "Sorry, your file was not uploaded. <br>";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($temp_name, $target_file)) {
			if($imageFileType == "jpg" || $imageFileType == "jpeg"){
				$newImage = imagecreatefromjpeg($target_file);
			}else if($imageFileType == "png"){
				$newImage = imagecreatefrompng($target_file);
			}

			list($width, $heigth) = getimagesize($target_file);

			$newWidth = 500;
			$newHigth = ($heigth / $width) * $newWidth;

			$newImageFrame = imagecreatetruecolor($newWidth,$newHigth);

			unlink($target_file);

			imagecopyresampled($newImageFrame, $newImage, 0, 0, 0, 0, $newWidth, $newHigth, $width, $heigth);
			if($imageFileType == "jpg" || $imageFileType == "jpeg"){
				imagejpeg($newImageFrame, $target_file, 30);
			}else if($imageFileType == "png"){
				imagepng($newImageFrame, $target_file, 9);
			}

			echo($file_name_for_db);

		} else {
		// echo "Sorry, there was an error uploading your file. <br>";
		}
	}
			//----------------handling image upload----------------------
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add new book | Books we have | Shahad Mahmud</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
	<link rel="stylesheet" type="text/css" href="assets/css/sidemenu.css">
	<link rel="stylesheet" type="text/css" href="assets/css/formstyle.css">
</head>
<body>

	<div class="main">
	  
		<form class="form_container" action="" method="post" enctype="multipart/form-data">
		    <label for="author_image">Upload image</label>
		    <input type="file" id="author_image" name="author_image" value="">
		  
		    <input type="submit" name="submit" value="Submit">
		</form>

	</div>
   
</body>

	<script>
	
	</script>
</html>