<?php
require 'vendor/autoload.php'; // مكتبة PhpSpreadsheet
require 'DB.php'; // الاتصال بقاعدة البيانات

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['file']['name'])) {
    $fileName = $_FILES['file']['name'];
    $fileTmp = $_FILES['file']['tmp_name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    // التحقق من امتداد الملف
    if (!in_array($fileExt, ['xls', 'xlsx'])) {
        echo "الرجاء رفع ملف Excel فقط.";
        exit;
    }

    // قراءة ملف Excel
    $spreadsheet = IOFactory::load($fileTmp);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // التحقق من الحسابات المكررة
    $duplicates = [];
    $added = 0;

    foreach ($rows as $index => $row) {
        if ($index === 0) continue; // تخطي الصف الأول (رؤوس الأعمدة)

        $firstName = mysqli_real_escape_string($conn, $row[0]);
        $lastName = mysqli_real_escape_string($conn, $row[1]);
        $phoneNumber = mysqli_real_escape_string($conn, $row[2]);

        // التحقق من وجود الحساب بالفعل
        $checkQuery = "SELECT * FROM users WHERE phoneNumber = '$phoneNumber'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $duplicates[] = $phoneNumber;
        } else {
            // إضافة الحساب إلى قاعدة البيانات
            $insertQuery = "INSERT INTO users (firstName, lastName, phoneNumber) VALUES ('$firstName', '$lastName', '$phoneNumber')";
            if (mysqli_query($conn, $insertQuery)) {
                $added++;
            }
        }
    }

    // عرض النتائج
    echo "<h3>نتائج رفع الملف:</h3>";
    echo "<p>تمت إضافة $added حساب جديد.</p>";

    if (!empty($duplicates)) {
        echo "<h4>الحسابات المكررة:</h4>";
        echo "<ul>";
        foreach ($duplicates as $duplicate) {
            echo "<li>$duplicate</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>لا توجد حسابات مكررة.</p>";
    }
}
?>