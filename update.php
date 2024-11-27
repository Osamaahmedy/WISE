<?php
require 'DB.php';

if (isset($_POST['id'], $_POST['firstName'], $_POST['lastName'], $_POST['phoneNumber'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);

    // تحقق من رقم الهاتف
    $checkPhoneQuery = "SELECT * FROM `users` WHERE phoneNumber = '$phoneNumber' AND id != '$id'";
    $checkPhoneResult = mysqli_query($conn, $checkPhoneQuery);

    if (mysqli_num_rows($checkPhoneResult) > 0) {
        echo "رقم الهاتف مكرر. يرجى اختيار رقم هاتف آخر.";
    } else {
        // تحديث البيانات
        $updateQuery = "UPDATE `users` 
                        SET firstName = '$firstName', lastName = '$lastName', phoneNumber = '$phoneNumber' 
                        WHERE id = '$id'";

        if (mysqli_query($conn, $updateQuery)) {
            echo "تم تحديث البيانات بنجاح!";
        } else {
            echo "خطأ: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>