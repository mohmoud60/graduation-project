<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'You are not logged in.']);
    exit();
}

// Fetch user role from the database
$stmt = $conn->prepare("SELECT privilege FROM account WHERE AccountID = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$userRole = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userRole) {
    echo json_encode(['error' => 'Invalid user.']);
    exit();
}

// 2. Check user's role permission
if ($userRole['privilege'] !== 'Admin' && $userRole['privilege'] !== 'service' && $userRole['privilege'] !== 'accountant' && $userRole['privilege'] !== 'Accounting') {
    echo json_encode(['error' => 'You do not have permission to perform this action.']);
    exit();
}

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

?>