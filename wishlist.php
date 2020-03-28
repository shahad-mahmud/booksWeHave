<?php
	session_start();

	$isLoggedin = true;

	if(!(isset($_SESSION['isLoggedin'])) || $_SESSION['isLoggedin'] == "False"){
		$isLoggedin = false;
	}
	require "assets/php/dp.php";

	$books = array();
	$book_author = array();
	$authors = array();
	$userName = array();

	//fill authors array
	$author_sql = "SELECT `author_id`,`author_name` FROM `authors` ORDER BY `author_name` ASC";
	$execute = $connection->query($author_sql);

	while($row = $execute -> fetch_assoc()){
		$authors[$row['author_id']] = $row['author_name'];
	}

	//fills book array
	$execute = $connection->query("SELECT books.`book_id`, `book_name`, users.name FROM `books`, `wish_list`, `users` WHERE books.book_id LIKE wish_list.book_id AND wish_list.user_name LIKE users.user_name");

	while($row = $execute -> fetch_assoc()){
		$bookId = $row['book_id'];
		$books[$bookId] = $row['book_name'];
		$userName[$bookId] = $row['name'];

		$authors_sql = "SELECT `book_id`, `author_id` FROM `writes` WHERE `book_id` LIKE '$bookId'";
		$execute_authors_sql = $connection->query($authors_sql);

		$authorName = "";
		while($row2 = $execute_authors_sql -> fetch_assoc()){
			$authorName = $authorName. " ". $authors[$row2['author_id']];
		}

		$book_author[$bookId] = $authorName;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Wish List | Shahad Mahmud</title>

	<link rel="stylesheet" type="text/css" href="assets/css/index.css">
	<link rel="stylesheet" type="text/css" href="assets/css/sidemenu.css">
</head>
<body>
	<div class="sidenav">
		<a href="index.php">Home</a>

		<?php
			if($isLoggedin == true){ ?>

				<a href="newbook.php">Add a new book</a>
			  	<a href="newauthor.php">Add new author</a>
			  	<a href="lend.php">Lend a book</a>
			  	<a href="addtowishlist.php">Add to wish list</a>
			  	<a class="active" href="wishlist.php">Wish list</a>
			  	<a href="logout.php">Logout</a>

			<?php }elseif ($isLoggedin == false) { ?>
			
				<a class="active" href="wishlist.php">Wish list</a>
				<a href="login.php">Login</a>

			<?php }

		?>
	</div>

	<div class="main">
		<table id="books">
			<tr>
				<th>বইয়ের নাম</th>
				<th>লেখক</th>
				<th>wish of</th>
			</tr>
			<?php foreach ($books as $key => $value) {
				?>

				<tr>
					<td><?php echo $value; ?></td>
					<td><?php echo $book_author[$key]; ?></td>
					<td><?php echo $userName[$key]; ?></td>
				</tr>

				<?php
			} ?>
			

		</table>
	</div>
   
</body>
</html>