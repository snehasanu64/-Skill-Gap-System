<?php
session_start();

// Database Config
$host     = 'localhost';
$dbname   = 'skill_gap_db';
$db_user  = 'root';
$db_pass  = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $db_user, $db_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("<h3 style='color:red;font-family:sans-serif;padding:2rem;'>
        Database Error: " . $e->getMessage() . "<br><br>
        Make sure WAMP is running and you have imported <b>database.sql</b>
    </h3>");
}


// Helper Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: dashboard.php');
        exit;
    }
}

function redirect($url) {
    header("Location: $url");
    exit;
}
?>
