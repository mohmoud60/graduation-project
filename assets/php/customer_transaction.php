<?php

include 'connection.php';
include 'time_settings.php';
include 'authenticator.php';





if (isset($_GET['from_date'])) {
    $customer_id = $_GET['customer_id'];
    $from_date = date('Y-m-d H:i:s', strtotime($_GET['from_date']));
    $to_date = date('Y-m-d H:i:s', strtotime($_GET['to_date']));
    $transaction_type = $_GET['transaction_type'];
    $fund_types = isset($_GET['fund_type']) ? $_GET['fund_type'] : null; // التحقق من fund-types

    $settings = getSettings($conn);

    if ($transaction_type === 'currency') {
        $sql = "
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
            ce.created_at,
            exchange_rates.fund_sname AS currency_ex
        FROM 
            currency_exchange ce
        LEFT JOIN accounting acc_buy ON SUBSTRING_INDEX(ce.currency_ex, '_', 1) = acc_buy.account_number
        LEFT JOIN accounting acc_sell ON SUBSTRING_INDEX(ce.currency_ex, '_', -1) = acc_sell.account_number
        LEFT JOIN currency c_buy ON acc_buy.currency_id = c_buy.currency_id
        LEFT JOIN currency c_sell ON acc_sell.currency_id = c_sell.currency_id
        INNER JOIN exchange_rates ON ce.currency_ex = exchange_rates.currency_ex
        WHERE ce.reason_delete IS NULL 
        AND customer_id = :customer_id 
        AND DATE(ce.created_at) BETWEEN :from_date AND :to_date
        ";

        // إضافة شرط `fund_types` إذا كان موجودًا
        if (!empty($fund_types)) {
            $sql .= " AND ce.currency_ex = :fund_types";
        }

        $sql .= " ORDER BY ce.created_at;";

        $params = [
            ':customer_id' => $customer_id,
            ':from_date' => $from_date,
            ':to_date' => $to_date
        ];

        if (!empty($fund_types)) {
            $params[':fund_types'] = $fund_types;
        }

        // سجل الاستعلام
        error_log("SQL Query: " . $sql);
        error_log("Parameters: " . json_encode($params));

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $key => $result) {
                $formattedDateTime = formatDateAndTime($result['created_at'], $settings);
                $results[$key]['date'] = $formattedDateTime['date'];
                $results[$key]['time'] = $formattedDateTime['time'];
            }

            echo json_encode($results);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $sql = "
        WITH AccumulatedBalances AS (
            SELECT 
                tt.*,
                c.currency_sname, 
                c.currency_symbole,
                SUM(
                    CASE 
                        WHEN tt.tr_type = 'deposit' AND tt.Delete_Date IS NULL THEN tt.tr_amount
                        WHEN tt.tr_type = 'withdraw' AND tt.Delete_Date IS NULL THEN -tt.tr_amount
                        ELSE 0 
                    END
                ) OVER (PARTITION BY tt.customer_id, tt.tr_currency ORDER BY tt.tr_timestamp ASC) AS accumulated_balance,
                CASE 
                    WHEN tt.tr_type = 'deposit' THEN 'إيداع'
                    WHEN tt.tr_type = 'withdraw' THEN 'سحب'
                    ELSE tt.tr_type
                END AS tr_type_label
            FROM customer_transaction tt 
            JOIN currency c ON tt.tr_currency = c.currency_id
            WHERE tt.customer_id = :customer_id 
            AND tt.Delete_Date IS NULL
        )
        SELECT * FROM AccumulatedBalances
        WHERE DATE(tr_timestamp) BETWEEN :from_date AND :to_date
        ";

        // إضافة شرط `fund_types` إذا كان موجودًا
        if (!empty($fund_types)) {
            $sql .= " AND tr_currency = :fund_types";
        }

        if (!empty($transaction_type) && $transaction_type !== 'both') {
            $sql .= " AND tr_type = :transaction_type";
        }

        $sql .= " ORDER BY tr_timestamp ASC;";

        $params = [
            ':customer_id' => $customer_id,
            ':from_date' => $from_date,
            ':to_date' => $to_date
        ];

        if (!empty($fund_types)) {
            $params[':fund_types'] = $fund_types;
        }

        if (!empty($transaction_type) && $transaction_type !== 'both') {
            $params[':transaction_type'] = $transaction_type;
        }

        // سجل الاستعلام والمعاملات
        error_log("SQL Query: " . $sql);
        error_log("Parameters: " . json_encode($params));

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $key => $result) {
                $formattedDateTime = formatDateAndTime($result['tr_timestamp'], $settings);
                $results[$key]['tr_timestamp'] = $formattedDateTime['date'] . ' ' . $formattedDateTime['time'];
            }

            echo json_encode($results);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}




if (isset($_POST['tr_type'])) {
$tr_type = $_POST['tr_type'];
$tr_amount = $_POST['tr_amount'];
$tr_currency = $_POST['tr_currency'];
$tr_descripcion = $_POST['tr_descripcion'];
$customer_id = $_POST['customer_id'];

$sql = "INSERT INTO customer_transaction(tr_type, tr_amount, tr_descripcion, customer_id, tr_currency) VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(1, $tr_type, PDO::PARAM_STR);
$stmt->bindParam(2, $tr_amount, PDO::PARAM_STR);
$stmt->bindParam(3, $tr_descripcion, PDO::PARAM_STR);
$stmt->bindParam(4, $customer_id, PDO::PARAM_INT);
$stmt->bindParam(5, $tr_currency, PDO::PARAM_STR);

if($stmt->execute()){
  echo 'success';
}else{
  echo 'error';
}
}

?>
