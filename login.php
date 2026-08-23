<?php
session_start();

// إذا كان المستخدم مسجل دخوله بالفعل، يتم توجيهه إلى لوحة التحكم
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// بيانات الدخول الافتراضية (يمكنك تغييرها هنا)
$admin_user = "admin";
$admin_pass = "admin123"; // في المشاريع الكبيرة يفضل تشفيرها واستدعائها من قاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة!";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | لوحة التحكم</title>
    <link rel="stylesheet" href="https://jsdelivr.net">
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Cairo', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { width: 100%; max-width: 400px; padding: 30px; border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="text-center fw-bold text-primary mb-4">لوحة التحكم</h3>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">اسم المستخدم</label>
                <input type="text" class="form-control py-2" id="username" name="username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                <input type="password" class="form-control py-2" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">دخول</button>
        </form>
    </div>
</body>
</html>
