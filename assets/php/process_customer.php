<?php
session_start();
require_once 'connection.php';
include 'authenticator.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($action === "add_customer") {
    if (isset($_POST['customer_name']) && isset($_POST['customer_address']) && isset($_POST['customer_phone']) && isset($_POST['account_type'])) {
        $fullName = $_POST['customer_name'];
        $customerAddress = $_POST['customer_address'];
        $customerPhone = $_POST['customer_phone'];
        $account_type = $_POST['account_type'];

        $query = "SELECT MAX(customer_id) as max_id FROM customer";
        $result = $conn->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $customer_id = $row['max_id'] + 1;

        $stmt = $conn->prepare("INSERT INTO customer (customer_id, full_name, customer_address, customer_phone, account_type) VALUES (:customer_id, :full_name, :customer_address, :customer_phone, :account_type)");
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':customer_address', $customerAddress);
        $stmt->bindParam(':customer_phone', $customerPhone);
        $stmt->bindParam(':account_type', $account_type);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}




if (isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $query = $conn->prepare("UPDATE customer SET Delete_Date = NOW() WHERE customer_id = :customer_id");
    $result = $query->execute([':customer_id' => $customer_id]);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if (isset($_POST['customer_ids'])) {
    $customerIds = json_decode($_POST['customer_ids'], true);
    $placeholders = implode(',', array_fill(0, count($customerIds), '?'));

    $stmt = $conn->prepare("UPDATE customer SET Delete_Date = NOW() WHERE customer_id IN ($placeholders)");
    $stmt->execute($customerIds);

    if ($stmt->rowCount() > 0) {
        echo 'Success';
    } else {
        echo 'Failure';
    }
}



if ($action === "drow_customer") {
    $draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
    $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
    $length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
    $search = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
    $order_column = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 0;
    $order_dir = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'asc';

    $columns = ['customer_id', 'full_name', 'customer_address', 'customer_phone', 'balance'];
    $order_column_name = $columns[$order_column];
    $params = [];
    $search_cond = 'WHERE c.Delete_Date IS NULL';

    if ($search != '') {
        $search_cond .= " AND (c.full_name LIKE :search OR c.customer_address LIKE :search OR c.customer_phone LIKE :search)";
        $params[':search'] = "%$search%";
    }

    // استعلام لحساب العدد الكلي
    $query = $conn->prepare("SELECT COUNT(*) as total FROM customer c $search_cond");
    $query->execute($params);
    $total = $query->fetch(PDO::FETCH_ASSOC)['total'];

    // جلب بيانات العملاء مع الانضمام إلى جدول type لجلب type_sname
    $query = $conn->prepare("
        SELECT c.*, t.type_sname 
        FROM customer c 
        LEFT JOIN type t ON t.type_id = c.account_type 
        $search_cond 
        ORDER BY $order_column_name $order_dir 
        LIMIT $start, $length
    ");
    $query->execute($params);

    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    // جلب العملات مرة واحدة لتجنب التكرار
    $stmt = $conn->prepare("SELECT currency_id, currency_sname, currency_symbole FROM currency WHERE Delete_Date IS NULL");
    $stmt->execute();
    $currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // معالجة البيانات لإضافة الرصيد بالعملات
    foreach ($data as $index => $row) {
        $customer_id = $row['customer_id'];
        $balances = [];

        foreach ($currencies as $currency) {
            $currency_id = $currency['currency_id'];
            $balance = getBalance($conn, $customer_id, $currency_id);

            if ($balance != 0) {
                $color = $balance > 0 ? 'text-success' : 'text-danger';
                $formatted_balance = "<span class=\"$color\">" . $balance . ' ' . $currency['currency_symbole'] . "</span>";
                $balances[] = $formatted_balance;
            }
        }

        // دمج الأرصدة المتعددة في نص واحد
        $data[$index]['balance'] = implode('&nbsp;&nbsp;&nbsp;, &nbsp;&nbsp;&nbsp;', $balances);

        // تحديث account_type ليعرض type_sname
        $data[$index]['account_type'] = $row['type_sname'];
    }

    $result = array(
        "draw" => $draw,
        "recordsTotal" => $total,
        "recordsFiltered" => $total,
        "data" => $data
    );

    echo json_encode($result);
}



function getBalance($conn, $customer_id, $currency_id) {
    $params = [
        ':customer_id' => $customer_id,
        ':currency_id' => $currency_id
    ];

    $stmt = $conn->prepare("SELECT (IFNULL(SUM(CASE WHEN tr_type = 'deposit' THEN tr_amount ELSE 0 END), 0) - IFNULL(SUM(CASE WHEN tr_type = 'withdraw' THEN tr_amount ELSE 0 END), 0)) as balance FROM customer_transaction WHERE customer_id = :customer_id AND tr_currency = :currency_id AND Delete_Date IS NULL");
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC)['balance'] ?? 0;
}



?>
