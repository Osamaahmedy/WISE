<?php
require 'DB.php';

$phoneNumber = isset($_GET['phoneNumber']) ? mysqli_real_escape_string($conn, $_GET['phoneNumber']) : '';

$query = "SELECT * FROM `users`";
if (!empty($phoneNumber)) {
    $query .= " WHERE phoneNumber LIKE '%$phoneNumber%'";
}

$result = mysqli_query($conn, $query);
$output = '';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['firstName'] . '</td>
                <td>' . $row['lastName'] . '</td>
                <td>' . $row['phoneNumber'] . '</td>
                <td>
                    <button class="editUser" 
                        data-id="' . $row['id'] . '" 
                        data-firstname="' . $row['firstName'] . '" 
                        data-lastname="' . $row['lastName'] . '" 
                        data-phonenumber="' . $row['phoneNumber'] . '">تعديل</button>
                    <button class="deleteUser" data-id="' . $row['id'] . '">حذف</button>
                </td>
            </tr>
        ';
    }
} else {
    $output .= '<tr><td colspan="5">لا توجد بيانات.</td></tr>';
}

echo $output;
mysqli_close($conn);
?>