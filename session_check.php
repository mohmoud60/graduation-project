<?php
session_start();

// التحقق من وقت النشاط الأخير (انتهاء الجلسة)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    // آخر طلب كان منذ أكثر من 30 دقيقة
    session_unset(); 
    session_destroy(); 
    header("Location: index.php");
    exit();
}

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
} else {
    // تحديث وقت النشاط الأخير
    $_SESSION['last_activity'] = time();
}

// التحقق من الصلاحيات للدخول إلى الصفحة الحالية
if (isset($required_permissions)) { // التحقق من وجود قائمة الصلاحيات
    if (!isset($_SESSION['permissions']) || !array_intersect($required_permissions, $_SESSION['permissions'])) {
        // إذا لم تتطابق أي صلاحية، إعادة التوجيه إلى صفحة "غير متاح"
        header("Location: unavailable.php");
        exit();
    }
}



?>
