<?php
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
require_once 'connection.php';

function validatePassword($password) {
    if (strlen($password) < 5) return "Password must be 5+ characters";
    if (!preg_match('/[A-Z]/', $password)) return "Password needs 1 UPPERCASE (A-Z)";
    if (!preg_match('/[0-9]/', $password)) return "Password needs 1 NUMBER (0-9)";
    if (!preg_match('/[!@#$%^&*]/', $password)) return "Password needs 1 SPECIAL (!@#$%^&*)";
    return true;
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Validate username
    if (strlen($username) < 5) {
        echo '<div style="color:#ef4444;font-weight:700">❌ Username must be 5+ characters</div>';
        exit;
    }
    
    // Validate password
    $passCheck = validatePassword($password);
    if ($passCheck !== true) {
        echo '<div style="color:#ef4444;font-weight:700">❌ ' . $passCheck . '</div>';
        exit;
    }
    
    // Check if user exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        echo '<div style="color:#ef4444;font-weight:700">❌ Username already exists! Please login.</div>';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);
        
        if ($stmt->execute()) {
            echo '<div style="color:#10b981;font-weight:700">✅ Account created! Now please login.</div>';
        } else {
            echo '<div style="color:#ef4444;font-weight:700">❌ Signup failed. Try again.</div>';
        }
    }
} else {
    echo '<div style="color:#ef4444;font-weight:700">❌ Please fill all fields</div>';
}
?>


