<?php
$link = mysqli_connect('localhost', 'your_db_user', 'your_db_password', 'your_db_name');
    die('Connection failed: ' . mysqli_connect_error());
}
echo 'MySQL connection successful';
?>
