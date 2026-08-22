<?php
// 1. تضمين ملف الاتصال بقاعدة البيانات
require_once 'db.php';

// 2. مصفوفة البيانات الشخصية
$cv_data = [
    "name" => "أميرة الكريمي",
    "title" => "مطور ويب متكامل (Full-Stack Developer)",
    "email" => "amir@example.com",
    "phone" => "+967 777 777 777",
    "about" => "مطور ويب شغوف ببناء تطبيقات ويب حديثة وسريعة باستخدام أحدث التقنيات البرمجية. أمتلك خبرة واسعة في تحويل الأفكار والملفات التصميمية إلى منتجات رقمية متكاملة وسهلة الاستخدام، مع التركيز على كتابة كود نظيف وقابل للتطوير.",
    "skills" => ["PHP & MySQL", "Laravel Framework", "HTML5 & CSS3", "JavaScript (ES6)", "Bootstrap 5", "Git & GitHub", "RESTful APIs"],
    "experience" => [
        ["role" => "مطور ويب مستقل (Freelancer)", "period" => "2024 - حتى الآن", "desc" => "تطوير مواقع مخصصة للعملاء، تحسين أداء المواقع، وبناء لوحات تحكم مخصصة لإدارة المحتوى وسرعة معالجة البيانات."],
        ["role" => "متدرب في شركة برمجيات", "period" => "2023 - 2024", "desc" => "المساعدة في تطوير الواجهات الأمامية وإصلاح الأخطاء البرمجية في الأنظمة القائمة والمشاركة في ربط الواجهات بالـ APIs."]
    ]
];

// 3. تعريف المتغيرات بقيم افتراضية فارغة لمنع ظهور أي تحذير (Warning) نهائياً
$message_status = "";
$message_class = "";

// 4. معالجة بيانات نموذج الاتصال بأمان تام
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['sender_name'] ?? ''));
    $email = filter_var(trim($_POST['sender_email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['sender_message'] ?? ''));

    if (empty($name) || empty($email) || empty($message)) {
        $message_status = "يرجى ملء جميع الحقول المطلوبة.";
        $message_class = "alert-danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_status = "البريد الإلكتروني غير صحيح.";
        $message_class = "alert-danger";
    } else {
        try {
            $sql = "INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message
            ]);

            $message_status = "شكراً لك يا " . $name . "، تم استلام رسالتك وحفظها بنجاح!";
            $message_class = "alert-success";
        } catch (PDOException $e) {
            $message_status = "عذراً، حدث خطأ أثناء حفظ الرسالة في قاعدة البيانات.";
            $message_class = "alert-danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>السيرة الذاتية | <?php echo $cv_data['name']; ?></title>
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://jsdelivr.net">
    <!-- Google Fonts (Cairo) -->
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { background-color: #f4f7f6; font-family: 'Cairo', sans-serif; color: #495057; }
        .hero-section { background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 80px 0; border-bottom: 5px solid #0d6efd; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .contact-info span { background: rgba(255, 255, 255, 0.1); padding: 8px 16px; border-radius: 30px; margin: 5px; display: inline-block; font-size: 0.95rem; }
        .card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); transition: transform 0.3s ease; }
        .card:hover { transform: translateY(-5px); }
        .section-title { font-weight: 700; color: #1e3c72; position: relative; padding-bottom: 10px; margin-bottom: 20px; }
        .section-title::after { content: ''; position: absolute; bottom: 0; right: 0; width: 50px; height: 3px; background-color: #0d6efd; border-radius: 2px; }
        .badge-skill { font-size: 0.9rem; padding: 10px 18px; margin: 5px; border-radius: 50px; background-color: #eef2f7 !important; color: #1e3c72 !important; border: 1px solid #dbe2ec; font-weight: 600; }
        .timeline-item { border-right: 3px solid #dbe2ec; padding-right: 20px; position: relative; }
        .timeline-item::before { content: ''; position: absolute; right: -8px; top: 5px; width: 13px; height: 13px; background-color: #0d6efd; border-radius: 50%; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #dee2e6; background-color: #fcfdfe; }
        .btn-submit { border-radius: 10px; padding: 12px; font-weight: 600; background: linear-gradient(135deg, #1e3c72, #2a5298); border: none; }
        .btn-submit:hover { background: linear-gradient(135deg, #2a5298, #1e3c72); }
        
               /* 🛠️ كود التنسيق المطور الخاص بالطباعة وتحويل الـ PDF */
        @media print {
            body { background-color: #ffffff; color: #000000; }
            .hero-section { background: #1e3c72 !important; color: white !important; padding: 40px 0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .card { box-shadow: none !important; transform: none !important; padding: 0 !important; margin-bottom: 25px !important; }
            .col-lg-4, .col-lg-8 { width: 100% !important; }
            
            /* إخفاء العناصر التفاعلية مثل زر التحميل، ونموذج تواصل معي، والإشعارات عند استخراج الـ PDF */
            .btn-download-cv, .card:has(form), .alert { display: none !important; } 
            
            /* تم الإصلاح: إجبار المهارات على الترتيب بشكل أفقي متناسق أثناء الطباعة */
            .d-flex.flex-wrap { display: flex !important; flex-wrap: wrap !important; flex-direction: row !important; }
            .badge-skill { 
                background-color: #f4f7f6 !important; 
                color: #1e3c72 !important;
                border: 1px solid #dbe2ec !important; 
                display: inline-block !important;
                margin: 4px !important;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
            }
        }

    </style>
</head>
<body>

    <header class="hero-section text-center mb-5">
        <div class="container">
            <h1 class="display-4 mb-2"><?php echo $cv_data['name']; ?></h1>
            <p class="lead text-white-50 fs-4 mb-4"><?php echo $cv_data['title']; ?></p>
            <div class="contact-info mb-4">
                <span>✉️ <?php echo $cv_data['email']; ?></span>
                <span>📞 <?php echo $cv_data['phone']; ?></span>
            </div>
            
            <!-- زر تحميل السيرة الذاتية PDF المضاف حديثاً -->
            <button onclick="window.print();" class="btn btn-light btn-download-cv text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                📄 تحميل السيرة الذاتية (PDF)
            </button>
        </div>
    </header>

    <main class="container mb-5">
        <div class="row g-4">
            
            <div class="col-lg-8">
                <div class="card p-4 mb-4">
                    <h3 class="section-title">نبذة عني</h3>
                    <p class="lead fs-6 text-secondary lh-lg mb-0"><?php echo $cv_data['about']; ?></p>
                </div>

                <div class="card p-4">
                    <h3 class="section-title">الخبرات المهنية</h3>
                    <div class="mt-3">
                        <?php foreach($cv_data['experience'] as $exp): ?>
                            <div class="timeline-item mb-4">
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                                    <h5 class="fw-bold text-dark mb-1"><?php echo $exp['role']; ?></h5>
                                    <span class="badge bg-light text-primary border px-3 py-2 rounded-pill"><?php echo $exp['period']; ?></span>
                                </div>
                                <p class="text-muted lh-base mb-0"><?php echo $exp['desc']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 mb-4">
                    <h3 class="section-title">المهارات التقنية</h3>
                    <div class="d-flex flex-wrap mt-3">
                        <?php foreach($cv_data['skills'] as $skill): ?>
                            <span class="badge badge-skill"><?php echo $skill; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card p-4">
                    <h3 class="section-title">تواصل معي</h3>
                    
                    <?php if (!empty($message_status)): ?>
                        <div class="alert <?php echo $message_class; ?> alert-dismissible fade show mt-3" role="alert">
                            <?php echo $message_status; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="mt-3">
                        <div class="mb-3">
                            <label for="sender_name" class="form-label fw-semibold text-secondary">الاسم الكريم</label>
                            <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="أدخل اسمك الكامل" required>
                        </div>
                        <div class="mb-3">
                            <label for="sender_email" class="form-label fw-semibold text-secondary">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="sender_email" name="sender_email" placeholder="name@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="sender_message" class="form-label fw-semibold text-secondary">نص الرسالة</label>
                            <textarea class="form-control" id="sender_message" name="sender_message" rows="4" placeholder="اكتب رسالتك هنا..." required></textarea>
