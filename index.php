<?php
	session_start();

	$isLoggedin = true;

	if(!(isset($_SESSION['isLoggedin'])) || $_SESSION['isLoggedin'] == "False"){
		$isLoggedin = false;
	}
	require "assets/php/dp.php";

	$books = array();
	$images = array();
	$book_author = array();
	$authors = array();

	$totalBooks = 0;
	$totalAuthors = 0;

	//fill authors array
	$author_sql = "SELECT `author_id`,`author_name` FROM `authors` ORDER BY `author_name` ASC";
	$execute = $connection->query($author_sql);

	while($row = $execute -> fetch_assoc()){
		$authors[$row['author_id']] = $row['author_name'];
		$totalAuthors = $totalAuthors+1;
	}

	//fills book array
	$execute = $connection->query("SELECT
									    books.`book_id`,
									    books.`book_name`,
									    books.`book_image`
									FROM
									    `books`
									WHERE
									    books.`book_id` NOT IN (SELECT wish_list.book_id FROM wish_list)");

	while($row = $execute -> fetch_assoc()){
		$bookId = $row['book_id'];
		$books[$bookId] = $row['book_name'];
		$totalBooks = $totalBooks+1;

		if($row['book_image'] == ""){
			$images[$bookId] = "uploads/books/default.png";
		}else{
			$images[$bookId] = "uploads/books/".$row['book_image'];
		}

		$authors_sql = "SELECT `book_id`, `author_id` FROM `writes` WHERE `book_id` LIKE '$bookId'";
		$execute_authors_sql = $connection->query($authors_sql);

		$authorName = "";
		$i = 0;
		while($row2 = $execute_authors_sql -> fetch_assoc()){
			if($i > 0){
				$authorName = $authorName. ",";
			}
			$authorName = $authorName. " ". $authors[$row2['author_id']];
			$i = $i + 1;
		}

		$book_author[$bookId] = $authorName;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Books we have | Shahad Mahmud</title>

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

	<link rel="stylesheet" type="text/css" href="assets/css/index.css">
	<link rel="stylesheet" type="text/css" href="assets/css/sidemenu.css">
</head>
<body>
	<div class="sidenav">
		<a class="active" href="index.php">Home</a>

		<?php
			if($isLoggedin == true){ ?>

				<a href="newbook.php">Add a new book</a>
			  	<a href="newauthor.php">Add new author</a>
			  	<a href="lend.php">Lend a book</a>
			  	<a href="addtowishlist.php">Add to wish list</a>
			  	<a href="wishlist.php">Wish list</a>
			  	<a href="logout.php">Logout</a>

			<?php }elseif ($isLoggedin == false) { ?>
			
				<a href="wishlist.php">Wish list</a>
				<a href="login.php">Login</a>

			<?php }

		?>
	</div>

	<div class="main">

		<div class="counter">
		    <div class="container">
		        <div class="row">
		            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		                <div class="employees">
		                    <p class="counter-count"><?php echo $totalBooks ?></p>
		                    <p class="employee-p">Total books</p>
		                </div>
		            </div>

		            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		                <div class="customer">
		                    <p class="counter-count"><?php echo $totalAuthors ?></p>
		                    <p class="customer-p">Authors</p>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>

		<table id="books">
			<tr>
				<th>বই</th>
				<th>বইয়ের নাম</th>
				<th>লেখক</th>
			</tr>
			<?php 
			$i = 100;
			foreach ($books as $key => $value) {
				$i = $i + 1;
				?>

				<tr>
					<td><img onclick="showImage('<?php echo $key; ?>')" id="<?php echo $key; ?>" src="<?php echo $images[$key]; ?>" alt="<?php echo $images[$key]; ?>" height="60" width="42"></td>
					<td><?php echo $value; ?></td>
					<td><?php echo $book_author[$key]; ?></td>
				</tr>

				<?php

			} 
			?>
			

		</table>
	</div>

	<div id="myModal" class="modal">
	  <span class="close">&times;</span>
	  <img class="modal-content" id="img01">
	 
	</div>

	<script type="text/javascript">
		function showImage(id){
			

			var modal = document.getElementById("myModal");
			var img = document.getElementById(id);
			var modalImg = document.getElementById("img01");
			var captionText = document.getElementById("caption");

			modal.style.display = "block";
		    modalImg.src = img.src;
		    modalImg.style.width = img.style.width/img.style.height;

		    var span = document.getElementsByClassName("close")[0];
		    span.onclick = function() { 
			  modal.style.display = "none";
			}

			console.log(id);
		}
	</script>

	<script type="text/javascript">
		
		$('.counter-count').each(function () {
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        }, {
            duration: 5000,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
	</script>
   
</body>
</html>