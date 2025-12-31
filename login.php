<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
require_once 'connection.php';

function validatePassword($password) {
    if (strlen($password) < 5) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[!@#$%^&*]/', $password)) return false;
    return true;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Basic validation
    if (strlen($username) < 5 || !validatePassword($password)) {
        echo json_encode(['success' => false, 'message' => '❌ Username 5+ chars & Password: 5+ chars + 1 UPPER + 1 NUMBER + 1 SPECIAL']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            echo json_encode(['success' => true, 'message' => 'Login successful!']);
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Wrong password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => '❌ User not found. Please signup first.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '❌ Please fill all fields']);
}
?>

