<?php
// تأكد من تعيين الترميز واللغة العربية في PHP
header('Content-Type: text/html; charset=utf-8');
setlocale(LC_ALL, 'ar_AR.UTF-8', 'ar_AR.utf8', 'ar');

require 'vendor/autoload.php';

use Mpdf\Mpdf;

// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "archive";

$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}

// معالجة البيانات المستلمة من النموذج
$subject = $_POST['subject'];
$start_date = $_POST['start_date'];
$indicator_no = $_POST['indicator_no'];
$from = $_POST['from'];
$to = $_POST['to'];
$image_paths = [];

// معالجة تحميل الصور
if (!empty($_FILES['images']['name'][0])) {
    $target_dir = "images/";
    foreach ($_FILES['images']['name'] as $key => $image_name) {
        $target_file = $target_dir . basename($image_name);
        if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
            $image_paths[] = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }
}

// تحقق من وجود المجلد pdfs، وإذا لم يكن موجودًا، أنشئه
if (!is_dir('pdfs')) {
    mkdir('pdfs', 0777, true);
}

// إنشاء كائن mPDF
$mpdf = new Mpdf([
    'default_font' => 'dejavusans',
    'mode' => 'utf-8',
    'format' => 'A4'
]);



// بناء النص HTML لإدراجه في الملف PDF

$html = "<p style='text-align: right;'>التاريخ المستند  : $start_date  </p>";
$html .= "<p style='text-align: right;'>الرقم الإشاري :  $indicator_no  </p>";
$html .= "<p style='text-align: right;'>من:  $from  </p>";
$html .= "<p style='text-align: right;'>إلى:  $to  </p>";
$html .= "<p style='text-align: right;'>الموضوع: $subject  </p>";
foreach ($image_paths as $image_path) {
    $html .= '<div style="text-align: center; margin-top: 10px;"><img src="' . $image_path . '" style="width: 100%; height: 100%;"></div>';
}

// إضافة النص HTML إلى ملف PDF
$mpdf->WriteHTML($html);

// حفظ ملف PDF
$pdf_path = 'pdfs/' . $indicator_no ."__".time() . '.pdf';
$mpdf->Output($pdf_path, \Mpdf\Output\Destination::FILE);

// تحويل مسارات الصور إلى JSON لتخزينها في قاعدة البيانات
$image_paths_json = json_encode($image_paths);

// إدراج البيانات في قاعدة البيانات
$stmt = $conn->prepare("INSERT INTO entries (subject, start_date, indicator_no, `from`, `to`, pdf_path, images) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $subject, $start_date, $indicator_no, $from, $to, $pdf_path, $image_paths_json);


try {
    $stmt->execute();
    $message = " تم حفظ الملف ";
    $type = "success";
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) { 
        $message = "الرمز الإشاري موجود مسبقاً";
        $type = "danger";
    } else {
        $message = "Error: " . $e->getMessage();
        $type = "danger";
    }
}

$stmt->close();
$conn->close();

// إعادة التوجيه مع رسالة
header("Location: index.php?message=" . urlencode($message) . "&type=" . $type);
exit;
?>
