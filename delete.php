<?php
require 'DB.php';

if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $deleteQuery = "DELETE FROM `users` WHERE id = '$id'";

    if (mysqli_query($conn, $deleteQuery)) {
        echo "تم حذف المستخدم بنجاح!";
    } else {
        echo "خطأ: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>