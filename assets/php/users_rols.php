<?php
session_start();
require_once 'connection.php';
include 'authenticator.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($action === "listing_users") {
    $draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
    $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
    $length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
    $search = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
    $order_column = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 0;
    $order_dir = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'asc';

    $columns = ['Employee_id', 'CreatedDate', 'Last_login', 'role_name', 'Employee_FullName', 'Employee_Email', 'avatar_path'];
    $order_column_name = $columns[$order_column];
    $params = [];
    $search_cond = 'WHERE a.Delete_Date IS NULL';

    if ($search != '') {
        $search_cond .= " AND (e.Employee_FullName LIKE :search OR e.Employee_Email LIKE :search OR r.name LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // استعلام لحساب العدد الكلي
    $query = $conn->prepare("SELECT COUNT(*) as total FROM account a
        LEFT JOIN employee e ON e.Employee_id = a.Employee_id
        LEFT JOIN roles r ON r.id = a.role_id
        $search_cond");
    $query->execute($params);
    $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

    // جلب البيانات المطلوبة
    $query = $conn->prepare("
        SELECT 
            a.Employee_id,
            a.CreatedDate,
            a.Last_login,
            a.role_id,
            e.Employee_FullName,
            e.Employee_Email,
            e.avatar_path,
            r.name AS role_name
        FROM account a
        LEFT JOIN employee e ON e.Employee_id = a.Employee_id
        LEFT JOIN roles r ON r.id = a.role_id
        $search_cond
        ORDER BY $order_column_name $order_dir
        LIMIT $start, $length
    ");
    $query->execute($params);

    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    $result = array(
        "draw" => $draw,
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data
    );

    echo json_encode($result);
}





?>
