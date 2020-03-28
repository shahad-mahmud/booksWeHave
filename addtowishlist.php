<?php
	session_start();

	if(!(isset($_SESSION['isLoggedin'])) || $_SESSION['isLoggedin'] == "False"){
		// echo '<script language="javascript">';
		// echo 'alert("Please login!")';
		// echo '</script>';

		?>

		<script>

		var txt;
		var r = confirm("Please login First");
		if (r == true) {
			window.location.href = "login.php";
		} else {
			window.location.href = "index.php";
		}
		 
		</script>

		<?php 
	}

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
			echo 'alert("this book is already brought.")';
			echo '</script>'; 
	    }else{
			$insert_book = "INSERT INTO `books`(`book_name`, `book_image`) VALUES ('$bookName','')";
      		$execute_book_insert = $connection->query($insert_book);

			if($execute_book_insert){
				$execute = $connection->query("SELECT `book_id` FROM `books` WHERE `book_name` LIKE '$bookName'");
				$row = $execute -> fetch_assoc();
          		$bookId = $row['book_id'];

				foreach ($postAuthors as $key => $value) {
					$insert_into_writes = "INSERT INTO `writes`(`book_id`, `author_id`) VALUES ('$bookId','$value')";
					$connection -> query($insert_into_writes);
				}

				$user = $_SESSION['userName'];
				$insert_wish = "INSERT INTO `wish_list`(`book_id`, `user_name`) VALUES ('$bookId', '$user')";
				$execute_wish_insert = $connection->query($insert_wish);

				if($execute_wish_insert){
					echo '<script language="javascript">';
					echo 'alert("Book successfully added")';
					echo '</script>';
				}else{
					echo '<script language="javascript">';
					echo 'alert("Book insertion error occured."'.mysqli_error($connection).')';
					echo '</script>';
				}

					
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
	<title>Add to wish list | Books we have | Shahad Mahmud</title>

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
	  	<a href="lend.php">Lend a book</a>
	  	<a class="active" href="addtowishlist.php">Add to wish list</a>
		<a href="wishlist.php">Wish list</a>
	  	<a href="logout.php">Logout</a>
	</div>

	<div class="main">
	  
		<form class="form_container" action="" method="post" enctype="multipart/form-data">
		    <label for="book_name">Book Name</label>
		    <input type="text" id="book_name" name="book_name" value="" placeholder="Book Name" required>

		    <label for="author">Author(s)</label>
		    <select class="author_class" name="author[]" multiple required>
				<?php
					foreach ($authors as $key => $value) {
						echo "<option option value=".$key.">".$value."</option>";
					}
				?>
	        </select>
		  
		    <input type="submit" name="submit" value="Submit">
		</form>

	</div>
   
</body>

	<script>
		$(document).ready(function() {
			$('.author_class').select2();
		});
    </script>

    <script>
		function myFunction() {
			var txt;
			var r = confirm("Press a button!\nEither OK or Cancel.\nThe button you pressed will be displayed in the result window.");
			if (r == true) {
				txt = "You pressed OK!";
			} else {
				txt = "You pressed Cancel!";
			}
			document.getElementById("demo").innerHTML = txt;
		}
	</script>
</html>