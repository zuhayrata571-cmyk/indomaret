
<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "indomaret_rpl4";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name)
    or die("Could not connect to database: " . mysqli_connect_error());

?>
