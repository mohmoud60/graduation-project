<?php
// Include database connection file
include 'connection.php';
include 'authenticator.php';


// Fetch the settings from the database
$sql = "SELECT * FROM settings WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

if ($result) {
    $settings = [
        'timeZone' => $result['time_zone'],
        'dateFormat' => $result['date_format'],
        'timeFormat' => $result['time_format'],
        'financialYearStart' => $result['fiscal_year_start'],
        'exchange_rate_sub' => $result['exchange_rate_sub'],
    ];
} else {
    $settings = null;
}

header('Content-Type: application/json');
echo json_encode($settings);
?>
