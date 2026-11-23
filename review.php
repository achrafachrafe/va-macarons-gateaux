<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V&A Macarons & Gateaux - Rabat</title>
    <style>
body{
    margin:0;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#ffe4ec,#fff0f6);
    font-family:Poppins,sans-serif;
    padding:20px;
}

.success,
.error{
    max-width:450px;
    width:100%;
    background:#fff;
    padding:35px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
}

.success h2{
    color:#28a745;
}

.error h2{
    color:#dc3545;
}

.success p,
.error p{
    color:#555;
    line-height:1.7;
    margin:15px 0;
}

.home-btn{
    display:inline-block;
    margin-top:20px;
    padding:12px 25px;
    background:#ff4fa3;
    color:#fff;
    text-decoration:none;
    border-radius:30px;
    transition:.3s;
}

.home-btn:hover{
    background:#ff2d8d;
}
</style>
</head>
<body>
<?php
include 'db.php';

if(isset($_POST['send'])){

$name=trim($_POST['name']);

$product=$_POST['product'];

$rating=$_POST['rating'];

$comment=$_POST['comment'];


$check=mysqli_query($conn,"

SELECT *

FROM orders o

JOIN order_items oi

ON o.id=oi.order_id

WHERE
LOWER(o.customer_name)=LOWER('$name')

AND oi.product_id='$product'

");


if(mysqli_num_rows($check)>0){
$already=mysqli_query($conn,"

SELECT *

FROM reviews

WHERE
customer_name='$name'

AND product_id='$product'

");


if(mysqli_num_rows($already)>0){

mysqli_query($conn,"

UPDATE reviews

SET
rating='$rating',
comment='$comment'

WHERE
customer_name='$name'
AND product_id='$product'

");

echo "
<div class='success'>
<h2>⭐ Avis mis à jour !</h2>

<p>
Merci <strong>$name</strong>,
<br>
Votre avis a été mis à jour avec succès.
</p>

<a href='index.php' class='home-btn'>
Retour à l'accueil
</a>

</div>
";

exit;

}
mysqli_query($conn,"


INSERT INTO reviews
(customer_name,product_id,rating,comment)

VALUES(
'$name',
'$product',
'$rating',
'$comment'
)

");

echo "
<div class='success'>
<h2>🎉 Merci pour votre avis !</h2>

<p>
Votre évaluation a été enregistrée avec succès.
</p>

<p>
⭐ Note : <strong>$rating / 5</strong>
</p>

<a href='index.php' class='home-btn'>
Retour à l'accueil
</a>

</div>
";

exit;
}else{
echo "
<div class='error'>
<h2>❌ Impossible d'ajouter un avis</h2>

<p>
Vous devez d'abord acheter ce produit avant de pouvoir laisser un avis.
</p>

<a href='index.php' class='home-btn'>
Retour à l'accueil
</a>

</div>
";

}

}
?>
<script src="connection.js"></script>
</body>
</html>