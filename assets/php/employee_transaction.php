<?php

include 'connection.php';
include 'time_settings.php';
include 'authenticator.php';


$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

if ($action === "pay_advance") {
    $id = $_POST['id'];
    $advance = $_POST['advance'];
    $transaction_type = 'Advances';
    if ($advance <= 0) {
        echo json_encode(['error' => 'The advance must be a positive number.']);
        exit;
    }
        
        try {
            $conn->beginTransaction();
            
            // Deduct the advance from the employee's salary
            $stmt = $conn->prepare('UPDATE employee SET loan = loan + :advance WHERE Employee_id = :id');
            $stmt->execute(['advance' => $advance, 'id' => $id]);
            
            // Deduct the advance from the fund
            $stmt = $conn->prepare('UPDATE accounting SET account_amount = account_amount - :advance WHERE account_number = 10201');
            $stmt->execute(['advance' => $advance]);
            
            // Record the advance
            $stmt = $conn->prepare('INSERT INTO employee_transactions (employee_id, amount , transaction_type , date) VALUES (:id, :advance, :transaction_type , NOW())');
            $stmt->execute(['advance' => $advance, 'id' => $id, 'transaction_type' => $transaction_type]);
            
            $conn->commit();
    
            echo "success";
            exit;
        } catch (connException $e) {
            $conn->rollBack();
            echo "error";
            exit;
        }
    }

    if ($action === "update_profile") {
        require 'mail_config.php'; // استدعاء إعدادات البريد
    
        $employee_id = $_POST['employee_id'];
        $employee_fullname = $_POST['full_name'];
        $employee_email = $_POST['Employee_Email'];
        $employee_phone = $_POST['employee_phone'];
        $employee_address = $_POST['employee_address'];
        $job_title = $_POST['job_title'];
        $salary = $_POST['basic_salary'];
    
        // تحديث بيانات الموظف
        $stmt = $conn->prepare("
            UPDATE employee 
            SET 
                Employee_FullName = :employee_fullname, 
                Employee_Email = :employee_email, 
                Employee_Phone = :employee_phone, 
                Employee_Address = :employee_address, 
                job_titel = :job_title, 
                Salary = :salary 
            WHERE Employee_id = :employee_id
        ");
        $stmt->bindParam(':employee_fullname', $employee_fullname);
        $stmt->bindParam(':employee_email', $employee_email);
        $stmt->bindParam(':employee_phone', $employee_phone);
        $stmt->bindParam(':employee_address', $employee_address);
        $stmt->bindParam(':job_title', $job_title);
        $stmt->bindParam(':salary', $salary);
        $stmt->bindParam(':employee_id', $employee_id);
    
        if ($stmt->execute()) {
            echo "success";
    
// إرسال رسالة عبر البريد الإلكتروني
try {
    $mail = getMailerInstance(); // الحصول على إعدادات البريد
    $mail->setFrom('information@dollar-ex.com', 'Dollar Exchange');
    $mail->addAddress($employee_email, $employee_fullname); // بريد الموظف

    // إعداد الترميز وعنوان البريد
    $mail->CharSet = 'UTF-8'; // تعيين الترميز إلى UTF-8
    $mail->Subject = mb_encode_mimeheader('تم تحديث بياناتك في نظام شركة دولار للصرافة', 'UTF-8', 'B');

    // إعداد محتوى البريد الإلكتروني
    $mail->isHTML(true);
    $mail->Body = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    color: #333;
                    line-height: 1.6;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    width: 100%;
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }
                .email-header {
                    background-color: #4CAF50;
                    color: #fff;
                    text-align: center;
                    padding: 15px 20px;
                }
                .email-body {
                    padding: 20px;
                }
                .email-footer {
                    text-align: center;
                    background-color: #f1f1f1;
                    padding: 10px;
                    font-size: 12px;
                    color: #777;
                }
                .info-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .info-table th, .info-table td {
                    padding: 10px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                .info-table th {
                    background-color: #f4f4f4;
                    color: #555;
                }
                .highlight {
                    color: #4CAF50;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>تم تحديث بياناتك</h1>
                    <p>شركة دولار للصرافة والحوالات المالية</p>
                </div>
                <div class='email-body'>
                    <h3>مرحبًا، <span class='highlight'>{$employee_fullname}</span></h3>
                    <p>تم تحديث بياناتك في نظام شركة دولار للصرافة. التفاصيل المحدثة كالتالي:</p>
                    <table class='info-table'>
                        <tr>
                            <th>اسم الموظف</th>
                            <td>{$employee_fullname}</td>
                        </tr>
                        <tr>
                            <th>البريد الإلكتروني</th>
                            <td>{$employee_email}</td>
                        </tr>
                        <tr>
                            <th>رقم الهاتف</th>
                            <td>{$employee_phone}</td>
                        </tr>
                        <tr>
                            <th>العنوان</th>
                            <td>{$employee_address}</td>
                        </tr>
                        <tr>
                            <th>المسمى الوظيفي</th>
                            <td>{$job_title}</td>
                        </tr>
                        <tr>
                            <th>الراتب</th>
                            <td>{$salary}</td>
                        </tr>
                    </table>
                    <p style='margin-top: 20px;'>إذا كانت هناك مشكلة، يرجى التواصل مع فريق الدعم.</p>
                </div>
                <div class='email-footer'>
                    <p>© 2024 شركة دولار للصرافة والحوالات المالية</p>
                    <p>هذا البريد يحتوي على معلومات سرية. يرجى الاحتفاظ بها وعدم مشاركتها.</p>
                </div>
            </div>
        </body>
        </html>
    ";

    $mail->AltBody = "مرحبًا، {$employee_fullname}\n
    تم تحديث بياناتك في نظام شركة دولار للصرافة. التفاصيل المحدثة كالتالي:\n
    اسم الموظف: {$employee_fullname}\n
    البريد الإلكتروني: {$employee_email}\n
    رقم الهاتف: {$employee_phone}\n
    العنوان: {$employee_address}\n
    المسمى الوظيفي: {$job_title}\n
    الراتب: {$salary}\n
    إذا كانت هناك مشكلة، يرجى التواصل مع فريق الدعم.";

    // إرسال البريد
    $mail->send();
    echo "تم إرسال البريد بنجاح.";
} catch (Exception $e) {
    echo "خطأ في إرسال البريد: " . $e->getMessage();
}

        } else {
            echo "error";
        }
    
        // معالجة الملفات الجديدة
        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $upload_dir = '../media/dollar/employee/';
            $existing_attachments = glob($upload_dir . $employee_id . '_*');
            $highest_counter = 0;
    
            foreach ($existing_attachments as $existing_attachment) {
                preg_match('/' . $employee_id . '_(\d+)/', $existing_attachment, $matches);
                if (isset($matches[1]) && (int)$matches[1] > $highest_counter) {
                    $highest_counter = (int)$matches[1];
                }
            }
    
            $attachment_count = $highest_counter + 1;
            foreach ($_FILES['attachments']['name'] as $index => $name) {
                if ($_FILES['attachments']['size'][$index] > 0) {
                    $file_extension = pathinfo($name, PATHINFO_EXTENSION);
                    $file_name = $employee_id . '_' . $attachment_count . '.' . $file_extension;
                    $target_file = $upload_dir . $file_name;
    
                    if (move_uploaded_file($_FILES['attachments']['tmp_name'][$index], $target_file)) {
                        echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                    $attachment_count++;
                }
            }
        }
    }
    

    if ($action === "update_password") {
        $response = [];
        require 'mail_config.php';
    
        $employee_id = $_POST['employee_id'];
        $new_password = $_POST['newpassword'];
    
        try {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
            $update_stmt = $conn->prepare("UPDATE account SET UserPassword = :new_password WHERE Employee_id = :employee_id");
            $update_stmt->bindParam(':new_password', $new_hashed_password);
            $update_stmt->bindParam(':employee_id', $employee_id);
    
            if ($update_stmt->execute()) {
                $employee_stmt = $conn->prepare("SELECT Employee_FullName, Employee_Email FROM employee WHERE Employee_id = :employee_id");
                $employee_stmt->bindParam(':employee_id', $employee_id);
                $employee_stmt->execute();
                $employee = $employee_stmt->fetch(PDO::FETCH_ASSOC);
    
                $full_name = $employee['Employee_FullName'];
                $employee_email = $employee['Employee_Email'];
    
                try {
                    $mail = getMailerInstance();
                    $mail->setFrom('information@dollar-ex.com', 'Dollar Exchange');
                    $mail->addAddress($employee_email, $full_name);
    
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = mb_encode_mimeheader("إشعار: تحديث كلمة المرور الخاصة بك", 'UTF-8', 'B');
                    $mail->isHTML(true);
                    $mail->Body = "
                        <html>
                        <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                            <div style='max-width: 600px; margin: auto; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px;'>
                                <h2 style='color: #4CAF50; text-align: center;'>إشعار تحديث كلمة المرور</h2>
                                <p style='font-size: 16px;'>مرحبًا <strong>{$full_name}</strong>,</p>
                                <p style='font-size: 16px; line-height: 1.6;'>
                                    نود إعلامك بأنه تم تحديث كلمة المرور الخاصة بك في نظام <strong>شركة دولار للصرافة</strong>.
                                    يرجى استخدام كلمة المرور الجديدة أدناه لتسجيل الدخول إلى حسابك:
                                </p>
                                <div style='background: #f9f9f9; border: 1px dashed #ddd; padding: 10px; text-align: center; font-size: 18px; font-weight: bold;'>
                                    كلمة المرور الجديدة: <span style='color: #4CAF50;'>{$new_password}</span>
                                </div>
                                <p style='font-size: 16px; line-height: 1.6;'>
                                    هذا التغيير تم بواسطة <strong>مدير النظام</strong>. إذا كنت تعتقد أن هذا التحديث غير مصرح به، يرجى التواصل مع فريق الدعم فورًا.
                                </p>
                                <hr style='margin: 20px 0;'>
                                <p style='text-align: center; font-size: 14px; color: #888;'>
                                    <strong>شركة دولار للصرافة والحوالات المالية</strong><br>
                                    جميع الحقوق محفوظة © 2024
                                </p>
                            </div>
                        </body>
                        </html>
                    ";
                    $mail->AltBody = "
    مرحبًا {$full_name},
    تم تحديث كلمة المرور الخاصة بك في نظام شركة دولار للصرافة.
    كلمة المرور الجديدة: {$new_password}
    
    هذا التحديث تم بواسطة مدير النظام. إذا كنت تعتقد أن هذا التحديث غير مصرح به، يرجى التواصل مع فريق الدعم فورًا.
    شركة دولار للصرافة والحوالات المالية.
                    ";
    
                    $mail->send();
                } catch (Exception $e) {
                    $response['email_error'] = "خطأ في إرسال البريد: " . $mail->ErrorInfo;
                }
    
                $response['status'] = 'success';
                $response['message'] = 'تم تحديث كلمة المرور بنجاح.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'فشل تحديث كلمة المرور.';
            }
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = 'حدث خطأ في النظام: ' . $e->getMessage();
        }
    
        echo json_encode($response);
    }
    

    if ($action === "updatecupassword") {
        $response = []; // لتخزين حالة الاستجابة
        require 'mail_config.php';
        // المدخلات القادمة من الطلب
        $employee_id = $_POST['employee_id']; // معرف الموظف
        $current_password = $_POST['currentpassword']; // كلمة المرور الحالية
        $new_password = $_POST['newpassword']; // كلمة المرور الجديدة
    
        try {
            // التحقق من كلمة المرور الحالية
            $stmt = $conn->prepare("SELECT UserPassword, UserName FROM account WHERE Employee_id = :employee_id");
            $stmt->bindParam(':employee_id', $employee_id);
            $stmt->execute();
    
            if ($stmt->rowCount() === 0) {
                $response['status'] = 'error';
                $response['message'] = 'الموظف غير موجود.';
            } else {
                $account = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashed_password = $account['UserPassword']; // كلمة المرور المشفرة
    
                if (password_verify($current_password, $hashed_password)) {
                    // إذا كانت كلمة المرور الحالية مطابقة، نقوم بتحديث كلمة المرور
                    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_stmt = $conn->prepare("UPDATE account SET UserPassword = :new_password WHERE Employee_id = :employee_id");
                    $update_stmt->bindParam(':new_password', $new_hashed_password);
                    $update_stmt->bindParam(':employee_id', $employee_id);
    
                    if ($update_stmt->execute()) {
                        // جلب بيانات الموظف لإرسال البريد
                        $employee_stmt = $conn->prepare("SELECT Employee_FullName, Employee_Email FROM employee WHERE Employee_id = :employee_id");
                        $employee_stmt->bindParam(':employee_id', $employee_id);
                        $employee_stmt->execute();
                        $employee = $employee_stmt->fetch(PDO::FETCH_ASSOC);
    
                        $full_name = $employee['Employee_FullName'];
                        $employee_email = $employee['Employee_Email'];
    
                        // إرسال رسالة التأكيد بالبريد الإلكتروني
                        try {
                            $mail = getMailerInstance();
                            $mail->setFrom('information@dollar-ex.com', 'Dollar Exchange');
                            $mail->addAddress($employee_email, $full_name);
    
                            // إعداد محتوى البريد الإلكتروني
                            $mail->CharSet = 'UTF-8';
                            $mail->Subject = mb_encode_mimeheader("تم تحديث كلمة المرور بنجاح", 'UTF-8', 'B');
                            $mail->isHTML(true);
                            $mail->Body = "
                               <html>
                        <body style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;'>
                            <div style='max-width: 600px; margin: auto; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px;'>
                                <h2 style='color: #4CAF50; text-align: center;'>إشعار تحديث كلمة المرور</h2>
                                <p style='font-size: 16px;'>مرحبًا <strong>{$full_name}</strong>,</p>
                                <p style='font-size: 16px; line-height: 1.6;'>
                                    نود إعلامك بأنه تم تحديث كلمة المرور الخاصة بك في نظام <strong>شركة دولار للصرافة</strong>.
                                    يرجى استخدام كلمة المرور الجديدة أدناه لتسجيل الدخول إلى حسابك:
                                </p>
                                <div style='background: #f9f9f9; border: 1px dashed #ddd; padding: 10px; text-align: center; font-size: 18px; font-weight: bold;'>
                                    كلمة المرور الجديدة: <span style='color: #4CAF50;'>{$new_password}</span>
                                </div>
                                <p style='font-size: 16px; line-height: 1.6;'>
                                    هذا التغيير تم بواسطة <strong>مدير النظام</strong>. إذا كنت تعتقد أن هذا التحديث غير مصرح به، يرجى التواصل مع فريق الدعم فورًا.
                                </p>
                                <hr style='margin: 20px 0;'>
                                <p style='text-align: center; font-size: 14px; color: #888;'>
                                    <strong>شركة دولار للصرافة والحوالات المالية</strong><br>
                                    جميع الحقوق محفوظة © 2024
                                </p>
                            </div>
                        </body>
                        </html>
                            ";
                            $mail->AltBody = "مرحبًا، {$full_name}\n
                            تم تغيير كلمة المرور الخاصة بك بنجاح في نظام شركة دولار للصرافة.\n
                            إذا لم تطلب هذا التغيير، يرجى التواصل مع فريق الدعم فورًا.\n
                            شركة دولار للصرافة والحوالات المالية - توقيع إلكتروني.";
    
                            $mail->send();
                        } catch (Exception $e) {
                            $response['email_error'] = "خطأ في إرسال البريد: " . $mail->ErrorInfo;
                        }
    
                        $response['status'] = 'success';
                        $response['message'] = 'تم تحديث كلمة المرور بنجاح.';
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = 'فشل تحديث كلمة المرور.';
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'كلمة المرور الحالية غير صحيحة.';
                }
            }
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = 'حدث خطأ في النظام: ' . $e->getMessage();
        }
    
        // عرض النتيجة للمستخدم
        echo json_encode($response);
    }

    if ($action === "show_report") {
        $employee_id = $_POST['employee_id'];
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];
    
        $stmt = $conn->prepare("SELECT * FROM employee_transactions WHERE employee_id = :employee_id AND date BETWEEN :from_date AND :to_date");
        $stmt->bindParam(':employee_id', $employee_id);
        $stmt->bindParam(':from_date', $from_date);
        $stmt->bindParam(':to_date', $to_date);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
         $settings = getSettings($conn);

        foreach($result as &$row) {
            $formattedDateTime = formatDateAndTime($row['date'], $settings);
            $row['date'] = $formattedDateTime['date'];
            $row['time'] = $formattedDateTime['time'];
        }
    
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    if ($action === "get_employees") {
        $stmt = $conn->prepare("SELECT Employee_id , Employee_FullName , Salary , loan , salary_paid FROM employee");
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($employees);
    }

    if ($action === "pay_salary") {
        $employee_id = $_POST['employee_id'];
        $amount = $_POST['amount'];
        $transaction_type = $_POST['transaction_type'];
        header('Content-Type: application/json');

        try {
            // بدء العملية
            $conn->beginTransaction();
    
            // تحديث جدول employee
            $sql = "UPDATE employee SET salary_paid = 1 , loan = 0 WHERE Employee_id = :employee_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Deduct the advance from the fund
            $stmt = $conn->prepare('UPDATE accounting SET account_amount = account_amount - :amount WHERE account_number = 10201');
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->execute();


            // إضافة عملية جديدة في جدول employee_transactions
            $sql = "INSERT INTO employee_transactions (employee_id, amount, transaction_type) VALUES (:employee_id, :amount, :transaction_type)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindParam(':transaction_type', $transaction_type, PDO::PARAM_STR);
            $stmt->execute();
    
            // إتمام العملية
            $conn->commit();
    
            // إرجاع رسالة نجاح
            echo json_encode(['success' => true]);
    
        } catch(PDOException $e) {
            // إلغاء العملية في حالة حدوث خطأ
            $conn->rollBack();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    

?>