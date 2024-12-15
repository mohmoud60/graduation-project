<?php
session_start();
require_once 'connection.php';
include 'authenticator.php';

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

if ($action === "add_currency") {
    $currency1 = $_POST['currency1'];
    $currency2 = $_POST['currency2'];
    $buy_rate = $_POST['buy_rate'];
    $sell_rate = $_POST['sell_rate'];
    $first_account = $_POST['first_account'];
    $second_account = $_POST['second_account'];

    // استعلام لجلب account_Sname للعملتين في نفس الوقت
    $stmt = $conn->prepare("SELECT account_number, account_Sname FROM accounting WHERE account_number IN (:currency1, :currency2)");
    $stmt->bindParam(':currency1', $currency1);
    $stmt->bindParam(':currency2', $currency2);
    
    if (!$stmt->execute()) {
        echo 'Error fetching account names';
        exit();
    }

    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // جلب النتائج كمصفوفة مع مفتاح وقيمة

    if (!isset($results[$currency1]) || !isset($results[$currency2])) {
        echo 'One of the currencies not found';
        exit();
    }

    $fund_sname = $results[$currency1] . " - " . $results[$currency2];
    $currency_ex = $currency1 . "_" . $currency2;

    $stmt = $conn->prepare("INSERT INTO exchange_rates (currency_ex, fund_sname, buy_rate, sell_rate , first_account , second_account) VALUES (:currency_ex, :fund_sname, :buy_rate, :sell_rate ,:first_account ,:second_account)");
    $stmt->bindParam(':currency_ex', $currency_ex);
    $stmt->bindParam(':fund_sname', $fund_sname);
    $stmt->bindParam(':buy_rate', $buy_rate);
    $stmt->bindParam(':sell_rate', $sell_rate);
    $stmt->bindParam(':first_account', $first_account);
    $stmt->bindParam(':second_account', $second_account);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error inserting into database';
    }
}






if ($action === "delete_currency") {
    $account_id = $_POST['account_id'];
    $query = $conn->prepare("UPDATE exchange_rates SET Delete_Date = CURRENT_TIMESTAMP WHERE id = :account_id");
    $result = $query->execute([':account_id' => $account_id]);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($action === "delete_currencys") {
    $accountids = json_decode($_POST['account_ids'], true);

    $placeholders = implode(',', array_fill(0, count($accountids), '?'));

    $stmt = $conn->prepare("UPDATE exchange_rates SET Delete_Date = CURRENT_TIMESTAMP WHERE id IN ($placeholders)");
    
    $stmt->execute($accountids);

    if ($stmt->rowCount() > 0) {
        echo 'Success';
    } else {
        echo 'Failure';
    }
}

if ($action === "show_currency") {
    $draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
    $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
    $length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
    $search = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
    $order_column = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 0;
    $order_dir = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'asc';
    
    $columns = [ 'id', 'fund_sname', 'buy_rate', 'sell_rate' , 'created_at'];
    $order_column_name = $columns[$order_column];
    
    if ($search != '') {
        $query = $conn->prepare("SELECT COUNT(*) as total FROM exchange_rates WHERE (OR fund_sname LIKE :search OR buy_rate LIKE :search OR sell_rate LIKE :search OR created_at LIKE :search) AND Delete_Date IS NULL ");
        $query->execute([':search' => "%$search%"]);
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
    
        $query = $conn->prepare("SELECT * FROM exchange_rates WHERE (OR fund_sname LIKE :search OR buy_rate LIKE :search OR sell_rate LIKE :search OR created_at LIKE :search) AND Delete_Date IS NULL ORDER BY $order_column_name $order_dir LIMIT $start, $length");
        $query->execute([':search' => "%$search%"]);
    } else {
        $query = $conn->prepare("SELECT COUNT(*) as total FROM exchange_rates WHERE Delete_Date IS NULL");
        $query->execute();
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
    
        $query = $conn->prepare("SELECT * FROM exchange_rates WHERE Delete_Date IS NULL ORDER BY $order_column_name $order_dir LIMIT $start, $length");
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




if ($action === "update_exchange") {
    $response = ['success' => true]; // نضع القيمة الافتراضية كصحيحة

    try {
        // تأكد من أن الاتصال بقاعدة البيانات موجود
        if (!$conn) {
            throw new Exception("لا يمكن الاتصال بقاعدة البيانات");
        }

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'buy_rate_') !== false) {
                $fund_sname = str_replace('buy_rate_', '', $key);
                $buy_rate = $value;
                $sell_rate = $_POST['sell_rate_' . $fund_sname];

                $stmt = $conn->prepare("UPDATE exchange_rates SET buy_rate = :buy_rate, sell_rate = :sell_rate WHERE currency_ex = :fund_sname");
                $stmt->execute([':buy_rate' => $buy_rate, ':sell_rate' => $sell_rate, ':fund_sname' => $fund_sname]);

                if ($stmt->rowCount() <= 0) {
                    // يمكنك تسجيل هذه الملاحظة دون إيقاف البرنامج
                    error_log("لا توجد نتيجة معينة للعملة: " . $fund_sname);
                }
                
            }
        }

        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage(), 'db_error' => $conn->errorInfo()]);
    }
}

if ($action === "show_currency_settings") {
    $draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 0;
    $start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
    $length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
    $search = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
    $order_column = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 0;
    $order_dir = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'asc';
    
    $columns = [ 'currency_id', 'currency_sname', 'currency_symbole'];
    $order_column_name = $columns[$order_column];
    
    if ($search != '') {
        $query = $conn->prepare("SELECT COUNT(*) as total FROM currency WHERE (currency_id LIKE :search OR currency_sname LIKE :search OR currency_symbole LIKE :search) AND Delete_Date IS NULL");
        $query->execute([':search' => "%$search%"]);
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
    
        $query = $conn->prepare("SELECT * FROM currency WHERE (currency_id LIKE :search OR currency_sname LIKE :search OR currency_symbole LIKE :search) AND Delete_Date IS NULL ORDER BY $order_column_name $order_dir LIMIT $start, $length");
        $query->execute([':search' => "%$search%"]);
    } else {
        $query = $conn->prepare("SELECT COUNT(*) as total FROM currency WHERE Delete_Date IS NULL");
        $query->execute();
        $total = $query->fetch(PDO::FETCH_ASSOC)['total'];
    
        $query = $conn->prepare("SELECT * FROM currency WHERE Delete_Date IS NULL ORDER BY $order_column_name $order_dir LIMIT $start, $length");
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

if ($action === "delete_currency_settings") {
    $account_id = $_POST['account_id'];
    $query = $conn->prepare("UPDATE currency SET Delete_Date = CURRENT_TIMESTAMP WHERE currency_id = :account_id");
    $result = $query->execute([':account_id' => $account_id]);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($action === "delete_currencys_settings") {
    $accountids = json_decode($_POST['account_ids'], true);

    $placeholders = implode(',', array_fill(0, count($accountids), '?'));

    $stmt = $conn->prepare("UPDATE currency SET Delete_Date = CURRENT_TIMESTAMP WHERE currency_id IN ($placeholders)");
    
    $stmt->execute($accountids);

    if ($stmt->rowCount() > 0) {
        echo 'Success';
    } else {
        echo 'Failure';
    }
}

if ($action === "add_currency_settings") {
    $currency_sname = $_POST['currency_sname'];
    $currency_symbole = $_POST['currency_symbole'];

    // 1. جلب أعلى قيمة من currency_id من الجدول.
    $stmt = $conn->prepare("SELECT MAX(currency_id) as max_currency_id FROM currency");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $max_currency_id = intval($result['max_currency_id']);

    // 2. زيادة هذه القيمة بواقع 5.
    $new_currency_id = $max_currency_id + 5;

    // 3. تحقق من أن القيمة المحسوبة تتكون من 3 خانات.
    $currency_id = str_pad($new_currency_id, 3, "0", STR_PAD_LEFT);

    // تحضير الاستعلام وإدخال البيانات في الجدول
    $stmt = $conn->prepare("INSERT INTO currency (currency_sname, currency_symbole, currency_id) VALUES (:currency_sname, :currency_symbole, :currency_id)");
    $stmt->bindParam(':currency_sname', $currency_sname);
    $stmt->bindParam(':currency_symbole', $currency_symbole);
    $stmt->bindParam(':currency_id', $currency_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error inserting into database';
    }
}



?>
