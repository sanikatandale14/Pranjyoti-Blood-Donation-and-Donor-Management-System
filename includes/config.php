<?php
session_start();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'pranjyoti_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

define('SITE_NAME', 'Pranjyoti - Help us save a life with the drop of your blood');
define('BASE_URL', 'http://localhost:8081/pranjyoti/');