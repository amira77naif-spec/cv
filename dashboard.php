<?php
session_start();
require_once 'db.php';

// حماية الصفحة: إذا لم يكن مسجلاً، يتم طرده إلى صفحة تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// معالجة طلب الحذف بأمان
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $sql = "DELETE FROM contacts WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header("Location: dashboard.php?status=deleted");
        exit;
    } catch (PDOException $e) {
        $error_msg = "فشل حذف الرسالة.";
    }
}

// جلب جميع الرسائل من الأحدث إلى الأقدم
try {
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY id DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("خطأ في جلب البيانات: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرسائل الواردة | لوحة التحكم</title>
    <link rel="stylesheet" href="https://jsdelivr.net">
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Cairo', sans-serif; }
        .navbar { background-color: #1e3c72; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

    <!-- شريط التنقل العلوي -->
    <nav class="navbar navbar-dark shadow-sm mb-5">
        <div class="container">
            <span class="navbar-brand fw-bold">إدارة السيرة الذاتية</span>
            <a href="logout.php" class="btn btn-outline-light btn-sm px-3 rounded-pill">تسجيل الخروج</a>
        </div>
    </nav>

    <main class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark mb-0">الرسائل الواردة (<?php echo count($messages); ?>)</h2>
            <a href="index.php" target="_blank" class="btn btn-secondary btn-sm px-3 rounded-pill">عرض الموقع 🌐</a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                تم حذف الرسالة بنجاح.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- جدول استعراض الرسائل -->
        <div class="card p-4">
            <?php if (count($messages) == 0): ?>
                <p class="text-center text-muted mb-0 my-4">لا توجد رسائل واردة حالياً.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th style="width: 45%;">الرسالة</th>
                                <th>التاريخ</th>
                                <th class="text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $msg): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="text-decoration-none"><?php echo htmlspecialchars($msg['email']); ?></a></td>
                                    <td class="text-secondary lh-base"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                                    <td class="text-muted small"><?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></td>
                                    <td class="text-center">
                                        <a href="dashboard.php?delete=<?php echo $msg['id']; ?>" class="btn btn-danger btn-sm rounded-pill px-3" onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه الرسالة؟');">حذف</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://jsdelivr.net"></script>
</body>
</html>
