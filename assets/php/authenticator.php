<?php
require_once 'connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'You are not logged in.']);
    exit();
}

// 2. جلب دور المستخدم من جدول الحسابات
$query = "
    SELECT role_id 
    FROM account 
    WHERE AccountID = :user_id AND Delete_Date IS NULL
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$userRole = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userRole) {
    echo json_encode(['error' => 'Invalid user.']);
    exit();
}

$roleId = $userRole['role_id'];

// 3. جلب الصلاحيات المرتبطة بالدور
$query = "
    SELECT permissions.name AS permission_name 
    FROM role_permissions
    JOIN permissions ON permissions.id = role_permissions.permission_id
    WHERE role_permissions.role_id = :role_id AND permissions.Delete_Date IS NULL
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':role_id', $roleId);
$stmt->execute();
$permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 4. تخزين الصلاحيات في الجلسة
$_SESSION['permissions'] = $permissions;

// 5. التحقق من وجود الصلاحيات
if (empty($_SESSION['permissions'])) {
    echo json_encode(['error' => 'No permissions found for this role.']);
    exit();
}

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

// الكود يتوقف هنا بعد تخزين الصلاحيات
?>
