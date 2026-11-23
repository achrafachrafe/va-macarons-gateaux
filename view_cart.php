<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V&A Macarons & Gateaux - Rabat</title>
    <style>
a{
    text-decoration:none;
}

body{
    font-family:'Poppins',sans-serif;
    background:#fff0f6;
    padding:20px;
}

/* CARD */

.cart-item{
    display:flex;
    align-items:center;
    gap:15px;
    background:#fff;
    padding:15px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 5px 15px rgba(0,0,0,.1);
}

.cart-left{
    flex-shrink:0;
}

.cart-img{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:10px;
}

.cart-item h2{
    flex:1;
    font-size:18px;
    margin:0;
    color:#333;
}

.cart-actions{
    display:flex;
    align-items:center;
    gap:8px;
}

.cart-actions a{
    width:28px;
    height:28px;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#ff4fa3;
    color:#fff;
    border-radius:8px;
    font-weight:bold;
}

.cart-item p{
    font-weight:bold;
    color:#444;
    white-space:nowrap;
    margin:0;
}

.delete-btn{
    color:red;
    font-weight:bold;
    white-space:nowrap;
}

.total{
    text-align:center;
    color:#ff4fa3;
    margin-top:30px;
}

.checkout-btn{
    display:block;
    margin:20px auto;
    border:none;
    background:#ff4fa3;
    color:white;
    padding:15px 35px;
    border-radius:30px;
    cursor:pointer;
    font-size:18px;
}

.checkout-btn:hover{
    background:#ff2d8d;
}

/* TELEPHONE */

@media (max-width:768px){

body{
    padding:12px;
}

.cart-item{
    flex-wrap:wrap;
    justify-content:center;
    text-align:center;
}

.cart-left{
    width:100%;
}

.cart-img{
    width:90px;
    height:90px;
}

.cart-item h2{
    width:100%;
    font-size:16px;
}

.cart-actions{
    justify-content:center;
    width:100%;
}

.cart-item p{
    width:100%;
    margin-top:10px;
}

.delete-btn{
    width:100%;
    margin-top:10px;
}

.checkout-btn{
    width:100%;
}

}
</style>
</head>
<body>
<?php
session_start();
include "db.php";

if(!isset($_SESSION['cart'])){
$_SESSION['cart'] = [];
}
$total=0;

foreach($_SESSION['cart'] as $id=>$qty){

$r=mysqli_query($conn,
"SELECT * FROM products WHERE id=$id");

$p=mysqli_fetch_assoc($r);

$subtotal=$p['price']*$qty;

$total+=$subtotal;

?>

<div class="cart-item">
<div class="cart-left">
    <img src="<?php echo $p['image']; ?>" class="cart-img">
</div>
<h2><?php echo $p['name']; ?></h2>

<div class="cart-actions">
<a href="update_cart.php?id=<?=$id?>&action=minus">-</a>

<?php echo $qty; ?>

<a href="update_cart.php?id=<?=$id?>&action=plus">+</a>
</div>

<p><?php echo $subtotal; ?> DH</p>

<a class="delete-btn" href="update_cart.php?id=<?=$id?>&action=delete">
❌ Supprimer
</a>

</div>

<?php } ?>

<h1 class="total">
Total: <?php echo $total; ?> DH
</h1>

<a href="checkout.php">
<button class="checkout-btn">Commander</button>
</a>

<script src="connection.js"></script>
</body>
</html>