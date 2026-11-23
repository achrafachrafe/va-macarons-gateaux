<?php
require_once "admin_check.php";
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';

    // Vérification des champs
    if ($name === "" || !is_numeric($price) || $price <= 0) {
        $error = "Veuillez saisir un nom et un prix valide.";
    } elseif (
        !isset($_FILES['image']) ||
        $_FILES['image']['error'] !== UPLOAD_ERR_OK
    ) {
        $error = "Veuillez sélectionner une image valide.";
    } else {

        // Vérification du type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        $imageType = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($imageType, $allowedTypes, true)) {
            $error = "Format accepté : JPG, PNG ou WEBP.";
        } else {

            // Création du dossier uploads
            $uploadDir = "uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Nom unique pour l'image
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('product_', true) . "." . strtolower($extension);

            $folder = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $folder)) {

                // Insertion avec Prepared Statement
                $stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO products (name, price, image) VALUES (?, ?, ?)"
                );

                mysqli_stmt_bind_param($stmt, "sds", $name, $price, $folder);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    header("Location: admin.php");
                    exit;
                } else {
                    $error = "Erreur lors de l'ajout du produit.";
                    unlink($folder);
                }

                mysqli_stmt_close($stmt);

            } else {
                $error = "Impossible de télécharger l'image.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - V&A Macarons & Gateaux</title>

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

        form {
            width: 100%;
        }

        .form-title {
            text-align: center;
            color: #e84393;
            font-size: 25px;
            margin-bottom: 10px;
        }

        .form-subtitle {
            text-align: center;
            color: #999;
            font-size: 13px;
            margin-bottom: 30px;
        }

        form input {
            width: 100%;
            padding: 15px 17px;
            border: 1px solid #f3d5e2;
            border-radius: 12px;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            color: #444;
            background: #fffafd;
            transition: .3s ease;
        }

        form input:focus {
            border-color: #ff69b4;
            box-shadow: 0 0 0 4px rgba(255, 105, 180, .12);
            background: white;
        }

        form input[type="file"] {
            padding: 12px;
            background: #fff0f6;
            cursor: pointer;
        }

        form input[type="file"]::file-selector-button {
            background: #ff69b4;
            color: white;
            border: none;
            padding: 9px 15px;
            border-radius: 8px;
            margin-right: 12px;
            cursor: pointer;
            font-family: inherit;
        }

        form button {
            width: 100%;
            padding: 15px;
            margin-top: 5px;
            background: linear-gradient(135deg, #ff69b4, #e84393);
            color: white;
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s ease;
            box-shadow: 0 6px 18px rgba(232, 67, 147, .2);
        }

        form button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(232, 67, 147, .3);
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

        .error {
            background: #ffe5e5;
            color: #c62828;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
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

    <form action="add_product.php" method="POST" enctype="multipart/form-data">

        <h2 class="form-title">🍰 Ajouter un produit</h2>

        <p class="form-subtitle">
            Ajoutez un nouveau délice à votre boutique
        </p>

        <?php if ($error !== "") { ?>
            <div class="error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php } ?>

        <input
            type="text"
            name="name"
            placeholder="Nom produit"
            required
        >

        <br><br>

        <input
            type="number"
            name="price"
            placeholder="Prix"
            min="0.01"
            step="0.01"
            required
        >

        <br><br>

        <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/webp"
            required
        >

        <br><br>

        <button type="submit">Ajouter produit</button>

    </form>

    <a href="Produits.php" class="back-btn">
        ← Retour aux produits
    </a>

</div>
<script src="connection.js"></script>
</body>
</html>
