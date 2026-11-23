<?php
include "db.php";

$id=$_GET['id'];

mysqli_query($conn,"
DELETE FROM custom_cakes
WHERE id=$id
");

header("Location:custom_orders.php");
?>