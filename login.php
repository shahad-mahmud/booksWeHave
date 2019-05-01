<?php
    require "assets/php/dp.php";

    if(isset($_POST['submit'])){

        function treatIncomingData($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $userName = treatIncomingData($_POST['user_name']);
        $userPass = treatIncomingData($_POST['user_pass']);

        $sql = "SELECT `user_id`, `name`, `user_name`, `password` FROM `users` WHERE `user_name` LIKE '$userName' AND `password` LIKE '$userPass'";
        $query      = $connection->query($sql);                     //execute the query
        $row_count = mysqli_num_rows($query);   

        if($row_count == 0){
            session_start();                                    
            $_SESSION['isLoggedin'] = "False";

            echo '<script language="javascript">';
            echo 'alert("The username or password is not correct. Try again!")';
            echo '</script>';
        }else{
            session_start();                                    
            $_SESSION['isLoggedin'] = "TruE";                      
            $_SESSION['userName']  = $userName; 

            header("Location: index.php");       
        }
    }

    
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Login | books we have | Shahad Mahmud</title>
        

        <link rel="stylesheet" type="text/css" href="assets/css/index.css">
        <link rel="stylesheet" type="text/css" href="assets/css/sidemenu.css">
        <link rel="stylesheet" type="text/css" href="assets/css/formstyle.css">
    </head>
    <body>

        <div class="sidenav">
            <a href="index.php">Home</a>
            <a href="wishlist.php">Wish list</a>
            <a class="active" href="login.php">Login</a>
        </div>

        <div class="main">

            <form class="form_container" action="" method="post" enctype="multipart/form-data">
                <label for="user_name">User Name</label>
                <input type="text" id="user_name" name="user_name" value="" placeholder="User Name" required>

                <label for="user_pass">Password</label>
                <input type="password" id="user_pass" name="user_pass" value="" placeholder="Password" required>

                <input type="submit" name="submit" value="Submit">
            </form>

        </div>
    </body>
</html>
