<?php
// api/auth.php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $name = sanitizeInput($_POST['name']);
        $mobile = sanitizeInput($_POST['mobile']);
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, mobile_number, email, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $mobile, $email, $hash]);
            $_SESSION['success'] = "Registration successful. Please login.";
            redirect('/index.php');
        } catch(PDOException $e) {
            $_SESSION['error'] = "Registration failed: " . $e->getMessage();
            redirect('/register.php');
        }
    }
    
    if (isset($_POST['login'])) {
        $email = sanitizeInput($_POST['email']);
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT id, password_hash, name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            redirect('/dashboard.php');
        } else {
            $_SESSION['error'] = "Invalid credentials.";
            redirect('/index.php');
        }
    }
    
    if (isset($_POST['admin_login'])) {
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT id, password_hash FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            redirect('/admin/dashboard.php');
        } else {
            $_SESSION['error'] = "Invalid admin credentials.";
            redirect('/admin/index.php');
        }
    }
}

if ($action === 'logout') {
    session_destroy();
    session_start();
    $_SESSION['success'] = "Logged out successfully.";
    redirect('/index.php');
}

if ($action === 'admin_logout') {
    session_destroy();
    session_start();
    $_SESSION['success'] = "Admin logged out.";
    redirect('/admin/index.php');
}
?>
