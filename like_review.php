<?php
include 'db.php';

$id = $_GET['id'];
$ip = $_SERVER['REMOTE_ADDR'];

$check = mysqli_query($conn, "
SELECT *
FROM review_likes
WHERE review_id='$id'
AND user_ip='$ip'
");


if(mysqli_num_rows($check) == 0){

mysqli_query($conn, "
INSERT INTO review_likes (review_id,user_ip)
VALUES ('$id','$ip')
");

mysqli_query($conn, "
UPDATE reviews
SET likes = likes + 1
WHERE id = $id
");

$status = "liked";

}else{

mysqli_query($conn, "
DELETE FROM review_likes
WHERE review_id='$id'
AND user_ip='$ip'
");

mysqli_query($conn, "
UPDATE reviews
SET likes = likes - 1
WHERE id = $id
AND likes > 0
");

$status = "unliked";

}

$r = mysqli_query($conn, "
SELECT likes
FROM reviews
WHERE id=$id
");

$row = mysqli_fetch_assoc($r);

echo json_encode([
"count" => $row['likes'],
"status" => $status
]);
?>