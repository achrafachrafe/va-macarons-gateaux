<?php
require_once "admin_check.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Admin</title>
    <style>
   
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Poppins", Arial, sans-serif;
    background: url("background.jpg") center/cover fixed no-repeat;
    color: #2d2430;
    min-height: 100vh;
}

/* Container */
.container {
    width: 90%;
    max-width: 1200px;
    margin: 50px auto;
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 40px;
}

.header h1 {
    font-size: 38px;
    color: #ff3f9f;
    margin-bottom: 10px;
}

.header h1 i {
    margin-right: 10px;
}

.header p {
    color: #777;
    font-size: 16px;
}

/* Cards container */
.cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

/* Card */
.card {
    text-decoration: none;
    background: #ffffff;
    border-radius: 22px;
    padding: 30px 25px;
    text-align: center;
    color: #2d2430;

    border: 1px solid #f8d8e7;

    box-shadow: 0 10px 30px rgba(255, 72, 159, 0.12);

    transition: all 0.3s ease;
}

/* Icon */
.card > i {
    font-size: 42px;
    color: #ff3f9f;
    margin-bottom: 18px;
    transition: 0.3s;
}

/* Title */
.card h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #29212a;
}

/* Description */
.card p {
    font-size: 14px;
    color: #777;
    line-height: 1.6;
}

/* Hover */
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(255, 72, 159, 0.22);
    border-color: #ff5aaa;
}

.card:hover > i {
    transform: scale(1.15);
    color: #ff1493;
}

/* Logout */
.card.logout {
    background: #fff5f8;
    border-color: #ffc4d9;
}

.card.logout > i {
    color: #e91e63;
}

.card.logout:hover {
    background: #ffe4ef;
}

/* Responsive */
@media (max-width: 900px) {
    .cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .container {
        width: 92%;
        margin: 30px auto;
    }

    .header h1 {
        font-size: 28px;
    }

    .cards {
        grid-template-columns: 1fr;
    }

    .card {
        padding: 25px 20px;
    }
}
</style>
</head>
<body>

<div class="container">

<div class="header">
    <h1><i class="fas fa-user-shield"></i> Admin Dashboard</h1>
    <p>Manage your website from one place</p>
</div>

<div class="cards">

<a href="change_password.php" class="card">
<i class="fas fa-key"></i>
<h3>Change Password</h3>
<p>Update administrator password.</p>
</a>

<a href="custom_orders.php" class="card">
<i class="fas fa-cake-candles"></i>
<h3>Custom Orders</h3>
<p>Manage personalized orders.</p>
</a>

<a href="orders.php" class="card">
<i class="fas fa-cart-shopping"></i>
<h3>Orders</h3>
<p>View all customer orders.</p>
</a>

<a href="Produits.php" class="card">
<i class="fas fa-box-open"></i>
<h3>Products</h3>
<p>Add, edit and delete products.</p>
</a>

<a href="stats.php" class="card">
<i class="fas fa-chart-bar"></i>
<h3>Statistics</h3>
<p>Website analytics.</p>
</a>

<a href="logout.php" class="card logout" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">
<i class="fas fa-right-from-bracket"></i>
<h3>Logout</h3>
<p>Sign out safely.</p>
</a>

</div>

</div>
<script src="connection.js"></script>
</body>
</html>