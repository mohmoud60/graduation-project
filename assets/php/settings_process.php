<?php
// Include database connection file
include 'connection.php';
include 'authenticator.php';

// Get current logo
$sql = "SELECT whatsApp_logo FROM settings WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();
$currentLogo = $result['whatsApp_logo'];

// Extract the extension of the current logo
$currentLogoExt = pathinfo($currentLogo, PATHINFO_EXTENSION);

// Check if file was uploaded
if(isset($_FILES['whatsAppLogo'])){
    $errors= array();
    $file_name = $_FILES['whatsAppLogo']['name'];
    $file_size =$_FILES['whatsAppLogo']['size'];
    $file_tmp =$_FILES['whatsAppLogo']['tmp_name'];
    $file_type=$_FILES['whatsAppLogo']['type'];
    $file_name_exploded = explode('.', $file_name);
    $file_ext = strtolower(end($file_name_exploded));

    // Check file extension
    $extensions= array("jpeg","jpg","png");

    if(in_array($file_ext,$extensions)=== false){
        $errors[]="extension not allowed, please choose a JPEG or PNG file.";
    }

    // Check file size
    if($file_size > 2097152){
        $errors[]='File size must be less than 2 MB';
    }

    // Delete the current logo file if it exists
    if(file_exists("../media/dollar/Whatsapp_code." . $currentLogoExt)){
        unlink("../media/dollar/Whatsapp_code." . $currentLogoExt);
    }

    // Change the name of the file to "logo"
    $file_name = "Whatsapp_code." . $file_ext;

    // Upload file to server
    if(empty($errors)==true){
        move_uploaded_file($file_tmp,"../media/dollar/".$file_name);
        $logo = "assets/media/dollar/".$file_name;  // Set new logo path if file uploaded successfully
    }else{
        $response = ['success' => false, 'message' => implode(' ', $errors)];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }    
}else{
    $logo = $currentLogo;  // Use current logo path if no file uploaded
}
// Save data to database
if(isset($_POST['timeZone']) && isset($_POST['dateFormat']) && isset($_POST['timeFormat']) && isset($_POST['financialYearStart']) && isset($_POST['exchange_rate_sub'])){
    $timeZone = $_POST['timeZone'];
    $dateFormat = $_POST['dateFormat'];
    $timeFormat = $_POST['timeFormat'];
    $fiscalYearStart = $_POST['financialYearStart'];
    $exchange_rate_sub = $_POST['exchange_rate_sub'];

    $sql = "INSERT INTO settings (id, time_zone, date_format, time_format, fiscal_year_start, whatsApp_logo , exchange_rate_sub) VALUES (?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE time_zone = VALUES(time_zone), date_format = VALUES(date_format), time_format = VALUES(time_format), fiscal_year_start = VALUES(fiscal_year_start), whatsApp_logo = VALUES(whatsApp_logo) , exchange_rate_sub = VALUES(exchange_rate_sub)";
    $stmt= $conn->prepare($sql);
    $stmt->execute([1, $timeZone, $dateFormat, $timeFormat, $fiscalYearStart, $logo , $exchange_rate_sub]);

    $response = ['success' => true, 'message' => 'تم تحديث الإعدادات بنجاح'];
}else{
    $response = ['success' => false, 'message' => 'يرجى تعبئة جميع الحقول'];
}

header('Content-Type: application/json');
echo json_encode($response);
    
    ?>

