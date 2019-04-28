<?php
	require "assets/php/dp.php";

	$authors = array();

	//fill authors array
	$author_sql = "SELECT `author_id`,`author_name` FROM `authors` ORDER BY `author_name` ASC";
	$execute = $connection->query($author_sql);

	while($row = $execute -> fetch_assoc()){
		$authors[$row['author_id']] = $row['author_name'];
	}

	//insertion
	if(isset($_POST['submit'])){

		function treatIncomingData($data){
	        $data = trim($data);
	        $data = stripslashes($data);
	        $data = htmlspecialchars($data);
	        return $data;
	    }


	    //get data
	    $bookName = treatIncomingData($_POST['book_name']);
	    $postAuthors = $_POST['author'];

	    $execute = $connection->query("SELECT `book_id` FROM `books` WHERE `book_name` LIKE '$bookName'");
	    $rows = mysqli_num_rows($execute);

	    if($rows > 0){
	    	echo '<script language="javascript">';
			echo 'alert("this book is already in our database.")';
			echo '</script>'; 
	    }else{

	    	$target_dir = "uploads/books/";
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


			$insert_book = "INSERT INTO `books`(`book_name`, `book_image`) VALUES ('$bookName','$file_name_for_db')";
      		$execute_book_insert = $connection->query($insert_book);

			if($execute_book_insert){
				$execute = $connection->query("SELECT `book_id` FROM `books` WHERE `book_name` LIKE '$bookName'");
				$row = $execute -> fetch_assoc();
          		$bookId = $row['book_id'];

				foreach ($postAuthors as $key => $value) {
					$insert_into_writes = "INSERT INTO `writes`(`book_id`, `author_id`) VALUES ('$bookId','$value')";
					$connection -> query($insert_into_writes);
				}

				echo '<script language="javascript">';
				echo 'alert("Book successfully added")';
				echo '</script>';
			}else{
				echo '<script language="javascript">';
				echo 'alert("Book insertion error occured."'.mysqli_error($connection).')';
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

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
	<link rel="stylesheet" type="text/css" href="assets/css/sidemenu.css">
	<link rel="stylesheet" type="text/css" href="assets/css/formstyle.css">
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
		    <label for="book_name">Book Name</label>
		    <input type="text" id="book_name" name="book_name" value="" placeholder="Book Name" required>

		    <label for="author_bio">Author(s)</label>
		    <select class="author_class" name="author[]" multiple required>
				<?php
					foreach ($authors as $key => $value) {
						echo "<option option value=".$key.">".$value."</option>";
					}
				?>
	        </select>


		    <label for="author_image">Upload image</label>
		    <input type="file" id="author_image" name="author_image" value="">
		  
		    <input type="submit" name="submit" value="Submit">
		</form>

	</div>
   
</body>

<script>
  $(document).ready(function() {
      $('.author_class').select2();
    });
  </script>
</html>