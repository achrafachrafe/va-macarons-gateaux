<?php
require_once "admin_check.php";
include "db.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    die("ID invalide");
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM products WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Produit introuvable");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V&A Macarons & Gateaux - Rabat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #fff5f9;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 25px;
}
.card {
            width: 100%;
            max-width: 470px;
            background: white;
            padding: 40px;
            border-radius: 23px;
            border: 1px solid #ffe0ec;
            box-shadow: 0 15px 45px rgba(232, 67, 147, .12);
        }


/* Inputs */
form input[type="text"],
form input[type="number"] {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #f3d5e2;
    border-radius: 12px;
    outline: none;
    background: #fffafd;
    font-family: inherit;
    font-size: 14px;
    transition: 0.3s;
}

form input:focus {
    border-color: #ff69b4;
    box-shadow: 0 0 0 4px rgba(255, 105, 180, 0.12);
}

/* Product Image */
form img {
    display: block;
    width: 180px;
    height: 160px;
    object-fit: cover;
    margin: 10px auto 20px;
    border-radius: 15px;
    border: 3px solid #ffe0ec;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

/* File Input */
form input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    background: #fff0f6;
    border-radius: 10px;
    border: 1px dashed #ff69b4;
    font-family: inherit;
    font-size: 13px;
}

form input[type="file"]::file-selector-button {
    background: #ff69b4;
    color: white;
    border: none;
    padding: 9px 13px;
    border-radius: 8px;
    margin-right: 10px;
    cursor: pointer;
    font-family: inherit;
}

/* Update Button */
form button {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #ff69b4, #e84393);
    color: white;
    border: none;
    border-radius: 12px;
    font-family: inherit;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0 6px 18px rgba(232, 67, 147, 0.2);
}

form button:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(232, 67, 147, 0.3);
}

 .back-btn {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #d63384;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .back-btn:hover {
            color: #a61e65;
        }

        @media(max-width: 500px) {
            .card {
                padding: 28px 20px;
            }
        }
</style>
</head>
<body>
    <div class="card">
    <form action="update.php"
method="POST"
enctype="multipart/form-data">

<input type="hidden"
name="id"
value="<?php echo $row['id']; ?>">

<input type="text"
name="name"
value="<?php echo $row['name']; ?>">

<input type="number"
name="price"
value="<?php echo $row['price']; ?>">

<br><br>

<img src="<?php echo $row['image']; ?>" width="150">

<br><br>

<input type="file" name="image">

<button type="submit">
Update
</button>

</form>
  <a href="Produits.php" class="back-btn">
        ← Retour au Produit
    </a>
</div>
<script src="connection.js"></script>
</body>
</html>
