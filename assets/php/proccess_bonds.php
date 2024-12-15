<?php
session_start();
require_once 'connection.php';
include 'time_settings.php';
include 'authenticator.php';

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

if ($action === "check_number") {
    $prefix = $_GET['prefix'];

    $query = $conn->prepare("SELECT bond_number FROM bonds WHERE bond_number LIKE :prefix");
    $query->execute(['prefix' => $prefix . '%']);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
}

if ($action === "save_bonds") {
    $bond_type = $_POST['bond_type'];
    $bond_number = $_POST[$bond_type . '_number'];
    $bond_name = $_POST[$bond_type . '_name'];
    $amount = $_POST[$bond_type . '_amount'];
    $fund_name = $_POST['currency']; // تم حفظ العملة الأصلية هنا
    
    // جلب fund_symbols استناداً إلى fund_name
   // احصل على currency_symbole استناداً إلى account_number
$currency_query = "
SELECT 
    c.currency_symbole 
FROM 
    currency c 
INNER JOIN 
    accounting a 
ON 
    c.currency_id = a.currency_id 
WHERE 
    a.account_number = ?";

$currency_stmt = $conn->prepare($currency_query);
$currency_stmt->execute([$fund_name]); // استخدم account_number هنا
$currency_row = $currency_stmt->fetch(PDO::FETCH_ASSOC);

$currency = $fund_name; // استخدم القيمة الافتراضية في حالة عدم وجود العملة في الجدول
if ($currency_row) {
$currency = $currency_row['currency_symbole'];
}


    $description = $_POST[$bond_type . '_description'];
    $created_by = $_SESSION['username']; // إضافة created_by

    // تحقق من ما إذا كان السند موجوداً بالفعل في النظام
    $check_bond_query = "SELECT * FROM bonds WHERE bond_number = ? AND bond_type = ?";
    $check_bond_stmt = $conn->prepare($check_bond_query);
    $check_bond_stmt->execute([$bond_number, $bond_type]);
    $bond_exists = $check_bond_stmt->fetch(PDO::FETCH_ASSOC);

    if ($bond_exists) {
        // رد النتيجة
        $response = [
            'status' => 'error',
            'message' => 'السند موجود بالفعل في النظام.'
        ];
        echo json_encode($response);
        exit();
    }
    // إضافة السند إلى جدول السندات
    $is_special = isset($_POST['is_special']) && $_POST['is_special'] ? 1 : 0;

    $query = "INSERT INTO bonds (bond_type, bond_number, bond_name, amount, currency, description, created_by , fund_name, is_special)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$bond_type, $bond_number, $bond_name, $amount, $currency, $description, $created_by , $fund_name, $is_special]);

    // تحديث مبلغ صندوق العملة
    if ($bond_type === 'exchange') {
        $query = "UPDATE accounting SET account_amount = account_amount - ? WHERE account_number = ?";
    } elseif ($bond_type === 'receipt') {
        $query = "UPDATE accounting SET account_amount = account_amount + ? WHERE account_number = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->execute([$amount, $fund_name]);

    // رد النتيجة
    $response = [
        'status' => 'success',
        'message' => 'تمت إضافة السند بنجاح.'
    ];
    echo json_encode($response);
}

if ($action === "serch_bonds") {
    header('Content-Type: application/json');

    $searchQuery = $_GET['query'];
    $settings = getSettings($conn);

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT eb.*, a.account_Sname AS fund_name_converted, ct.sname AS bond_type_converted 
        FROM bonds eb 
        LEFT JOIN accounting a ON eb.fund_name = a.account_number 
        LEFT JOIN convert_types ct ON eb.bond_type = ct.name
        WHERE (eb.bond_number LIKE :query OR eb.bond_name LIKE :query) AND eb.Delete_Date IS NULL
        ORDER BY eb.created_at DESC";


        $stmt = $conn->prepare($query);
        $stmt->execute(['query' => '%' . $searchQuery . '%']);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as &$row) {
            $formattedDateTime = formatDateAndTime($row['created_at'], $settings);
            $row['created_at'] = $formattedDateTime['time'];
            $row['created_date'] = $formattedDateTime['date'];
        }
        echo json_encode($result);

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}

if ($action === "showLastEntriesBtn") {
    header('Content-Type: application/json');

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $query = "SELECT eb.*, a.account_Sname AS fund_name_converted, ct.sname AS bond_type_converted 
        FROM bonds eb 
        LEFT JOIN accounting a ON eb.fund_name = a.account_number 
        LEFT JOIN convert_types ct ON eb.bond_type = ct.name 
        WHERE eb.Delete_Date IS NULL
        ORDER BY eb.created_at DESC LIMIT :limit";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settings = getSettings($conn);
        foreach ($result as &$row) {
            $formattedDateTime = formatDateAndTime($row['created_at'], $settings);
            $row['created_at'] = $formattedDateTime['time'];
            $row['created_date'] = $formattedDateTime['date'];
        }

        echo json_encode($result);

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}


if ($action === "delete_bonds") {
    if (!isset($_POST['bond_number'])) {
        http_response_code(400);
        echo json_encode(['error' => 'bond_number is missing']);
        exit;
    }
    
    $bond_number = $_POST['bond_number'];
    
    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->beginTransaction(); // بدء عملية تحديث متعددة
    
        // جلب بيانات السند قبل حذفه
        $query = "SELECT * FROM bonds WHERE bond_number = :bond_number";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':bond_number', $bond_number);
        $stmt->execute();
        $bond = $stmt->fetch(PDO::FETCH_ASSOC);

        // Now use $currency_code in your queries.
        if ($bond['bond_type'] == 'exchange') {
            $query = "UPDATE accounting SET account_amount = account_amount + :amount WHERE account_number = :currency";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':amount', $bond['amount']);
            $stmt->bindParam(':currency', $bond['fund_name']);
            $stmt->execute();
        } elseif ($bond['bond_type'] == 'receipt') {
            $query = "UPDATE accounting SET account_amount = account_amount - :amount WHERE account_number = :currency";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':amount', $bond['amount']);
            $stmt->bindParam(':currency', $bond['fund_name']);
            $stmt->execute();
        }
        
       // بدلاً من حذف السجل، قم بتحديث قيمة Delete_Date
$query = "UPDATE bonds SET Delete_Date = CURRENT_TIMESTAMP WHERE bond_number = :bond_number";
$stmt = $conn->prepare($query);
$stmt->bindParam(':bond_number', $bond_number);
$stmt->execute();

    $conn->commit(); // إكمال عملية التحديث
    
    echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $conn->rollBack(); // التراجع عن التحديثات في حالة وجود خطأ
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        }
        
        $conn = null;
}
?>