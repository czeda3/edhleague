<?php
$hostname = "localhost"; //localhost
$dbname = "nnvrbxyv_donblackeDB";
$username = "nnvrbxyv_dbadmin";
$password = "aQcluj6(^O(3";

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed". mysqli_connect_error());
}

