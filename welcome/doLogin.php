<?php
// ملف /welcome/doLogin.php
session_start();  // بداية الجلسة

include '../assets/php/connection.php';

$enteredUsername = $_POST['username'];
$enteredPassword = $_POST['password'];

$sql = "SELECT AccountID, UserName, role_id, UserPassword, Employee_id FROM account WHERE UserName = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$enteredUsername]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $hashedPassword = $user['UserPassword'];

    if (password_verify($enteredPassword, $hashedPassword)) {
        // نجاح تسجيل الدخول، قم بإعادة توجيه المستخدم إلى الداشبورد
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['AccountID'];
        $_SESSION['username'] = $user['UserName'];
        $_SESSION['role'] = $user['role_id'];
        $_SESSION['employee_id'] = $user['Employee_id'];
        $_SESSION['last_activity'] = time();

        // تحديث وقت آخر تسجيل دخول
        $updateSql = "UPDATE account SET Last_login = CURRENT_TIMESTAMP WHERE AccountID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->execute([$user['AccountID']]);

        // إعادة التوجيه إلى الصفحة الرئيسية
        header('Location: ../dashboard.php');
        exit();
    } else {
        // كلمة المرور غير صحيحة
        $_SESSION['error'] = 'كلمة السر أو اسم المستخدم خاطئ.';
        header('Location: ../index.php');
        exit();
    }
} else {
    // اسم المستخدم غير صحيح
    $_SESSION['error'] = 'كلمة السر أو اسم المستخدم خاطئ.';
    header('Location: ../index.php');
    exit();
}

$conn = null;
?>
