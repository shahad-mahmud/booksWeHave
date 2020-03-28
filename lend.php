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

    // $authors = array();
    $books = array();

	//fill books array
	$books_sql = "SELECT `book_id`, `book_name`, `book_image` FROM `books` ORDER BY `book_name` ASC";
	$execute = $connection->query($books_sql);

	while($row = $execute -> fetch_assoc()){
		$books[$row['book_id']] = $row['book_name'];
	}

    //todo: handle the lend books---------------------------------------
	//insertion
	if(isset($_POST['submit'])){

		function treatIncomingData($data){
	        $data = trim($data);
	        $data = stripslashes($data);
	        $data = htmlspecialchars($data);
	        return $data;
	    }


	    //get data
        $borrowerName = treatIncomingData($_POST['borrower_name']);
        $borrowerNo = treatIncomingData($_POST['borrower_contact_no']);
        $dateOfBorrow = treatIncomingData($_POST['date_to_take']);
	    $booksToBorrow = $_POST['books'];

        //todo: insert into lend table

        $isOk = true;

        foreach ($booksToBorrow as $key => $book_id) {
            // echo "\t\t\t\t\t" + $key + " => " + $book_id + "\n";
            $insert_lend = "INSERT INTO `lending`(`borrower_name`, `borrower_no`, `date_of_borrow`, `date_of_return`, `book_id`, `isReturned`) 
                                        VALUES ('$borrowerName', '$borrowerNo', '$dateOfBorrow', '$dateOfBorrow', '$book_id', false)";
            $execute_lend_insert = $connection->query($insert_lend);
            
            if(!$execute_lend_insert)
                $isOk = false;

        }

        

        if($isOk){
            echo '<script language="javascript">';
            echo 'alert("Lend record successfully added")';
            echo '</script>';
        }else{
            echo '<script language="javascript">';
            echo 'alert("Lend record insertion error occured."'.mysqli_error($connection).')';
            echo '</script>';
        }
		

	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Lend a book | Books we have | Shahad Mahmud</title>

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
	  	<a class="active" href="lend.php">Lend a book</a>
	  	<a href="addtowishlist.php">Add to wish list</a>
		<a href="wishlist.php">Wish list</a>
	  	<a href="logout.php">Logout</a>
	</div>

	<div class="main">
	  
		<form class="form_container" action="" method="post" enctype="multipart/form-data">
		    <label for="borrower_name">Name</label>
            <input type="text" id="borrower_name" name="borrower_name" value="" placeholder="Name" required>
            
            <label for="borrower_contact_no">Mobile number</label>
		    <input type="text" id="borrower_contact_no" name="borrower_contact_no" value="" placeholder="Name" required>

		    <label for="books">Book(s)</label>
		    <select class="author_class" name="books[]" multiple required>
				<?php
					foreach ($books as $key => $value) {
						echo "<option option value=".$key.">".$value."</option>";
					}
				?>
	        </select><br><br>


		    <label for="date_to_take">Date of lending</label>
            <input type="date" id="date_to_take" name="date_to_take" value=""><br><br>
		  
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