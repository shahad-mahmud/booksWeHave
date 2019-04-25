<?php
	
	$server = "";
	$user_name = "root";
	// $user_name = "arduytgw_shahad";
	$pass = "";
	// $pass = "exodia2018pathok";
	$db = "books";
	// $db = "arduytgw_pathok";

	$connection = mysqli_connect("localhost",$user_name,$pass,$db) or die("Can not connect to database.");
	// if($connection){
	// 	echo "string";
	// }
 	$connection -> set_charset("utf8");
	// $connection -> query("SET time_zone = '+10:00'");

?>