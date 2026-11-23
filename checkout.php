<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V&A Macarons & Gateaux - Rabat</title>
    <style>
 *{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#ffe4ec,#fff0f6);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}

fieldset{
    border:none;
    background:#fff;
    width:100%;
    max-width:420px;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}

h2{
    text-align:center;
    color:#ff4fa3;
    margin-bottom:20px;
}

input,
textarea{
    width:100%;
    padding:12px 15px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
    font-size:15px;
    transition:.3s;
}

input:focus,
textarea:focus{
    border-color:#ff4fa3;
    box-shadow:0 0 8px rgba(255,79,163,.3);
}

textarea{
    height:90px;
    resize:none;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    background:#ff4fa3;
    color:#fff;
    font-size:17px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#ff2d8d;
}

.success{
    background:#fff;
    padding:30px;
    border-radius:20px;
    text-align:center;
    color:#28a745;
    font-size:22px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}

@media(max-width:768px){

    body{
        padding:15px;
        align-items:flex-start;
    }

    fieldset{
        max-width:100%;
        padding:20px;
    }

    h2{
        font-size:22px;
    }

    input,
    textarea,
    button{
        font-size:16px;
    }

}
/*////////*/
.success{
    background:#fff;
    max-width:420px;
    margin:80px auto;
    padding:35px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
}

.success h2{
    color:#28a745;
    margin-bottom:15px;
}

.success p{
    color:#555;
    font-size:17px;
    line-height:1.7;
    margin-bottom:15px;
}

.home-btn{
    display:inline-block;
    margin-top:15px;
    background:#ff4fa3;
    color:#fff;
    padding:12px 25px;
    border-radius:30px;
    text-decoration:none;
    transition:.3s;
}

.home-btn:hover{
    background:#ff2d8d;
}

</style>
</head>
<body>
<?php
session_start();
include 'db.php';

$total=0;

if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
header("Location:view_cart.php");
exit();
}


foreach($_SESSION['cart'] as $id=>$qty){

$r=mysqli_query(
$conn,
"SELECT * FROM products WHERE id=$id"
);

$p=mysqli_fetch_assoc($r);

if(!$p){
continue;
}

$total +=
$p['price']*$qty;

}



if(isset($_POST['order'])){

$name=$_POST['name'];
$phone=$_POST['phone'];
$address=$_POST['address'];


/* ORDER */

mysqli_query($conn,"
INSERT INTO orders
(customer_name,phone,address,total,status)

VALUES(
'$name',
'$phone',
'$address',
'$total',
'En attente'
)
");


$order_id=
mysqli_insert_id($conn);



foreach($_SESSION['cart'] as $id=>$qty){


/* ORDER ITEMS */

mysqli_query($conn,"
INSERT INTO order_items
(order_id,product_id,qty)

VALUES(
'$order_id',
'$id',
'$qty'
)
");


/* BEST SELLERS */

mysqli_query($conn,"
UPDATE products
SET sold_count=sold_count+$qty
WHERE id=$id
");


}


unset($_SESSION['cart']);

echo "
<div class='success'>

<h2>🎉 Commande confirmée !</h2>

<p>
Merci <strong>$name</strong>,
<br>
Votre commande a été envoyée avec succès.
</p>

<p>
🚚 Livraison prévue dans les 24 heures.
</p>

<a href='index.php' class='home-btn'>
Retour à l'accueil
</a>

</div>
";

exit;

}
?>



<fieldset>

<form method='POST'>

<h2>Checkout</h2>

<input
type="text"
name='name'
placeholder='Nom'
required>

<br><br>


<input
type="number"
name='phone'
placeholder='Téléphone'
required>

<br><br>


<textarea
name='address'
placeholder='Adresse'
required>
</textarea>

<br><br>


<h2>
Total:
<?php echo $total; ?>
DH
</h2>


<button name='order'>
Valider commande
</button>

</form>

</fieldset>
<script src="connection.js"></script>
</body>
</html>