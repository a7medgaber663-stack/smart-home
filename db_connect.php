<?php
$host = 'localhost';
$dbname = 'smarthome';
$user = 'root';
$pass = '';

$conn = mysqli_connect("localhost", "root", "", "smarthome");
if(!$conn){
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}
?>
