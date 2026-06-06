<?php
// includes/functions.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if normal user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

define('BASE_URL', '/Sakhi SOS');

// Redirect utility
function redirect($url) {
    if (strpos($url, '/') === 0) {
        header("Location: " . BASE_URL . $url);
    } else {
        header("Location: " . $url);
    }
    exit;
}

// Sanitize user inputs for security
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Ensure the user is logged in, redirect otherwise
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php');
    }
}

// Ensure admin is logged in, redirect otherwise
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        redirect('index.php');
    }
}
