<?php
session_start();
require_once 'connection.php';
include 'time_settings.php';
include 'authenticator.php';


$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

if ($action === "fetch_exchange_rate") {
$currency_ex = $_GET['currency_ex'];

$query = $conn->prepare("
SELECT 
e.buy_rate, 
e.sell_rate,
c1.currency_symbole AS first_account,
c2.currency_symbole AS second_account
FROM exchange_rates e
JOIN currency c1 ON c1.currency_id = e.first_account
JOIN currency c2 ON c2.currency_id = e.second_account
WHERE e.currency_ex = :currency_ex AND e.Delete_Date IS NULL;
");

$query->execute(['currency_ex' => $currency_ex]);
$result = $query->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($result);
}else if ($action === "fetch_data") {
    $invoice_number = isset($_GET["invoice_number"]) ? $_GET["invoice_number"] : null;
    $last_n = isset($_GET["last_n"]) ? intval($_GET["last_n"]) : null;
    
    
    function formatType($type) {
        return $type === 'buy' ? 'شراء' : 'بيع';
    }
    
    function formatCurrencyExchange($conn, $currency_ex) {
        $query = "SELECT fund_sname FROM exchange_rates WHERE currency_ex = ?";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            return $currency_ex; // يمكنك استرجاع قيمة افتراضية هنا إذا كنت ترغب في ذلك.
        }
    
        $stmt->execute([$currency_ex]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result && isset($result['fund_sname'])) {
            return $result['fund_sname'];
        } else {
            return $currency_ex; // إذا لم يتم العثور على نتيجة، سيتم استرجاع قيمة currency_ex.
        }
    }
    
    try {
        if ($last_n) {
            $stmt = $conn->prepare("SELECT 
                ce.type, 
                ce.currency_ex, 
                ce.quantity, 
                ce.exchange_rate, 
                ce.total, 
                ce.order_id, 
                CASE
                    WHEN ce.type = 'buy' THEN c_buy.currency_symbole
                    ELSE c_sell.currency_symbole
                END AS quantitySymbol,
                CASE
                    WHEN ce.type = 'buy' THEN c_sell.currency_symbole
                    ELSE c_buy.currency_symbole
                END AS totalSymbol,
                ce.created_at
            FROM 
                currency_exchange ce
            LEFT JOIN accounting acc_buy ON SUBSTRING_INDEX(ce.currency_ex, '_', 1) = acc_buy.account_number
            LEFT JOIN accounting acc_sell ON SUBSTRING_INDEX(ce.currency_ex, '_', -1) = acc_sell.account_number
            LEFT JOIN currency c_buy ON acc_buy.currency_id = c_buy.currency_id
            LEFT JOIN currency c_sell ON acc_sell.currency_id = c_sell.currency_id
            WHERE 
                ce.reason_delete IS NULL 
            ORDER BY 
                ce.created_at DESC 
            LIMIT :last_n");
        
            $stmt->bindParam(':last_n', $last_n, PDO::PARAM_INT);
        }
        else {
            $stmt = $conn->prepare("
    SELECT 
        ce.type, 
        ce.currency_ex, 
        ce.quantity, 
        ce.exchange_rate, 
        ce.total, 
        ce.order_id, 
        CASE
            WHEN ce.type = 'buy' THEN c_buy.currency_symbole
            ELSE c_sell.currency_symbole
        END AS quantitySymbol,
        CASE
            WHEN ce.type = 'buy' THEN c_sell.currency_symbole
            ELSE c_buy.currency_symbole
        END AS totalSymbol,
        ce.created_at
    FROM 
        currency_exchange ce
    LEFT JOIN accounting acc_buy ON SUBSTRING_INDEX(ce.currency_ex, '_', 1) = acc_buy.account_number
    LEFT JOIN accounting acc_sell ON SUBSTRING_INDEX(ce.currency_ex, '_', -1) = acc_sell.account_number
    LEFT JOIN currency c_buy ON acc_buy.currency_id = c_buy.currency_id
    LEFT JOIN currency c_sell ON acc_sell.currency_id = c_sell.currency_id
    WHERE 
        ce.order_id = :invoice_number 
        AND ce.reason_delete IS NULL
");

$stmt->bindParam(':invoice_number', $invoice_number);

        }
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $settings = getSettings($conn);
    
        $formattedResult = [];
        foreach ($result as $row) {
            $row['type'] = formatType($row['type']);
            $row['currency_ex'] = formatCurrencyExchange($conn, $row['currency_ex']);
            $dateAndTime = formatDateAndTime($row['created_at'], $settings);
            $row['date'] = $dateAndTime['date'];
            $row['time'] = $dateAndTime['time'];
            
            // هذا هو الكود الجديد لتقسيم order_id
            $order_id_parts = explode("-", $row['order_id']);
            if (isset($order_id_parts[1])) {
                $row['order_id'] = $order_id_parts[1];
            }

            $formattedResult[] = $row;
        }
        
        echo json_encode($formattedResult);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        }

}elseif ($action === "fetch_customer_name") {

    if (isset($_GET['customer_id'])) {
        $customer_id = $_GET['customer_id'];

        $stmt = $conn->prepare("SELECT full_name FROM customer WHERE customer_id = :customer_id AND Delete_Date IS NULL");
        $stmt->execute(['customer_id' => $customer_id]);
        $customer = $stmt->fetch();

        if ($customer) {
            echo json_encode(['full_name' => $customer['full_name']]);
        } else {
            echo json_encode(['error' => 'Customer not found']);
        }
    } else if (isset($_GET['full_name'])) {
        $full_name = $_GET['full_name'];

        $stmt = $conn->prepare("SELECT customer_id FROM customer WHERE full_name = :full_name AND Delete_Date IS NULL");
        $stmt->execute(['full_name' => $full_name]);

        if ($row = $stmt->fetch()) {
            echo json_encode(['customer_id' => $row['customer_id']]);
        } else {
            echo json_encode(['error' => 'Customer not found']);
        }
    } else {
        echo "Invalid request.";
    }
}
else if ($action === "save_transactions") {
    function updateCurrencyFund($conn, $transaction) {
        // استخدم "_" كفاصل بين العملات
        list($currency1, $currency2) = explode("_", $transaction['currency_ex']);
        
        if ($transaction['type'] === 'buy') {
            $subtractFrom = $currency2;
            $addTo = $currency1;
            $subtractAmount = $transaction['total'];
            $addAmount = $transaction['quantity'];
        } else if ($transaction['type'] === 'sell') {
            $subtractFrom = $currency1;
            $addTo = $currency2;
            $subtractAmount = $transaction['total'];
            $addAmount = $transaction['quantity'];
        }
        
        $update_query1 = "UPDATE accounting SET account_amount = account_amount - ? WHERE account_number = ?";
        $update_query2 = "UPDATE accounting SET account_amount = account_amount + ? WHERE account_number = ?";
        
        $stmt1 = $conn->prepare($update_query1);
        $stmt2 = $conn->prepare($update_query2);
        
        if (!$stmt1 || !$stmt2) {
            return false;
        }
        
        if (!$stmt1->execute([$subtractAmount, $subtractFrom]) || !$stmt2->execute([$addAmount, $addTo])) {
            return false;
        }
        
        return true;
    }
    
    
    function idExists($conn, $id) {
         $check_id_query = "SELECT id FROM currency_exchange WHERE id = ?";
        $stmt = $conn->prepare($check_id_query);
    
        if (!$stmt) {
            return false;
        }
    
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row !== false;
    }
    
    function generateRandomId($prefix) {
        return $prefix . str_pad(strval(mt_rand(0, 1e9 - 1)), 9, '0', STR_PAD_LEFT);
    }
    
    function getNextOrderId($conn) {
        $max_order_id_query = "SELECT MAX(CAST(SUBSTRING(order_id, 3) AS UNSIGNED)) AS max_order_number FROM currency_exchange";
        $stmt = $conn->prepare($max_order_id_query);
    
        if (!$stmt) {
            return false;
        }
    
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row !== false && !is_null($row['max_order_number'])) {
            return 'O-' . ($row['max_order_number'] + 1);
        } else {
            return 'O-1';
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $json = file_get_contents('php://input');
        $transactions = json_decode($json, true);
    
        if (!empty($transactions)) {
            $conn->beginTransaction();
    
            $order_id = getNextOrderId($conn);
    
            foreach ($transactions as $transaction) {
    
                $insert_query = "INSERT INTO currency_exchange (type, currency_ex, quantity, exchange_rate, total, created_by, customer_id, order_id) VALUES (?,?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_query);
                if (!$insert_stmt) {
                    $conn->rollBack();
                    http_response_code(500);
                    echo json_encode(array("error" => "Error preparing insert query."));
                    exit;
                }
    
                $customer_id = isset($transaction['customer_id']) ? $transaction['customer_id'] : null;
                $total = isset($transaction['total']) && !is_null($transaction['total']) ? $transaction['total'] : 0;
                if (!$insert_stmt->execute([ $transaction['type'], $transaction['currency_ex'], $transaction['quantity'], $transaction['exchange_rate'], $total, $transaction['created_by'], $customer_id, $order_id])) {
                    $conn->rollBack();
                    http_response_code(500);
                    echo json_encode(array("error" => "Error executing insert query."));
                    exit;
                }
                if (!updateCurrencyFund($conn, $transaction)) {
                    $conn->rollBack();
                    http_response_code(500);
                    echo json_encode(array("error" => "Error updating currency fund."));
                    exit;
                }
            }
    
            $conn->commit();
        } else {
            http_response_code(400);
            echo json_encode(array("error" => "No data provided."));
        }
    } else {
        http_response_code(405);
        echo json_encode(array("error" => "Only POST method is allowed."));
    }
    
    http_response_code(200);
    $parts = explode('-', $order_id);
    $order_number = isset($parts[1]) ? $parts[1] : '';
    echo json_encode(array("order_id" => $order_number));
    
}else if ($action === "fetch_data_remove") {
    $order_id = 'O-' . $_POST["order_id"];
    $description = $_POST["description"];

    try {
        // Fetch the data related to the order_id
        $stmt = $conn->prepare("SELECT * FROM currency_exchange WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            echo "Error: No records found with order_id " . $order_id;
            exit();
        }

        foreach ($results as $result) {
            $currencies = explode('_', $result['currency_ex']);
            $firstCurrency = $currencies[0];
            $secondCurrency = $currencies[1];

            $firstAmount = $result['quantity'];
            $secondAmount = $result['total'];

            if ($result['type'] == 'buy') {
                $stmt = $conn->prepare("UPDATE accounting SET account_amount = account_amount - :firstAmount WHERE account_number = :firstCurrency");
                $stmt->bindParam(':firstAmount', $firstAmount);
                $stmt->bindParam(':firstCurrency', $firstCurrency);
                $updated1 = $stmt->execute();
                
                $stmt = $conn->prepare("UPDATE accounting SET account_amount = account_amount + :secondAmount WHERE account_number = :secondCurrency");
                $stmt->bindParam(':secondAmount', $secondAmount);
                $stmt->bindParam(':secondCurrency', $secondCurrency);
                $updated2 = $stmt->execute();
            } else {
                $firstAmount = $result['total'];
                $secondAmount = $result['quantity'];
                $stmt = $conn->prepare("UPDATE accounting SET account_amount = account_amount + :firstAmount WHERE account_number = :firstCurrency");
                $stmt->bindParam(':firstAmount', $firstAmount);
                $stmt->bindParam(':firstCurrency', $firstCurrency);
                $updated1 = $stmt->execute();
                
                $stmt = $conn->prepare("UPDATE accounting SET account_amount = account_amount - :secondAmount WHERE account_number = :secondCurrency");
                $stmt->bindParam(':secondAmount', $secondAmount);
                $stmt->bindParam(':secondCurrency', $secondCurrency);
                $updated2 = $stmt->execute();
            }

            if (!$updated1 || !$updated2) {
                echo "Error: Failed to update accounting table";
                exit();
            }

            $stmt = $conn->prepare("UPDATE currency_exchange SET reason_delete = :description, Delete_Date = NOW() WHERE order_id = :order_id");
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':order_id', $result['order_id']);
            $stmt->execute();
        }

        echo "Records updated successfully";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}else if ($action === "update_exchange_rate") {
    if (
        isset($_POST['currency_ex']) &&
        isset($_POST['buy_rate']) &&
        isset($_POST['sell_rate'])
    ) {
        $currency_ex = $_POST['currency_ex'];
        $buy_rate = $_POST['buy_rate'];
        $sell_rate = $_POST['sell_rate'];
    
        $stmt = $conn->prepare("UPDATE exchange_rates SET buy_rate = :buy_rate, sell_rate = :sell_rate WHERE currency_ex = :currency_ex");
        $result = $stmt->execute([
            'currency_ex' => $currency_ex,
            'buy_rate' => $buy_rate,
            'sell_rate' => $sell_rate,
        ]);
    
        if ($result) {
            echo json_encode(['success' => 'تم تحديث سعر الصرف']);
        } else {
            echo json_encode(['error' => 'فشل تحديث سعر الصرف']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}else if ($action === "search_customers") {
    // Read the query from the POST data
    $input = file_get_contents('php://input');
    $postData = json_decode($input, true);
    $query = $postData['query'];

    // Prepare a SQL statement to search customers
    $sql = "SELECT * FROM customer WHERE (full_name LIKE :query OR customer_phone LIKE :query OR customer_id LIKE :query) AND Delete_Date IS NULL";
    $stmt = $conn->prepare($sql);

    // Bind the query parameter and execute the statement
    $stmt->execute(['query' => "%$query%"]);

    // Fetch all matching customers
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Send the customers as a JSON response
    header('Content-Type: application/json');
    echo json_encode($customers);
}
else if ($action === "company_info") {
    try {
        $stmt = $conn->prepare("SELECT * FROM company_info");
        $stmt->execute();
    
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $companyInfo = $stmt->fetch();
    
        echo json_encode($companyInfo);
    }
    catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}else if ($action === "process_form") {

// Get current logo
$sql = "SELECT logo FROM company_info WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();
$currentLogo = $result['logo'];

// Extract the extension of the current logo
$currentLogoExt = pathinfo($currentLogo, PATHINFO_EXTENSION);

// Check if file was uploaded
if(isset($_FILES['logo'])){
    $errors= array();
    $file_name = $_FILES['logo']['name'];
    $file_size =$_FILES['logo']['size'];
    $file_tmp =$_FILES['logo']['tmp_name'];
    $file_type=$_FILES['logo']['type'];
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
    if(file_exists("../media/dollar/Company_logo." . $currentLogoExt)){
        unlink("../media/dollar/Company_logo." . $currentLogoExt);
    }

    // Change the name of the file to "logo"
    $file_name = "Company_logo." . $file_ext;

    // Upload file to server
    if(empty($errors)==true){
        move_uploaded_file($file_tmp,"../media/dollar/".$file_name);
        $logo = "assets/media/dollar/".$file_name;  // Set new logo path if file uploaded successfully
    }else{
        print_r($errors);
        $logo = $currentLogo;  // Use current logo path if file not uploaded successfully
    }
}else{
    $logo = $currentLogo;  // Use current logo path if no file uploaded
}

// Save data to database
if(isset($_POST['companyName'])){
    $companyName = $_POST['companyName'];
    $companyAddress = $_POST['companyAddress'];
    $mobileNumber = $_POST['mobileNumber'];
    $companyDescription = $_POST['companyDescription'];

    $sql = "INSERT INTO company_info (id, logo, companyName, companyAddress, mobileNumber, companyDescription) VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE logo = VALUES(logo), companyName = VALUES(companyName), companyAddress = VALUES(companyAddress), mobileNumber = VALUES(mobileNumber), companyDescription = VALUES(companyDescription)";
    $stmt= $conn->prepare($sql);
    $stmt->execute([1, $logo, $companyName, $companyAddress, $mobileNumber, $companyDescription]);
    $response = ['success' => true, 'message' => 'تم تحديث البيانات بنجاح'];
}else {
    $response = ['success' => false, 'message' => 'حدث خطأ أثناء التحديث'];
}

header('Content-Type: application/json');
echo json_encode($response);
}else if ($action === "vodafone_cash") {

    $query = $conn->prepare("SELECT vodafone_cash_price FROM settings WHERE id = 1");
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($result);
}else if ($action === "update_vodafone_cash") {
    if (
        isset($_POST['vodafone_cash_price'])
    ) {
        $buy_rate = $_POST['vodafone_cash_price'];
    
        $stmt = $conn->prepare("UPDATE settings SET vodafone_cash_price = :buy_rate WHERE id = 1");
        $result = $stmt->execute([
            'buy_rate' => $buy_rate,
        ]);
    
        if ($result) {
            echo json_encode(['success' => 'تم تحديث سعر الصرف']);
        } else {
            echo json_encode(['error' => 'فشل تحديث سعر الصرف']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}


   

?>