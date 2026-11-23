<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect("localhost","root","","cake_shope");

if(!$conn){
    header("Location: offline.php");
    exit();
}

mysqli_set_charset($conn,"utf8mb4");
?>