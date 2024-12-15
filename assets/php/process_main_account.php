<?php
session_start();
require_once 'connection.php';
include 'authenticator.php';

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;


if ($action === "add_account") {
    try {
        // التحقق من المدخلات
        if (empty($_POST['account_Sname']) || empty($_POST['currency_id']) || empty($_POST['type_id'])) {
            throw new Exception('Missing required fields');
        }

        $account_Sname = trim($_POST['account_Sname']);
        $currency_id = trim($_POST['currency_id']);
        $account_type = (int)$_POST['type_id'];

        if (!in_array($account_type, [3000, 3100, 3200])) {
            throw new Exception('Invalid account type');
        }

        // تحديد بادئة الكود وقيم البداية حسب نوع الحساب
        $prefix = '';
        $start_sequence = 10000;
        switch ($account_type) {
            case 3000:
                $prefix = 'main_';
                $start_sequence = 10100;
                break;
            case 3100:
                $prefix = 'sub_';
                $start_sequence = 10200;
                break;
            case 3200:
                $prefix = 'income_';
                $start_sequence = 10300;
                break;
        }

        // جلب أكبر رقم حساب بناءً على نوع الحساب
        $stmt = $conn->prepare("SELECT MAX(account_number) as max_id FROM accounting WHERE account_type = :account_type");
        $stmt->bindParam(':account_type', $account_type, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // تحديد account_number بناءً على أعلى قيمة موجودة
        $account_number = empty($row['max_id']) ? $start_sequence : $row['max_id'] + 1;

        // جلب أعلى تسلسل للكود حسب نوع الحساب
        $stmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING(account_code, :prefix_length) AS UNSIGNED)) as max_code 
                                FROM accounting WHERE account_type = :account_type");
        $prefix_length = strlen($prefix) + 1;
        $stmt->bindParam(':prefix_length', $prefix_length, PDO::PARAM_INT);
        $stmt->bindParam(':account_type', $account_type, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // تحديد account_code بناءً على أعلى قيمة موجودة
        $account_code_number = empty($row['max_code']) ? $start_sequence : $row['max_code'] + 1;
        $account_code = $prefix . $account_code_number;

        // إدخال البيانات إلى قاعدة البيانات
        $stmt = $conn->prepare("INSERT INTO accounting (account_number, account_Sname, currency_id, account_type, account_code) 
                                VALUES (:account_number, :account_Sname, :currency_id, :account_type, :account_code)");
        $stmt->bindParam(':account_number', $account_number, PDO::PARAM_INT);
        $stmt->bindParam(':account_Sname', $account_Sname, PDO::PARAM_STR);
        $stmt->bindParam(':currency_id', $currency_id, PDO::PARAM_STR);
        $stmt->bindParam(':account_type', $account_type, PDO::PARAM_INT);
        $stmt->bindParam(':account_code', $account_code, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            throw new Exception('Failed to insert data');
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}





if (isset($_POST['account_id'])) {
    $account_id = $_POST['account_id'];
    $query = $conn->prepare("DELETE FROM accounting WHERE account_number = :account_id");
    $result = $query->execute([':account_id' => $account_id]);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if(isset($_POST['account_ids'])) {
    $accountids = json_decode($_POST['account_ids'], true);

    $placeholders = implode(',', array_fill(0, count($accountids), '?'));

    $stmt = $conn->prepare("DELETE FROM accounting WHERE account_number IN ($placeholders)");

    $stmt->execute($accountids);

    if($stmt->rowCount() > 0) {
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

    $columns = ['account_number', 'account_Sname', 'type_sname', 'account_amount', 'currency_sname', 'Date', 'currency_symbole'];
    $order_column_name = $columns[$order_column];

    $query_cond = 'accounting.account_type in (3000,3100,3200)';

    if ($search != '') {
        $query_cond .= ' AND (account_number LIKE :search OR account_Sname LIKE :search OR account_amount LIKE :search OR type_sname LIKE :search OR Date LIKE :search OR currency_sname LIKE :search OR currency_symbole LIKE :search)';
    }

    // استعلام لحساب العدد الكلي
    $query = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM accounting 
        INNER JOIN type ON accounting.account_type = type.type_id
        INNER JOIN currency ON accounting.currency_id = currency.currency_id
        WHERE $query_cond
    ");

    $params = $search != '' ? [':search' => "%$search%"] : [];
    $query->execute($params);
    $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

    // استعلام لجلب البيانات مع الربط بين الجداول
    $query = $conn->prepare("
        SELECT accounting.*, type.type_sname, currency.currency_sname, currency.currency_symbole
        FROM accounting 
        INNER JOIN type ON accounting.account_type = type.type_id
        INNER JOIN currency ON accounting.currency_id = currency.currency_id
        WHERE $query_cond
        ORDER BY $order_column_name $order_dir 
        LIMIT $start, $length
    ");
    $query->execute($params);

    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    // إعداد النتيجة للإرسال كـ JSON
    $result = array(
        "draw" => $draw,
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data
    );

    echo json_encode($result);
}



?>
