<?php
session_start();
require 'connection.php';
include 'authenticator.php';

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

if ($action === "load_data") {

header('Content-Type: application/json');

if(!isset($_SESSION['employee_id'])){
    echo json_encode(['error' => 'Session employee_id not set.']);
    exit();
}

try {
    $stmt = $conn->prepare("SELECT * FROM employee WHERE Employee_id = :id");
    $stmt->execute(['id' => $_SESSION['employee_id']]);
    $employeeData = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$employeeData) {
        echo json_encode(['error' => 'No data found for provided employee_id.']);
        exit();
    }

    echo json_encode($employeeData);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

}
if ($action === "get_contact") {
    $response = [];
    try {

        // التحقق من وجود معرّف الموظف في المتغيرات الجلسية
        if (isset($_SESSION['employee_id'])) {
            $employeeId = $_SESSION['employee_id'];
            $stmt = $conn->prepare("SELECT Employee_id, Employee_FullName, Employee_Email, avatar_path ,role_id FROM employee WHERE Employee_id != :employeeId AND Delete_Date IS NULL");
            $stmt->bindParam(':employeeId', $employeeId, PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $response['error'] = "Employee ID not set in session.";
        }
    } catch (PDOException $e) {
        $response['error'] = "Database error: " . $e->getMessage();
    }
    echo json_encode($response);
}


?>
