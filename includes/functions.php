<?php
ob_start();
require_once 'config.php';

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function getBloodGroups() {
    global $conn;
    $result = $conn->query("SELECT * FROM blood_groups ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getCities() {
    global $conn;
    $result = $conn->query("SELECT DISTINCT city FROM donors WHERE city != '' ORDER BY city");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function displayAlert($message, $type = 'info') {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">' . $message . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}