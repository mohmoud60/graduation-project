<?php
session_start();
require_once 'connection.php';
include 'authenticator.php';

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;
if ($action === "update_income") {
    try {
        $usd = floatval($_POST['dollarAmount']);  // The USD amount that you need to add
        $ils = floatval($_POST['ilsAmount']);  
        $username = $_SESSION['username'];  

        // Start a transaction
        $conn->beginTransaction();

        // Subtract amounts from accounting table
        $accounting_query = "UPDATE accounting SET account_amount = CASE account_number WHEN 10401 THEN account_amount - :usd WHEN 10400 THEN account_amount - :ils END WHERE account_number IN (10401, 10400)";
        $stmt = $conn->prepare($accounting_query);
        $stmt->bindParam(':usd', $usd);
        $stmt->bindParam(':ils', $ils);
        $stmt->execute();

        // Update currency_fund table
        $fund_query = "UPDATE currency_fund SET fund_amount = CASE fund_name WHEN 'USD' THEN fund_amount + :usd WHEN 'ILS' THEN fund_amount + :ils END WHERE fund_name IN ('USD', 'ILS')";
        $stmt = $conn->prepare($fund_query);
        $stmt->bindParam(':usd', $usd);
        $stmt->bindParam(':ils', $ils);
        $stmt->execute();

        // Add entries to income_transfer table
        $transfer_query = 'INSERT INTO income_transfer (usd_amount, ils_amount , created_by) VALUES (:usd, :ils , :username)';
        $stmt = $conn->prepare($transfer_query);
        $stmt->bindParam(':usd', $usd);
        $stmt->bindParam(':ils', $ils);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Update accounting table
        $accounting_query = 'UPDATE accounting SET account_amount = 0 WHERE account_type = 3200';
        $stmt = $conn->prepare($accounting_query);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        echo 'success';
    } catch (PDOException $e) {
        // Rollback the transaction
        $conn->rollback();
        echo 'error: ' . $e->getMessage();
    }
}




?>