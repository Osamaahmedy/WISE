<?php
require 'vendor/autoload.php'; // مكتبة PhpSpreadsheet
require 'DB.php'; // الاتصال بقاعدة البيانات

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // كتابة رؤوس الأعمدة
    $sheet->setCellValue("A1", "الاسم الأول");
    $sheet->setCellValue("B1", "اسم العائلة");
    $sheet->setCellValue("C1", "رقم الهاتف");

    // كتابة البيانات
    $rowIndex = 2; // البدء من الصف الثاني
    while ($row = mysqli_fetch_assoc($result)) {
        $sheet->setCellValue("A$rowIndex", $row['firstName']);
        $sheet->setCellValue("B$rowIndex", $row['lastName']);
        $sheet->setCellValue("C$rowIndex", $row['phoneNumber']);
        $rowIndex++;
    }

    // تصدير الملف
    $writer = new Xlsx($spreadsheet);
    $fileName = "users_export.xlsx";

    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=$fileName");

    $writer->save("php://output");
    exit;
} else {
    echo "لا توجد بيانات لتصديرها.";
}
?>