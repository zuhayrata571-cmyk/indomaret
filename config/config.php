<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "indomaret";

$conn = mysqli_connect($host, $user, $password, $database)
or die("could not connect to the database: ". mysqli_connect_error());

var_dump($conn);