<?php
session_start();
require_once 'connection.php';
include 'authenticator.php';


if (isset($_POST['employee_name'])) {
    require 'mail_config.php'; // استدعاء ملف إعدادات البريد

    function generate_employee_id(PDO $conn)
    {
        $start_id = 225001;
        $end_id = 225999;

        for ($i = $start_id; $i <= $end_id; $i++) {
            $stmt = $conn->prepare("SELECT * FROM employee WHERE Employee_id = :employee_id");
            $stmt->bindParam(':employee_id', $i);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return $i;
            }
        }

        return null;
    }

    $response = [];
    $e_username = $_POST['username'];
    $e_password_plain = $_POST['password'];
    $e_password = password_hash($e_password_plain, PASSWORD_DEFAULT); // تشفير كلمة المرور
    $e_privilege = $_POST['account_rolls'];
    $employee_id = generate_employee_id($conn);
    $employee_fullname = $_POST['employee_name'];
    $employee_email = $_POST['employee_email'];
    $employee_phone = $_POST['employee_phone'];
    $employee_address = $_POST['employee_address'];
    $job_title = $_POST['job_titel'];
    $salary = $_POST['salary'];

    try {
        // إدخال البيانات في جدول employee
        $stmt = $conn->prepare("INSERT INTO employee (Employee_id, Employee_FullName, Employee_Email, Employee_Phone, Employee_Address, job_titel, Salary) VALUES (:employee_id, :employee_fullname, :employee_email, :employee_phone, :employee_address, :job_title, :salary)");
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':employee_fullname', $employee_fullname);
        $stmt->bindParam(':employee_email', $employee_email);
        $stmt->bindParam(':employee_phone', $employee_phone);
        $stmt->bindParam(':employee_address', $employee_address);
        $stmt->bindParam(':job_title', $job_title);
        $stmt->bindParam(':salary', $salary);

        $stmt->execute();

        // الحصول على AccountID الجديد
        $stmt = $conn->prepare("SELECT MAX(AccountID) AS max_account_id FROM account");
        $stmt->execute();
        $max_account_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_account_id'];
        $new_account_id = $max_account_id ? $max_account_id + 1 : 1;

        // إدخال البيانات في جدول account
        $stmt2 = $conn->prepare("INSERT INTO account (AccountID, UserName, UserPassword, privilege, Employee_id) VALUES (:account_id, :username, :password, :privilege, :employee_id)");
        $stmt2->bindParam(':account_id', $new_account_id);
        $stmt2->bindParam(':username', $e_username);
        $stmt2->bindParam(':password', $e_password);
        $stmt2->bindParam(':privilege', $e_privilege);
        $stmt2->bindParam(':employee_id', $employee_id);

        $stmt2->execute();

// إرسال البريد الإلكتروني
$mail = getMailerInstance(); // استدعاء إعدادات البريد
$mail->setFrom('information@dollar-ex.com', 'Dollar Exchange');
$mail->addAddress($employee_email, $employee_fullname);
$mail->CharSet = 'UTF-8'; // تعيين الترميز إلى UTF-8
$mail->Subject = mb_encode_mimeheader('تفاصيل حسابك في نظام شركة دولار للصرافة', 'UTF-8', 'B');
$mail->isHTML(true);

// إعداد محتوى البريد الإلكتروني
$mail->Body = "
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                line-height: 1.6;
                color: #333;
            }
            .email-container {
                max-width: 600px;
                margin: 20px auto;
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .email-header {
                background: #4CAF50;
                color: #ffffff;
                padding: 20px;
                text-align: center;
                font-size: 20px;
                font-weight: bold;
            }
            .email-body {
                padding: 20px;
            }
            .email-body h3 {
                color: #4CAF50;
                margin-bottom: 15px;
            }
            .email-body ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .email-body ul li {
                margin-bottom: 10px;
                padding: 10px;
                background: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            .email-body ul li strong {
                color: #4CAF50;
            }
            .email-footer {
                background: #f1f1f1;
                color: #777;
                text-align: center;
                padding: 15px;
                font-size: 12px;
                border-top: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
        <div class='email-container'>
            <div class='email-header'>
                تفاصيل حسابك في شركة دولار للصرافة
            </div>
            <div class='email-body'>
                <h3>مرحبًا، {$employee_fullname}</h3>
                <p>شكرًا لانضمامك إلى فريقنا. هذه هي تفاصيل حسابك المسجلة في نظام شركة دولار للصرافة:</p>
                <ul>
                    <li><strong>اسم المستخدم:</strong> {$e_username}</li>
                    <li><strong>كلمة المرور:</strong> {$e_password_plain}</li>
                    <li><strong>الصلاحيات:</strong> {$e_privilege}</li>
                    <li><strong>البريد الإلكتروني:</strong> {$employee_email}</li>
                    <li><strong>رقم الهاتف:</strong> {$employee_phone}</li>
                    <li><strong>العنوان:</strong> {$employee_address}</li>
                    <li><strong>المسمى الوظيفي:</strong> {$job_title}</li>
                    <li><strong>الراتب:</strong> {$salary}</li>
                </ul>
                <p style='color: red; font-weight: bold; margin-top: 20px;'>الرجاء الاحتفاظ بهذه البيانات وعدم مشاركتها مع أي شخص.</p>
            </div>
            <div class='email-footer'>
                <p>© 2024 شركة دولار للصرافة والحوالات المالية</p>
                <p>تم الإرسال بواسطة النظام الخاص بنا</p>
            </div>
        </div>
    </body>
    </html>
";

$mail->AltBody = "مرحبًا، {$employee_fullname}\n
شكرًا لانضمامك إلى فريقنا. هذه هي تفاصيل حسابك المسجلة في نظام شركة دولار للصرافة:\n
اسم المستخدم: {$e_username}\n
كلمة المرور: {$e_password_plain}\n
الصلاحيات: {$e_privilege}\n
البريد الإلكتروني: {$employee_email}\n
رقم الهاتف: {$employee_phone}\n
العنوان: {$employee_address}\n
المسمى الوظيفي: {$job_title}\n
الراتب: {$salary}\n
الرجاء الاحتفاظ بهذه البيانات وعدم مشاركتها مع أي شخص.";

// إرسال البريد

    $mail->send();
    $response['status'] = 'success';
    $response['message'] = 'Employee and account created successfully.';
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}


    echo json_encode($response);
}




if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // حذف المرفقات الخاصة بالموظف
    $dirPath = "../media/dollar/employee/" . $employee_id . "_*";

    foreach (glob($dirPath) as $filename) {
        if (is_file($filename)) {
            unlink($filename);
        }
    }

    // تحديث Delete_Date في جدول employee
    $query = $conn->prepare("UPDATE employee SET Delete_Date = NOW() WHERE Employee_id = :employee_id");
    $result = $query->execute([':employee_id' => $employee_id]);

    // تحديث Delete_Date في جدول account
    $queryAccount = $conn->prepare("UPDATE account SET Delete_Date = NOW() WHERE Employee_id = :employee_id");
    $resultAccount = $queryAccount->execute([':employee_id' => $employee_id]);

    if ($result && $resultAccount) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if (isset($_POST['employee_ids'])) {
    $employeeIds = json_decode($_POST['employee_ids'], true);

    // حذف المرفقات الخاصة بالموظفين
    foreach ($employeeIds as $employee_id) {
        $dirPath = "../media/dollar/employee/" . $employee_id . "_*";

        foreach (glob($dirPath) as $filename) {
            if (is_file($filename)) {
                unlink($filename);
            }
        }
    }

    // تحديث Delete_Date في جدول employee
    $placeholders = implode(',', array_fill(0, count($employeeIds), '?'));
    $stmt = $conn->prepare("UPDATE employee SET Delete_Date = NOW() WHERE Employee_id IN ($placeholders)");
    $stmt->execute($employeeIds);

    // تحديث Delete_Date في جدول account
    $stmtAccount = $conn->prepare("UPDATE account SET Delete_Date = NOW() WHERE Employee_id IN ($placeholders)");
    $stmtAccount->execute($employeeIds);

    if ($stmt->rowCount() > 0 && $stmtAccount->rowCount() > 0) {
        echo 'Success';
    } else {
        echo 'Failure';
    }
}




if (isset($_REQUEST['draw'])) {
    $draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
    $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
    $length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
    $search = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
    $order_column = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 0;
    $order_dir = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'asc';
    
    $columns = ['Employee_id', 'Employee_FullName', 'Employee_Address', 'Employee_Phone'];
    $order_column_name = $columns[$order_column];
    
    if ($search != '') {
        $query = $conn->prepare("SELECT COUNT(*) as total FROM employee WHERE (Employee_id LIKE :search OR Employee_FullName LIKE :search OR Employee_Address LIKE :search OR Employee_Phone LIKE :search) AND Delete_Date IS NULL");
        $query->execute([':search' => "%$search%"]);
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
    
        $query = $conn->prepare("SELECT * FROM employee WHERE (Employee_id LIKE :search OR Employee_FullName LIKE :search OR Employee_Address LIKE :search OR Employee_Phone LIKE :search) AND Delete_Date IS NULL ORDER BY $order_column_name $order_dir LIMIT $start, $length");
        $query->execute([':search' => "%$search%"]);
    } else {
        $query = $conn->prepare("SELECT COUNT(*) as total FROM employee WHERE Delete_Date IS NULL");
        $query->execute();
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
    
        $query = $conn->prepare("SELECT * FROM employee WHERE Delete_Date IS NULL ORDER BY $order_column_name $order_dir LIMIT $start, $length");
        $query->execute();
    }
    
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $result = array(
        "draw" => $draw,
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data
    );
    
    echo json_encode($result);    
}



if (isset($_GET['action']) && $_GET['action'] === "get_attachments") {
    function get_employee_attachments($employee_id, $upload_dir) {
        $attachments = [];
        $counter = 1;
        while (file_exists($upload_dir . $employee_id . '_' . $counter . '.jpg') ||
               file_exists($upload_dir . $employee_id . '_' . $counter . '.jpeg') ||
               file_exists($upload_dir . $employee_id . '_' . $counter . '.png') ||
               file_exists($upload_dir . $employee_id . '_' . $counter . '.gif') ||
               file_exists($upload_dir . $employee_id . '_' . $counter . '.pdf')) {
    
            if (file_exists($upload_dir . $employee_id . '_' . $counter . '.jpg')) {
                $attachments[] = 'assets/media/dollar/employee/' . $employee_id . '_' . $counter . '.jpg';
            } elseif (file_exists($upload_dir . $employee_id . '_' . $counter . '.jpeg')) {
                $attachments[] = 'assets/media/dollar/employee/' . $employee_id . '_' . $counter . '.jpeg';
            } elseif (file_exists($upload_dir . $employee_id . '_' . $counter . '.png')) {
                $attachments[] = 'assets/media/dollar/employee/' . $employee_id . '_' . $counter . '.png';
            } elseif (file_exists($upload_dir . $employee_id . '_' . $counter . '.gif')) {
                $attachments[] = 'assets/media/dollar/employee/' . $employee_id . '_' . $counter . '.gif';
            } elseif (file_exists($upload_dir . $employee_id . '_' . $counter . '.pdf')) {
                $attachments[] = 'assets/media/dollar/employee/' . $employee_id . '_' . $counter . '.pdf';
            }
    
            $counter++;
        }
    
        return $attachments;
    }
header('Content-Type: application/json');

$employee_id = $_GET['employee_id'];

$upload_dir = realpath(__DIR__ . '/../media/dollar/employee/') . '/';

$attachments = get_employee_attachments($employee_id, $upload_dir);

echo json_encode($attachments);

}



?>