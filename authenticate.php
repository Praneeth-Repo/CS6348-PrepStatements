<?php
session_start();

// Database connection
$mysqli = new mysqli("localhost", "root", "", "cs6314");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Sanitize and validate inputs
$userid = isset($_POST['userid']) ? trim($_POST['userid']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Simple validation
if (empty($userid) || empty($password)) {
    $_SESSION['error'] = "Please enter both User ID and Password.";
    header("Location: login.php");
    exit;
}

// Use prepared statement to prevent SQL injection
$stmt = $mysqli->prepare("SELECT * FROM user WHERE userid = ? AND password = ?");
$stmt->bind_param("ss", $userid, $password);
$stmt->execute();
$result = $stmt->get_result();

// Check login result
if ($result && $result->num_rows >= 1) {
    $user = $result->fetch_assoc();

    if ($user['user_status'] !== 'active') {
        $_SESSION['error'] = "Account is " . $user['user_status'];
        header("Location: login.php");
        exit;
    }

    $_SESSION['userid'] = $user['userid'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['session_id'] = session_id();

    // Use prepared statement for logging as well
    $log_stmt = $mysqli->prepare("INSERT INTO userlog (userid, session_id) VALUES (?, ?)");
    $log_stmt->bind_param("ss", $user['userid'], $_SESSION['session_id']);
    $log_stmt->execute();

    // Redirect based on user type
    if ($user['user_type'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: course_management.php");
    }
    exit;
} else {
    $_SESSION['error'] = "Invalid credentials.";
    header("Location: login.php");
    exit;
}
?>
