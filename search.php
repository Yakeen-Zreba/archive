<?php
// اتصال بقاعدة البيانات
$mysqli = new mysqli("localhost", "root", "", "archives");

// التحقق من وجود اتصال
if ($mysqli->connect_error) {
    die("فشل الاتصال: " . $mysqli->connect_error);
}

$result = null; // تعريف المتغير قبل استخدامه

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search_id']) && !empty($_POST['search_id'])) {
        $search_id = $mysqli->real_escape_string($_POST['search_id']);

        // إعداد الاستعلام
        $query = "SELECT * FROM entries WHERE indicator_no = ?";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            // ربط المعلمات
            $stmt->bind_param("s", $search_id);

            // تنفيذ الاستعلام
            $stmt->execute();

            // الحصول على النتيجة
            $result = $stmt->get_result();

            // التحقق مما إذا كانت هناك نتائج
            if ($result->num_rows == 0) {
                $message = "رقم الإشارة غير موجود.";
            } else {
                $message = null;
            }
            
            // إغلاق البيان
            $stmt->close();
        } else {
            $message = "خطأ في الاستعلام: " . $mysqli->error;
        }
    } else {
        $message = "يرجى إدخال رقم الإشارة.";
    }
}

// إغلاق الاتصال بقاعدة البيانات
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>بحث</title>
    <link rel="icon" href="logos/short_logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
         .navbar {
            background-color: #8056fb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 1rem;
        }
        .navbar .btn {
            color: #fff;
            background-color: #8056fb;
            border: none;
            font-size: 20px;
        }
        .navbar .btn:hover {
            background-color: #6a42c9;
        }
        .navbar .logo {
            max-height: 30px;
        }
        .btn-back {
            color: #fff;
            background-color: #8056fb;
            border: none;
            font-size: 20px;
        }
        .btn-back:hover {
            background-color: #6a42c9;
        }
        body {
            background-color: #f8f9fa;
            direction: rtl;
            text-align: right;
        }
        .btn-primary {
            border: 1px solid #8056fb;
            background-color: #fff;
            color: #8056fb;
        }
        .btn-primary:hover {
            background-color: #8056fb;
            color: #fff;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">
            <img src="logos/white_logo.png" class="logo" alt="Logo">
        </a>
        <a class="btn btn-back" href="index.php">
            <i class="fas fa-arrow-left"></i>
        </a>
    </nav>

    <div class="container mt-4">
        <!--<h2 class="text-center mb-4">بحث عن رقم الإشارة</h2>-->
        
        <!-- نموذج البحث -->
        <form action="search.php" method="post">
            <div class="form-group">
                <label for="search_id">الرقم الإشارة:</label>
                <input type="text" class="form-control" id="search_id" name="search_id" required>
            </div>
            <button type="submit" class="btn btn-primary">بحث</button>
        </form>

        <!-- عرض الرسائل -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info mt-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- عرض النتائج -->
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>تاريخ المستند</th>
                        <th>الرقم الاشاري</th>
                        <th>من</th>
                        <th>الى</th>
                        <th>الموضوع</th>
                        <th>مسار الملف</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['indicator_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['from']); ?></td>
                            <td><?php echo htmlspecialchars($row['to']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($row['pdf_path']); ?>" target="_blank"><?php echo htmlspecialchars($row['pdf_path']); ?></a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
