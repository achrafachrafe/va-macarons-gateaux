<?php
require_once "admin_check.php";
require_once "db.php";

// CSRF Token
if (empty($_SESSION['password_csrf'])) {
    $_SESSION['password_csrf'] = bin2hex(random_bytes(32));
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['password_csrf'], $token)) {
        $error = "Requête invalide. Veuillez réessayer.";
    } else {

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (
            $current_password === '' ||
            $new_password === '' ||
            $confirm_password === ''
        ) {
            $error = "Veuillez remplir tous les champs.";

        } elseif (strlen($new_password) < 10) {
            $error = "Le nouveau mot de passe doit contenir au moins 10 caractères.";

        } elseif ($new_password !== $confirm_password) {
            $error = "Les nouveaux mots de passe ne correspondent pas.";

        } elseif ($new_password === $current_password) {
            $error = "Choisissez un mot de passe différent de l'ancien.";

        } else {

            // Récupérer le mot de passe actuel
            $stmt = mysqli_prepare(
                $conn,
                "SELECT password FROM admins WHERE id = ? LIMIT 1"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $_SESSION['admin_id']
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $admin = mysqli_fetch_assoc($result);

            mysqli_stmt_close($stmt);

            if (!$admin || !password_verify(
                $current_password,
                $admin['password']
            )) {

                $error = "Le mot de passe actuel est incorrect.";

            } else {

                // Hash du nouveau mot de passe
                $hashed_password = password_hash(
                    $new_password,
                    PASSWORD_DEFAULT
                );

                // Mise à jour
            // Mise à jour du mot de passe
$update = mysqli_prepare(
    $conn,
    "UPDATE admins
     SET password = ?,
         session_version = session_version + 1
     WHERE id = ?"
);

if (!$update) {
    $error = "Erreur lors de la préparation de la requête.";

} else {

    mysqli_stmt_bind_param(
        $update,
        "si",
        $hashed_password,
        $_SESSION['admin_id']
    );

    if (mysqli_stmt_execute($update) &&
        mysqli_stmt_affected_rows($update) === 1) {

        // Synchroniser la session actuelle
        $current_version = (int)($_SESSION['session_version'] ?? 0);

        $_SESSION['session_version'] = $current_version + 1;

        $success = "Mot de passe modifié avec succès. Les autres sessions ont été déconnectées.";

        // Nouveau token CSRF
        $_SESSION['password_csrf'] = bin2hex(random_bytes(32));

    } else {
        $error = "Impossible de modifier le mot de passe.";
    }

    mysqli_stmt_close($update);
}
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Changer mot de passe | V&A</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #fff0f6, #ffe0ec);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .password-card {
            width: 100%;
            max-width: 470px;
            background: white;
            padding: 40px;
            border-radius: 23px;
            border: 1px solid #ffe0ec;
            box-shadow: 0 15px 45px rgba(232, 67, 147, .12);
        }

        .icon {
            width: 70px;
            height: 70px;
            background: #fff0f6;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }

        h2 {
            text-align: center;
            color: #d63384;
            font-size: 24px;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #999;
            font-size: 13px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #555;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #f3d5e2;
            border-radius: 11px;
            background: #fffafd;
            outline: none;
            font-family: inherit;
            font-size: 14px;
            transition: .3s;
        }

        input:focus {
            border-color: #ff69b4;
            box-shadow: 0 0 0 4px rgba(255,105,180,.12);
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff69b4, #e84393);
            border: none;
            border-radius: 11px;
            color: white;
            font-family: inherit;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: .3s;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(232,67,147,.25);
        }

        .alert {
            padding: 13px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert-error {
            background: #fff0f0;
            color: #dc3545;
        }

        .alert-success {
            background: #eafaf1;
            color: #198754;
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
            .password-card {
                padding: 28px 20px;
            }
        }
    </style>
</head>

<body>

<div class="password-card">

    <div class="icon">🔐</div>

    <h2>Changer le mot de passe</h2>

    <p class="subtitle">
        Sécurisez votre compte administrateur
    </p>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden"
               name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['password_csrf'], ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-group">
            <label>Mot de passe actuel</label>
            <input type="password"
                   name="current_password"
                   autocomplete="current-password"
                   required>
        </div>

        <div class="form-group">
            <label>Nouveau mot de passe</label>
            <input type="password"
                   name="new_password"
                   minlength="10"
                   autocomplete="new-password"
                   required>
        </div>

        <div class="form-group">
            <label>Confirmer le nouveau mot de passe</label>
            <input type="password"
                   name="confirm_password"
                   minlength="10"
                   autocomplete="new-password"
                   required>
        </div>

        <button type="submit" class="submit-btn">
            🔒 Modifier le mot de passe
        </button>

    </form>

    <a href="admin.php" class="back-btn">
        ← Retour au Dashboard
    </a>

</div>
<script src="connection.js"></script>
</body>
</html>
