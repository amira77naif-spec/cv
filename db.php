<?php
$host = 'localhost';
$db_name = 'mycv_db';
$username = 'root'; // المستخدم الافتراضي في XAMPP
$password = '';     // كلمة المرور الافتراضية تكون فارغة في XAMPP

try {
    // إنشاء اتصال آمن باستخدام PDO وتحديد الترميز utf8mb4 لدعم اللغة العربية
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // ضبط وضع الأخطاء لإظهار الاستثناءات عند حدوث خلل
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $exception) {
    // في حال فشل الاتصال يتم إيقاف البرنامج وعرض الخطأ
    die("فشل الاتصال بقاعدة البيانات: " . $exception->getMessage());
}
?>
