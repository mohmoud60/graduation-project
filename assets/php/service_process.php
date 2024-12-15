<?php
// Include database connection file
include 'connection.php';
include 'convertNumberToWords.php';
include 'time_settings.php';
include 'authenticator.php';

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;


if ($action === "save") {
    try {
        $conn->beginTransaction();

        function fetchNextId($conn, $table, $idColumn) {
            $stmt = $conn->prepare("SELECT MAX($idColumn) as max_id FROM $table");
            $stmt->execute();
            $max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];
            $next_id = $max_id !== null ? $max_id + 1 : 1;
            return str_pad($next_id, 10, "0", STR_PAD_LEFT);
        }
        $transfer_id = fetchNextId($conn, 'transfers', 'transfer_id');

        $checkSql = "SELECT 1 FROM transfers WHERE transfer_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->execute([$transfer_id]);

        if ($checkStmt->fetchColumn()) {
            throw new Exception('This transfer ID already exists in the database.');
        }
        
        $from_account_ids = $_POST['from_account'];
        $from_amounts = $_POST['from_amount'];
        $from_types = $_POST['from_type'];
        $to_account_ids = $_POST['to_account'];
        $to_amounts = $_POST['to_amount'];
        $to_types = $_POST['to_type'];
        $income_fund = explode("_", $_POST['income_fund'])[1];
        $income_amount = $_POST['income_amount'];
        $description = $_POST['description'];
        $created_by = $_SESSION["username"];
        $from_currencies = $_POST['from_currency'];
        $to_currencies = $_POST['to_currency'];
        $cut_vodafone = $_POST['Cut_Vodafone'];

        $sql = "INSERT INTO transfers (transfer_id, from_account_id, from_amount, from_type, from_account_type, to_account_id, to_amount, to_type, to_account_type, income_fund, income_amount, description, created_by, cut_vodafone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($sql);

        $sql2 = "UPDATE accounting SET account_amount = account_amount + ? WHERE account_number = ?";
        $stmt2 = $conn->prepare($sql2);

        $sql3 = "UPDATE accounting SET account_amount = account_amount - ? WHERE account_number = ?";
        $stmt3 = $conn->prepare($sql3);

        $sql5 = "INSERT INTO customer_transaction (customer_id, tr_type, tr_amount, tr_descripcion, tr_currency) VALUES (?, ?, ?, ?, ?)";
        $stmt5 = $conn->prepare($sql5);

        $sql_update_tr_from_id = "UPDATE transfers SET tr_from_id = ? WHERE transfer_id = ?";
        $sql_update_tr_to_id = "UPDATE transfers SET tr_to_id = ? WHERE transfer_id = ?";
        $stmt_update_tr_from_id = $conn->prepare($sql_update_tr_from_id);
        $stmt_update_tr_to_id = $conn->prepare($sql_update_tr_to_id);

        for($i = 0; $i < max(count($from_account_ids), count($to_account_ids)); $i++) {
            $from_account_id = isset($from_account_ids[$i]) ? explode("_", sanitizeInput($from_account_ids[$i]))[1] : null;
            $from_amount = isset($from_amounts[$i]) ? sanitizeInput($from_amounts[$i]) : 0;
            $from_type = isset($from_types[$i]) ? sanitizeInput($from_types[$i]) : 'deposit';
            $to_account_id = isset($to_account_ids[$i]) ? explode("_", sanitizeInput($to_account_ids[$i]))[1] : null;
            $to_amount = isset($to_amounts[$i]) ? sanitizeInput($to_amounts[$i]) : 0;
            $to_type = isset($to_types[$i]) ? sanitizeInput($to_types[$i]) : 'deposit';
            $from_currency = isset($from_currencies[$i]) ? sanitizeInput($from_currencies[$i]) : 'USD';
            $to_currency = isset($to_currencies[$i]) ? sanitizeInput($to_currencies[$i]) : 'USD';
            $cut_vodafone = isset($_POST['Cut_Vodafone']) && $_POST['Cut_Vodafone'] !== '' ? $_POST['Cut_Vodafone'] : null;

            if (isset($from_account_ids[$i])) {
                if (strpos($from_account_ids[$i], 'fund_') !== false) {
                    $from_account_type = 'funds';
                } else if (strpos($from_account_ids[$i], 'customer_') !== false) {
                    $from_account_type = 'customer';
                }
            }
        
            if (isset($to_account_ids[$i])) {
                if (strpos($to_account_ids[$i], 'fund_') !== false) {
                    $to_account_type = 'funds';
                } else if (strpos($to_account_ids[$i], 'customer_') !== false) {
                    $to_account_type = 'customer';
                }
            }

            $stmt1->execute([$transfer_id, $from_account_id, $from_amount, $from_type, $from_account_type, $to_account_id, $to_amount, $to_type, $to_account_type, $income_fund, $income_amount, $description, $created_by , $cut_vodafone]);

            if ($from_account_type == 'customer' && !empty($from_account_id)) {
                $customer_id = $from_account_id;
                $tr_type = $from_type;
                $tr_amount = $from_amount;
                $tr_currency = $from_currency;
                $stmt5->execute([$customer_id, $tr_type, $tr_amount, $description, $tr_currency]);

                $from_tr_id = $conn->lastInsertId();
                $stmt_update_tr_from_id->execute([$from_tr_id, $transfer_id]);
            }
            if ($to_account_type == 'customer' && !empty($to_account_id)) {
                $customer_id = $to_account_id;
                $tr_type = $to_type;
                $tr_amount = $to_amount;
                $tr_currency = $to_currency;
                $stmt5->execute([$customer_id, $tr_type, $tr_amount, $description, $tr_currency]);

                $to_tr_id = $conn->lastInsertId();
                $stmt_update_tr_to_id->execute([$to_tr_id, $transfer_id]);
            }
        }

        if(strpos($_POST['income_fund'], 'income_') !== false) {
            $stmt2->execute([$income_amount, $income_fund]);
        }
        $conn->commit();
        $word_amounts = convertNumberToWords(floatval($from_amount));
        echo json_encode(["success" => true, "from_amount_in_words" => $word_amounts, "message" => "Records inserted successfully." , 'transfer_id' => $transfer_id ]);
    } catch(PDOException $error) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "ERROR: Could not execute the query. " . $error->getMessage()]);
    } catch(Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}


function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



/////////////
if ($action === "showLastEntriesBtn") {
    header('Content-Type: application/json');

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $query = "SELECT t.transfer_id, t.from_amount, t.to_amount, t.income_amount, t.from_account_type,
        CASE
            WHEN t.cut_vodafone IS NOT NULL THEN CONCAT(t.description, ' قص ', t.cut_vodafone)
            ELSE t.description
        END AS description,
        t.created_at,
        CASE
            WHEN t.from_account_type = 'funds' THEN a1.account_Sname
            WHEN t.from_account_type = 'customer' THEN c1.full_name
        END AS from_account_name,
        CASE
            WHEN t.to_account_type = 'funds' THEN a2.account_Sname
            WHEN t.to_account_type = 'customer' THEN c2.full_name
        END AS to_account_name,
        ct1.sname AS from_type_converted,
        ct2.sname AS to_type_converted,
        a3.account_Sname AS income_fund_converted,
        CASE
            WHEN t.from_account_type = 'funds' THEN cur1.currency_symbole
            WHEN t.from_account_type = 'customer' THEN cur4.currency_symbole
        END AS from_account_currency,
        CASE
        WHEN t.from_account_type = 'funds' THEN cur1.currency_sname
        WHEN t.from_account_type = 'customer' THEN cur4.currency_sname
        END AS currency_sname,
        CASE
            WHEN t.to_account_type = 'funds' THEN cur2.currency_symbole
            WHEN t.to_account_type = 'customer' THEN cur6.currency_symbole
        END AS to_account_currency,
        cur3.currency_symbole AS income_fund_currency
    FROM transfers t
    LEFT JOIN accounting a1 ON t.from_account_id = a1.account_number AND t.from_account_type = 'funds'
    LEFT JOIN customer c1 ON t.from_account_id = c1.customer_id AND t.from_account_type = 'customer'
    LEFT JOIN accounting a2 ON t.to_account_id = a2.account_number AND t.to_account_type = 'funds'
    LEFT JOIN customer c2 ON t.to_account_id = c2.customer_id AND t.to_account_type = 'customer'
    LEFT JOIN convert_types ct1 ON t.from_type = ct1.name
    LEFT JOIN convert_types ct2 ON t.to_type = ct2.name
    LEFT JOIN accounting a3 ON t.income_fund = a3.account_number
    LEFT JOIN currency cur1 ON a1.currency_id = cur1.currency_id
    LEFT JOIN currency cur2 ON a2.currency_id = cur2.currency_id
    LEFT JOIN currency cur3 ON a3.currency_id = cur3.currency_id
    LEFT JOIN customer_transaction ct4 ON t.tr_from_id = ct4.id AND t.from_account_type = 'customer'
    LEFT JOIN currency cur4 ON ct4.tr_currency = cur4.currency_id
    LEFT JOIN customer_transaction ct5 ON t.tr_to_id = ct5.id AND t.to_account_type = 'customer'
    LEFT JOIN currency cur6 ON ct5.tr_currency = cur6.currency_id
    WHERE t.Delete_Date IS NULL
    ORDER BY t.created_at DESC LIMIT :limit
    ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settings = getSettings($conn);
        foreach ($result as &$row) {
            $formattedDateTime = formatDateAndTime($row['created_at'], $settings);
            $row['created_at'] = $formattedDateTime['time'];
            $row['created_date'] = $formattedDateTime['date'];
            $row['word_amounts'] = convertNumberToWords(floatval($row['from_amount']));
        }

        echo json_encode($result);

    } catch(PDOException $e) {
        error_log("Error: " . $e->getMessage());
    }
    $conn = null;
}

/////////
if ($action === "search_transfers") {
    header('Content-Type: application/json');

    $searchQuery = $_GET['query'];

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT t.transfer_id, t.from_amount, t.to_amount, t.income_amount, t.from_account_type,
        CASE
            WHEN t.cut_vodafone IS NOT NULL THEN CONCAT(t.description, ' قص ', t.cut_vodafone)
            ELSE t.description
        END AS description,
        t.created_at,
        CASE
            WHEN t.from_account_type = 'funds' THEN a1.account_Sname
            WHEN t.from_account_type = 'customer' THEN c1.full_name
        END AS from_account_name,
        CASE
            WHEN t.to_account_type = 'funds' THEN a2.account_Sname
            WHEN t.to_account_type = 'customer' THEN c2.full_name
        END AS to_account_name,
        ct1.sname AS from_type_converted,
        ct2.sname AS to_type_converted,
        a3.account_Sname AS income_fund_converted,
        CASE
            WHEN t.from_account_type = 'funds' THEN cur1.currency_symbole
            WHEN t.from_account_type = 'customer' THEN cur4.currency_symbole
        END AS from_account_currency,
        CASE
            WHEN t.from_account_type = 'funds' THEN cur1.currency_sname
            WHEN t.from_account_type = 'customer' THEN cur4.currency_sname
        END AS currency_sname,
        CASE
            WHEN t.to_account_type = 'funds' THEN cur2.currency_symbole
            WHEN t.to_account_type = 'customer' THEN cur6.currency_symbole
        END AS to_account_currency,
        cur3.currency_symbole AS income_fund_currency
    FROM transfers t
    LEFT JOIN accounting a1 ON t.from_account_id = a1.account_number AND t.from_account_type = 'funds'
    LEFT JOIN customer c1 ON t.from_account_id = c1.customer_id AND t.from_account_type = 'customer'
    LEFT JOIN accounting a2 ON t.to_account_id = a2.account_number AND t.to_account_type = 'funds'
    LEFT JOIN customer c2 ON t.to_account_id = c2.customer_id AND t.to_account_type = 'customer'
    LEFT JOIN convert_types ct1 ON t.from_type = ct1.name
    LEFT JOIN convert_types ct2 ON t.to_type = ct2.name
    LEFT JOIN accounting a3 ON t.income_fund = a3.account_number
    LEFT JOIN currency cur1 ON a1.currency_id = cur1.currency_id
    LEFT JOIN currency cur2 ON a2.currency_id = cur2.currency_id
    LEFT JOIN currency cur3 ON a3.currency_id = cur3.currency_id
    LEFT JOIN customer_transaction ct4 ON t.tr_from_id = ct4.id AND t.from_account_type = 'customer'
    LEFT JOIN currency cur4 ON ct4.tr_currency = cur4.currency_id
    LEFT JOIN customer_transaction ct5 ON t.tr_to_id = ct5.id AND t.to_account_type = 'customer'
    LEFT JOIN currency cur6 ON ct5.tr_currency = cur6.currency_id
    WHERE t.Delete_Date IS NULL AND (t.transfer_id LIKE :query)
    ORDER BY t.created_at DESC
    ";

        $stmt = $conn->prepare($query);
        $stmt->execute(['query' => '%' . $searchQuery . '%']);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $settings = getSettings($conn);
        foreach ($result as &$row) {
            $formattedDateTime = formatDateAndTime($row['created_at'], $settings);
            $row['created_at'] = $formattedDateTime['time'];
            $row['created_date'] = $formattedDateTime['date'];
        }
        
        echo json_encode($result);

    } catch(PDOException $e) {
        error_log("Error: " . $e->getMessage());
    }
    $conn = null;
}

///////////
if ($action === "delete_bonds") {
    // جلب المعرّف الخاص بالعملية
    $transfer_id = $_POST['transfer_id'];

    try {
        // بداية معاملة جديدة
        $conn->beginTransaction();

        // جلب التحويل
        $stmt = $conn->prepare("SELECT * FROM transfers WHERE transfer_id = ?");
        $stmt->execute([$transfer_id]);
        $transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $processed_funds = [];

        foreach ($transfers as $transfer) {
            if ($transfer['from_account_type'] == 'funds' && !is_null($transfer['from_account_id'])) {
                $stmt = $conn->prepare("SELECT * FROM accounting WHERE account_number = ?");
                $stmt->execute([$transfer['from_account_id']]);
                $account = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($transfer['from_type'] == 'deposit') {
                    $account['account_amount'] -= $transfer['from_amount'];
                } else if ($transfer['from_type'] == 'withdraw') {
                    $account['account_amount'] += $transfer['from_amount'];
                }

                $stmt = $conn->prepare("UPDATE accounting SET account_amount = ? WHERE account_number = ?");
                $stmt->execute([$account['account_amount'], $account['account_number']]);
            }

            if ($transfer['to_account_type'] == 'funds' && !is_null($transfer['to_account_id'])) {
                $stmt = $conn->prepare("SELECT * FROM accounting WHERE account_number = ?");
                $stmt->execute([$transfer['to_account_id']]);
                $account = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($transfer['to_type'] == 'deposit') {
                    $account['account_amount'] -= $transfer['to_amount'];
                } else if ($transfer['to_type'] == 'withdraw') {
                    $account['account_amount'] += $transfer['to_amount'];
                }

                $stmt = $conn->prepare("UPDATE accounting SET account_amount = ? WHERE account_number = ?");
                $stmt->execute([$account['account_amount'], $account['account_number']]);
            }

            if (!in_array($transfer['income_fund'], $processed_funds) && !is_null($transfer['income_fund'])) {
                $stmt = $conn->prepare("SELECT * FROM accounting WHERE account_number = ?");
                $stmt->execute([$transfer['income_fund']]);
                $account = $stmt->fetch(PDO::FETCH_ASSOC);
                $account['account_amount'] -= $transfer['income_amount'];
                $stmt = $conn->prepare("UPDATE accounting SET account_amount = ? WHERE account_number = ?");
                $stmt->execute([$account['account_amount'], $account['account_number']]);
        
                $processed_funds[] = $transfer['income_fund'];
            }

            if ($transfer['from_account_type'] == 'customer' && !is_null($transfer['tr_from_id'])) {
                $stmt = $conn->prepare("UPDATE customer_transaction SET Delete_Date = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$transfer['tr_from_id']]);
            }
            
            if ($transfer['to_account_type'] == 'customer' && !is_null($transfer['tr_to_id'])) {
                $stmt = $conn->prepare("UPDATE customer_transaction SET Delete_Date = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([$transfer['tr_to_id']]);
            }

            $stmt = $conn->prepare("UPDATE transfers SET Delete_Date = NOW() WHERE transfer_id = ?");
            $stmt->execute([$transfer_id]);
        }

        // إلتزام المعاملة
        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // إذا حدث خطأ، ألغِ المعاملة
        $conn->rollBack();

        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}


///////////////////////
if ($action === "search_balance_customers") {
    // قراءة الاستعلام من بيانات POST
    $input = file_get_contents('php://input');
    $postData = json_decode($input, true);
    $query = $postData['query'];

    // جلب جميع العملات المتاحة
    $stmt = $conn->prepare("SELECT currency_id, currency_sname, currency_symbole FROM currency WHERE Delete_Date IS NULL");
    $stmt->execute();
    $currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // استعلام لجلب العملاء فقط
    $sql = "SELECT full_name AS name, customer_id AS id, 'customer' AS type
            FROM customer 
            WHERE (full_name LIKE :query OR customer_phone LIKE :query OR customer_id LIKE :query) AND Delete_Date IS NULL";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['query' => "%$query%"]);
    $allEntities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // معالجة النتائج
    foreach ($allEntities as $index => $entity) {
        $balances = [];
        foreach ($currencies as $currency) {
            $id_value = $entity['id'];
            $currency_id = isset($currency['currency_id']) ? $currency['currency_id'] : null;


            // حساب الرصيد للعملاء فقط
            $balance = getBalance($conn, 'customer', 'customer_id', $id_value, $currency_id);

            if ($balance != 0) {
                $balances[] = $balance . ' ' . $currency['currency_symbole'];
            }
        }

        // تخزين الرصيد مع بقية البيانات
        $allEntities[$index]['balance'] = implode(', ', $balances);
        unset($allEntities[$index]['id']); // حذف المفتاح id من الإخراج
    }

    // إرسال النتيجة بتنسيق JSON
    header('Content-Type: application/json');
    echo json_encode($allEntities, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

// وظيفة لحساب الرصيد
function getBalance($conn, $table, $id_column, $id_value, $currency_id) {
    $params = [
        ':id_value' => $id_value,
        ':currency_id' => $currency_id
    ];

    $stmt = $conn->prepare("SELECT 
                            (IFNULL(SUM(CASE WHEN tr_type = 'deposit' THEN tr_amount ELSE 0 END), 0) - 
                             IFNULL(SUM(CASE WHEN tr_type = 'withdraw' THEN tr_amount ELSE 0 END), 0)) as balance 
                             FROM {$table}_transaction 
                             WHERE {$id_column} = :id_value AND tr_currency = :currency_id AND Delete_Date IS NULL");
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC)['balance'] ?? 0;
}

?>