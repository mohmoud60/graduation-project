<?php
if (!function_exists('getSettings')) {
    function getSettings($conn) {
        $stmt = $conn->prepare("SELECT * FROM settings LIMIT 1");
        $stmt->execute();
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        return $settings;
    }
}

if (!function_exists('formatDateAndTime')) {
    function formatDateAndTime($date_time, $settings) {
        // ضبط توقيت البيانات كتوقيت عالمي منسق (UTC)
        $utcDateTime = new DateTime($date_time, new DateTimeZone('UTC'));
    
        // تحديد منطقة الوقت المطلوبة
        $utcDateTime->setTimeZone(new DateTimeZone($settings['time_zone']));
    
        if ($settings['time_format'] == 12) {
            $timeFormat = 'h:i A';
        } else {
            $timeFormat = 'H:i';
        }
    
        // تنسيق الوقت بنظام 12 ساعة أو 24 ساعة
        $time = $utcDateTime->format($timeFormat);
    
        // تنسيق التاريخ بنظام m-d-Y أو d-m-Y أو m/d/Y أو d/m/Y
        $date = $utcDateTime->format($settings['date_format']);
    
        return [
            'date' => $date,
            'time' => $time,
        ];
    }
}
?>
