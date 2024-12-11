<?php
$host = 'localhost'; 
$username = 'shangealone'; // username mo sa xamp
$password = 'shangealone'; // password mo sa xamp
$database = 'members_shangealone'; // name nung data base mo


$dbcon = mysqli_connect($host, $username, $password, $database);
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}
    