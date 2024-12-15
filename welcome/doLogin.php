<?php
// ملف /welcome/doLogin.php
session_start();  // بداية الجلسة

include '../assets/php/connection.php';

$enteredUsername = $_POST['username'];
$enteredPassword = $_POST['password'];

$sql = "SELECT AccountID, UserName, privilege, UserPassword , Employee_id FROM account WHERE UserName = ?";
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
    $_SESSION['role'] = $user['privilege'];
    $_SESSION['employee_id'] = $user['Employee_id'];
    $_SESSION['last_activity'] = time();

    header('Location: ../dashboard.php');
  } else {
    // كلمة المرور غير صحيحة، قم بتخزين رسالة الخطأ وإعادة توجيه المستخدم
    $_SESSION['error'] = 'كلمة السر أو اسم المستخدم خاطئ.';
    header('Location: ../index.php');
  }
} else {
  // اسم المستخدم غير صحيح، قم بتخزين رسالة الخطأ وإعادة توجيه المستخدم
  $_SESSION['error'] = 'كلمة السر أو اسم المستخدم خاطئ.';
  header('Location: ../index.php');
}

$conn = null;
?>