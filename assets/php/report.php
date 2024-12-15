<?php
session_start();
require_once 'connection.php';
include 'time_settings.php';
include 'authenticator.php';

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;

if ($action === "company_expenses") {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];

    // Get the bonds data
    $stmt = $conn->prepare("SELECT * FROM bonds WHERE DATE(created_at) BETWEEN :from_date AND :to_date AND is_special = 1 AND Delete_Date IS NULL ");
    $stmt->bindParam(':from_date', $from_date);
    $stmt->bindParam(':to_date', $to_date);
    $stmt->execute();
    $settings = getSettings($conn);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the employee_transactions data
    $stmt = $conn->prepare("SELECT * FROM employee_transactions WHERE DATE(date) BETWEEN :from_date AND :to_date AND Delete_Date IS NULL");
    $stmt->bindParam(':from_date', $from_date);
    $stmt->bindParam(':to_date', $to_date);
    $stmt->execute();
    $employee_transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge and format the results
    $formattedResults = [];

    foreach ($results as $result) {
        $formattedResult = $result;
        $formattedResult['bond_type'] = $result['bond_type'] == 'exchange' ? 'سند صرف' : 'سند قبض';

        $formattedDateTime = formatDateAndTime($result['created_at'], $settings);
        $formattedResult['created_date'] = $formattedDateTime['date'];
        $formattedResult['created_time'] = $formattedDateTime['time'];
        $formattedResults[] = $formattedResult;
    }

    foreach ($employee_transactions as $transaction) {
        $formattedResult = [];
        $formattedResult['bond_number'] = $transaction['transaction_id'];
        $formattedResult['bond_type'] = $transaction['transaction_type'] === 'Salary' ? 'سند دفع راتب' : 'سند دفع سلفة نقدية';

        // Get the employee name
        $stmt = $conn->prepare("SELECT Employee_FullName FROM employee WHERE Employee_id = :employee_id");
        $stmt->bindParam(':employee_id', $transaction['employee_id']);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        $formattedResult['bond_name'] = $employee['Employee_FullName'];

        $formattedResult['amount'] = $transaction['amount'];
        $formattedResult['currency'] = '₪';
        $formattedResult['description'] = $transaction['transaction_type'] === 'Salary' ? 'دفع راتب' : 'دفع سلفة نقدية';

        $formattedDateTime = formatDateAndTime($transaction['date'], $settings);
        $formattedResult['created_date'] = $formattedDateTime['date'];
        $formattedResult['created_time'] = $formattedDateTime['time'];

        $formattedResults[] = $formattedResult;
    }

    echo json_encode($formattedResults);
}


if ($action === "daily_report") {
    $from_date = $_GET['from_date'];

    try {
        // إعداد وتنفيذ الاستعلام الأول للحصول على بيانات الصرف
        $stmt = $conn->prepare("
        SELECT 
            ce.type, 
            ce.currency_ex, 
            REPLACE(ce.order_id, 'O-', '') as order_id, 
            ce.quantity, 
            ce.exchange_rate, 
            ce.total, 
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
        WHERE 
            ce.reason_delete IS NULL 
        AND DATE(ce.created_at) = :from_date
        ORDER BY 
            ce.created_at;
        ");
        $stmt->bindParam(':from_date', $from_date);
        $stmt->execute();

        $settings = getSettings($conn);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $formattedResults = [];

        foreach ($results as $result) {
            $formattedResult = $result;
            $formattedDateTime = formatDateAndTime($result['created_at'], $settings);
            $formattedResult['created_date'] = $formattedDateTime['date'] . ' ' . $formattedDateTime['time'];
            $formattedResults[] = $formattedResult;
        }

        // إعداد وتنفيذ الاستعلام الثاني للحصول على البيانات المالية
        $stmt2 = $conn->prepare("
        WITH 
        Morning AS (
            SELECT 0 AS morning_total
        ),
        Evening AS (
            SELECT 0 AS evening_total
        ),
        BondsExpenseTotal AS (
            SELECT
                SUM(
                    amount * COALESCE(
                        (SELECT buy_rate 
                         FROM exchange_rates 
                         WHERE currency_ex = CONCAT(fund_name, '_10201')), 
                    1)
                ) AS total
            FROM bonds
            WHERE bond_type = 'exchange' AND DATE(created_at) = :from_date AND Delete_Date IS NULL 
        ),
        EmployeeTransactionsTotal AS (
            SELECT 
                COALESCE(SUM(amount), 0) AS total
            FROM employee_transactions
            WHERE DATE(date) = :from_date AND Delete_Date IS NULL
        ),
        BondsExpense AS (
            SELECT
                COALESCE(BondsExpenseTotal.total, 0) + COALESCE(EmployeeTransactionsTotal.total, 0) AS bonds_expense_total
            FROM 
                (SELECT 1) dummy
            LEFT JOIN BondsExpenseTotal ON 1=1
            LEFT JOIN EmployeeTransactionsTotal ON 1=1
        ),
        BondsTotal AS (
            SELECT
                SUM(amount * COALESCE(
                    (SELECT buy_rate 
                     FROM exchange_rates 
                     WHERE currency_ex = CONCAT(fund_name, '_10201')), 
                1)
            ) AS total
            FROM bonds
            WHERE bond_type = 'receipt' AND DATE(created_at) = :from_date AND Delete_Date IS NULL 
        ),
        IncomeTransferTotal AS (
            SELECT
                SUM(
                    ils_amount + usd_amount * COALESCE(
                        (SELECT buy_rate 
                         FROM exchange_rates 
                         WHERE currency_ex = '10200_10201'), 
                    1)
                ) AS total
            FROM income_transfer 
            WHERE DATE(CreatedDate) = :from_date AND Delete_Date IS NULL 
        ),
        BondsReceipt AS (
            SELECT
                COALESCE(BondsTotal.total, 0) + COALESCE(IncomeTransferTotal.total, 0) AS bonds_receipt_total
            FROM 
                (SELECT 1) dummy
            LEFT JOIN BondsTotal ON 1=1
            LEFT JOIN IncomeTransferTotal ON 1=1
        ),
        TransfersExpense AS (
            SELECT 
                SUM(
                    CASE 
                        WHEN (from_type = 'withdraw' AND a.account_number = from_account_id) THEN from_amount
                        WHEN (to_type = 'withdraw' AND a.account_number = to_account_id) THEN to_amount
                        ELSE 0 
                    END
                ) AS transfers_expense_total
            FROM transfers t
            JOIN accounting a ON t.from_account_id = a.account_number OR t.to_account_id = a.account_number
            WHERE a.account_type = 3000 AND DATE(t.created_at) = :from_date AND t.Delete_Date IS NULL 
        ),
        TransfersReceipt AS (
            SELECT 
                SUM(
                    CASE 
                        WHEN (from_type = 'deposit' AND a.account_number = from_account_id) THEN from_amount
                        WHEN (to_type = 'deposit' AND a.account_number = to_account_id) THEN to_amount
                        ELSE 0 
                    END
                ) AS transfers_Receipt_total
            FROM transfers t
            JOIN accounting a ON t.from_account_id = a.account_number OR t.to_account_id = a.account_number
            WHERE a.account_type = 3000 AND DATE(t.created_at) = :from_date AND t.Delete_Date IS NULL 
        )
        SELECT
            COALESCE(m.morning_total, 0) as morning_total,
            COALESCE(e.evening_total, 0) as evening_total,
            COALESCE(e.evening_total, 0) - COALESCE(m.morning_total, 0) AS difference,
            COALESCE(be.bonds_expense_total, 0) + COALESCE(te.transfers_expense_total, 0) as bonds_expense_total,
            COALESCE(br.bonds_receipt_total, 0) + COALESCE(tr.transfers_Receipt_total, 0) as bonds_receipt_total,
            (COALESCE(e.evening_total, 0) - COALESCE(m.morning_total, 0)) + COALESCE(be.bonds_expense_total, 0) + COALESCE(te.transfers_expense_total, 0) - COALESCE(br.bonds_receipt_total, 0) - COALESCE(tr.transfers_Receipt_total, 0) AS total_prof
        FROM Morning AS m, Evening AS e, BondsExpense AS be, BondsReceipt AS br, TransfersExpense AS te, TransfersReceipt AS tr;
        ");
        $stmt2->bindParam(':from_date', $from_date);
        $stmt2->execute();

        $financialResults = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // دمج البيانات في استجابة JSON
        $finalResponse = [
            'exchangeData' => $formattedResults,
            'financialData' => $financialResults
        ];

        echo json_encode($finalResponse, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}



if ($action === "bonds_reports") {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];

    // Get the bonds data
    $stmt = $conn->prepare("SELECT * FROM bonds WHERE DATE(created_at) BETWEEN :from_date AND :to_date AND Delete_Date IS NULL ");
    $stmt->bindParam(':from_date', $from_date);
    $stmt->bindParam(':to_date', $to_date);
    $stmt->execute();
    $settings = getSettings($conn);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge and format the results
    $formattedResults = [];

    foreach ($results as $result) {
        $formattedResult = $result;
        $formattedResult['bond_type'] = $result['bond_type'] == 'exchange' ? 'سند صرف' : 'سند قبض';

        $formattedDateTime = formatDateAndTime($result['created_at'], $settings);
        $formattedResult['created_date'] = $formattedDateTime['date'];
        $formattedResult['created_time'] = $formattedDateTime['time'];
        $formattedResults[] = $formattedResult;
    }

    echo json_encode($formattedResults);
}



if ($action === "transfer_report") {
    header('Content-Type: application/json');

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $from_date = $_GET['from_date'];
        $to_date = $_GET['to_date'];

        $query = "
        SELECT 
        t.transfer_id, 
        t.from_amount, 
        t.to_amount, 
        t.income_amount,
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
    WHERE t.Delete_Date IS NULL AND DATE(t.created_at) BETWEEN :from_date AND :to_date
    ORDER BY t.created_at DESC
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':from_date', $from_date);
        $stmt->bindParam(':to_date', $to_date);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $settings = getSettings($conn);
        foreach ($result as &$row) {
            $formattedDateTime = formatDateAndTime($row['created_at'], $settings);
            $row['created_at'] = $formattedDateTime['time'];
            $row['created_date'] = $formattedDateTime['date'];
        }

        echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        echo json_encode(["error" => $e->getMessage()]);
    }
    $conn = null;
}

if ($action === "posting_income_report") {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];

    // Get the bonds data
    $stmt = $conn->prepare("SELECT * FROM income_transfer WHERE Delete_Date IS NULL AND DATE(CreatedDate) BETWEEN :from_date AND :to_date");
    $stmt->bindParam(':from_date', $from_date);
    $stmt->bindParam(':to_date', $to_date);
    $stmt->execute();
    $settings = getSettings($conn);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge and format the results
    $formattedResults = [];

    foreach ($results as $result) {
        $formattedResult = $result;
        $formattedDateTime = formatDateAndTime($result['CreatedDate'], $settings);
        $formattedResult['created_date'] = $formattedDateTime['date'];
        $formattedResult['created_time'] = $formattedDateTime['time'];
        $formattedResults[] = $formattedResult;
    }

    echo json_encode($formattedResults);
}

?>