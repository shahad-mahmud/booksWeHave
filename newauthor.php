<?php
	require "assets/php/dp.php";

	if(isset($_POST['submit'])){

		function treatIncomingData($data){
	        $data = trim($data);
	        $data = stripslashes($data);
	        $data = htmlspecialchars($data);
	        return $data;
	    }


	    //get data
	    $post_author_name = treatIncomingData($_POST['author_name']);
	    $post_author_bio = treatIncomingData($_POST['author_bio']);

	    $execute = $connection->query("SELECT `author_id` FROM `authors` WHERE `author_name` LIKE '$post_author_name'");
	    $rows = mysqli_num_rows($execute);

	    if($rows > 0){
	    	echo '<script language="javascript">';
			echo 'alert("this author is already in our database.")';
			echo '</script>'; 
	    }else{

	    	$target_dir = "uploads/authors/";
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
			        imagepng($newImageFrame, $target_file, 30);
			      }

			  } else {
			      // echo "Sorry, there was an error uploading your file. <br>";
			  }
			}
			//----------------handling image upload----------------------


			$insert_author = "INSERT INTO `authors`( `author_name`, `author_bio`, `author_image`) VALUES ('$post_author_name','$post_author_bio','$file_name_for_db')";
      		$execute_author_insert = $connection->query($insert_author);

			if($execute_author_insert){
				echo '<script language="javascript">';
				echo 'alert("Author successfully added")';
				echo '</script>';
			}else{
				echo '<script language="javascript">';
				echo 'alert("Author insertion error occured."'.mysqli_error($connection).')';
				echo '</script>';
			}


		}
		

	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Add new book | Books we have | Shahad Mahmud</title>

	<link rel="stylesheet" type="text/css" href="assets/css/index.css">
	<link rel="stylesheet" type="text/css" href="assets/css/sidemenu.css">
	<link rel="stylesheet" type="text/css" href="assets/css/formstyle.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
</head>
<body>
	<div class="sidenav">
		<a href="index.php">Home</a>
	 	<a href="newbook.php">Add a new book</a>
	 	<a href="newauthor.php">Add new author</a>
	  	<a href="#">Lend a book</a>
	  	<a href="#">Wish list</a>
	</div>

	<div class="main">
	  
		<form class="form_container" action="" method="post" enctype="multipart/form-data">
		    <label for="author_name">Author Name</label>
		    <input type="text" id="author_name" name="author_name" value="" placeholder="Author Name" required>

		    <label for="author_bio">Author Bio</label>
		    <textarea rows="4" cols="50" type="text" id="author_bio" name="author_bio" value="" placeholder="Author Bio"></textarea>

		    <label for="author_image">Upload image</label>
		    <input type="file" id="author_image" name="author_image" value="">
		  
		    <input type="submit" name="submit" value="Submit">
		</form>

	</div>
   
</body>
</html>