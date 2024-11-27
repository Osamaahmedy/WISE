<?php
require 'DB.php';
// insert.php
if (isset($_POST['firstName'], $_POST['lastName'], $_POST['phoneNumber'])) {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);

    // تحقق إذا كان الحساب موجودًا مسبقًا
    $checkQuery = "SELECT * FROM `users` WHERE phoneNumber = '$phoneNumber'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "الحساب موجود بالفعل.";
    } else {
       
        // إدخال البيانات
        $insertQuery = "INSERT INTO `users` (firstName, lastName, phoneNumber) 
                        VALUES ('$firstName', '$lastName', '$phoneNumber')";

        if (mysqli_query($conn, $insertQuery)) {
            echo "تمت إضافة المستخدم بنجاح!";
        } else {
            echo "خطأ: " . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
?>