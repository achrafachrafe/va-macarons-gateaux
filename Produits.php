<?php
require_once "admin_check.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Y&A Macarons & Gateaux - Rabat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #fff5f9;
    color: #444;
    padding: 40px;
}

/* Header */
h1 {
    text-align: center;
    color: #e84393;
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 30px;
    letter-spacing: -1px;
}

/* Add Product Button */
body > a {
    display: block;
    width: fit-content;
    margin: 0 auto 30px;
    text-decoration: none;
}

button {
    border: none;
    cursor: pointer;
    font-family: inherit;
    transition: 0.3s ease;
}

body > a button {
    background: linear-gradient(135deg, #ff69b4, #e84393);
    color: white;
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    box-shadow: 0 6px 18px rgba(232,67,147,0.2);
}

body > a button:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(232,67,147,0.3);
}

/* Separator */
hr {
    border: none;
    height: 1px;
    background: #ffd6e7;
    margin-bottom: 35px;
}

/* Products Grid */
body {
    display: flex;
    flex-direction: column;
}

.card {
    display: inline-flex;
    flex-direction: column;
    width: 300px;
    vertical-align: top;
    background: white;
    border-radius: 20px;
    padding: 18px;
    margin: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    border: 1px solid #ffe2ed;
    transition: 0.3s ease;
}

.card:hover {
    transform: translateY(-7px);
    box-shadow: 0 15px 35px rgba(232,67,147,0.12);
}

.card img {
    width: 100%;
    height: 210px;
    object-fit: cover;
    border-radius: 14px;
    background: #fff0f6;
}

.card h2 {
    font-size: 19px;
    color: #d63384;
    margin: 18px 0 8px;
}

.card p {
    font-size: 17px;
    color: #777;
    font-weight: 600;
    margin-bottom: 18px;
}

/* Action Buttons */
.card a {
    text-decoration: none;
}

.card button {
    width: 100%;
    padding: 11px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    margin-top: 9px;
}

.card a:first-of-type button {
    background: #fff0f6;
    color: #d63384;
}

.card a:first-of-type button:hover {
    background: #e84393;
    color: white;
}

.card a:last-of-type button {
    background: #fff0f0;
    color: #dc3545;
}

.card a:last-of-type button:hover {
    background: #dc3545;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    body {
        padding: 20px 12px;
    }

    h1 {
        font-size: 28px;
    }

    .card {
        width: 100%;
        max-width: 380px;
        margin: 12px auto;
    }
}
.products-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
}
.back-btn {
            background: linear-gradient(135deg, #ff69b4, #e84393);
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            transition: .3s;
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 18px rgba(232,67,147,.2);
        }

</style>
</head>
<body>
<?php
include "db.php";
?>

<h1>Admin Panel</h1>

<a href="add_product.php">
<button>Ajouter Produit</button>
</a>
<a href="admin.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Dashboard
        </a>
<hr>
<div class="products-container">
<?php
$result = mysqli_query($conn,"SELECT * FROM products");

while($row=mysqli_fetch_assoc($result)){
?>

<div class="card">

<img src="<?php echo $row['image']; ?>" width="150">

<h2><?php echo $row['name']; ?></h2>

<p><?php echo $row['price']; ?> DH</p>

<a href="edit.php?id=<?php echo $row['id']; ?>">
<button>Modifier</button>
</a>

<a href="delete.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Supprimer ?')">
<button>Supprimer</button>
</a>

</div>

<?php } ?>
</div>
<script src="connection.js"></script>
</body>
</html>