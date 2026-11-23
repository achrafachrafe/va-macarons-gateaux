<?php
require_once "admin_check.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>V&A Macarons & Gateaux - Rabat</title>
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
    
* {
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', Arial, sans-serif;
    background: #fff5f8;
    color: #3d3036;
    margin: 0;
    padding: 35px 25px;
}

/* Title */
h1 {
    text-align: center;
    color: #e84393;
    font-size: 32px;
    font-weight: 700;
    margin: 15px 0 45px;
    position: relative;
}

h1::after {
    content: "";
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #ff69b4, #e84393);
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: -13px;
    border-radius: 20px;
}

/* Dashboard Button */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: linear-gradient(135deg, #ff69b4, #e84393);
    color: white;
    padding: 12px 20px;
    text-decoration: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    transition: .3s;
    box-shadow: 0 5px 15px rgba(232, 67, 147, .15);
}

.back-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(232, 67, 147, .25);
}

/* Order Card */
.order {
    background: #ffffff;
    padding: 25px;
    margin: 25px auto;
    max-width: 750px;
    border-radius: 18px;
    border: 1px solid #f8dce8;
    box-shadow: 0 8px 25px rgba(180, 90, 130, .08);
    transition: .3s;
}

.order:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(180, 90, 130, .15);
}

/* Customer Name */
.order h2 {
    color: #e84393;
    font-size: 22px;
    margin: 0 0 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f8dce8;
}

/* Information */
.order p {
    margin: 12px 0;
    color: #55464e;
    font-size: 14px;
    line-height: 1.7;
    overflow-wrap: anywhere;
}

/* Image */
.order img {
    display: block;
    max-width: 100%;
    width: 220px;
    height: 200px;
    object-fit: cover;
    margin-top: 18px;
    border-radius: 13px;
    border: 2px solid #f8dce8;
    transition: .3s;
}

.order img:hover {
    transform: scale(1.03);
}

/* Delete Button */
.order button {
    background: #fff0f3;
    color: #e84393;
    border: 1px solid #ffc4d9;
    padding: 10px 18px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: .3s;
}

.order button:hover {
    background: #e84393;
    color: white;
    border-color: #e84393;
}

/* Responsive */
@media (max-width: 600px) {
    body {
        padding: 20px 12px;
    }

    h1 {
        font-size: 25px;
    }

    .order {
        padding: 18px;
    }

    .order h2 {
        font-size: 19px;
    }

    .order img {
        width: 100%;
        height: 220px;
    }
}

</style>
</head>
<body>
<?php
include "db.php";
?>
<h1>Demandes personnelles</h1>
<a href="admin.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Dashboard
        </a>
<?php
$r=mysqli_query($conn,
"SELECT * FROM custom_cakes ORDER BY id DESC");

while($row=mysqli_fetch_assoc($r)){
?>
<div class="order">

<h2>
<?php echo $row['name']; ?>
</h2>

<p>Phone:
<?php echo $row['phone']; ?>
</p>

<p>
Adresse:
<?php echo $row['address']; ?>
</p>

<p>Size:
<?php echo $row['size']; ?>
</p>

<p>Flavor:
<?php echo $row['flavor']; ?>
</p>

<p>Message:
<?php echo $row['message']; ?>
</p>

<p>
Price:
<?php echo $row['price']; ?> DH
</p>

<?php if($row['image']!=""){ ?>
<img
src="uploads/<?php echo $row['image']; ?>"
width="200">
<?php } ?>

<br><br>

<a
href="delete_custom.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Supprimer cette commande ?')"
>
<button>
Supprimer
</button>
</a>
</div>
<?php } ?>
<script src="connection.js"></script>
</body>
</html>