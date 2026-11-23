<?php
session_start();
require_once "db.php";

// Déjà connecté
if (isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit;
}

// CSRF Token
if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérification CSRF
    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['login_csrf'], $_POST['csrf_token'])
    ) {
        $error = "Requête invalide. Veuillez réessayer.";
    } else {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $error = "Email ou mot de passe incorrect.";
        } else {

            // Recherche Admin
            $stmt = mysqli_prepare(
    $conn,
    "SELECT id, email, password, session_version
     FROM admins
     WHERE email = ?
     LIMIT 1"
);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);
                $admin = mysqli_fetch_assoc($result);

                if ($admin && password_verify($password, $admin['password'])) {

                    // Nouvelle session sécurisée
                    session_regenerate_id(true);

                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_email'] = $admin['email'];
                    $_SESSION['session_version'] = (int)$admin['session_version'];
                    // Renouveler CSRF
                    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));

                    header("Location: admin.php");
                    exit;

                } else {
                    $error = "Email ou mot de passe incorrect.";
                }

                mysqli_stmt_close($stmt);

            } else {
                $error = "Une erreur est survenue. Réessayez plus tard.";
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

    <title>Admin Login | V&A Macarons & Gateaux</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #fff0f6, #ffe0ec, #fff5f9);
        }

        .login-container {
            width: 100%;
            max-width: 430px;
            background: white;
            padding: 45px 38px;
            border-radius: 25px;
            box-shadow: 0 15px 50px rgba(232, 67, 147, .13);
            border: 1px solid #ffe0ec;
        }

        .logo {
            width: 75px;
            height: 75px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff0f6;
            border-radius: 50%;
            font-size: 35px;
        }

        h2 {
            text-align: center;
            color: #d63384;
            font-size: 25px;
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
            padding: 14px 16px;
            border: 1px solid #f3d5e2;
            border-radius: 11px;
            outline: none;
            background: #fffafd;
            font-family: inherit;
            font-size: 14px;
            transition: .3s;
        }

        input:focus {
            border-color: #ff69b4;
            box-shadow: 0 0 0 4px rgba(255, 105, 180, .12);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            border: none;
            border-radius: 11px;
            background: linear-gradient(135deg, #ff69b4, #e84393);
            color: white;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(232, 67, 147, .25);
        }

        .error {
            padding: 13px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #fff0f0;
            color: #dc3545;
            font-size: 13px;
            text-align: center;
        }

        .footer {
            text-align: center;
            color: #aaa;
            font-size: 11px;
            margin-top: 25px;
        }

        @media(max-width: 480px) {
            .login-container {
                padding: 35px 23px;
            }
        }
    </style>

</head>

<body>

<div class="login-container">

    <div class="logo">🍰</div>

    <h2>Welcome Back!</h2>

    <p class="subtitle">
        Admin Panel - Y&A Macarons & Gateaux
    </p>

    <?php if ($error): ?>
        <div class="error">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['login_csrf'], ENT_QUOTES, 'UTF-8') ?>"
        >

        <div class="form-group">
            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="admin@example.com"
                required
                autocomplete="username"
            >
        </div>

        <div class="form-group">
            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit" class="login-btn">
            Se connecter →
        </button>

    </form>

    <div class="footer">
        © <?= date('Y') ?> Y&A Macarons & Gateaux
        <br>
        Tous droits réservés.
    </div>

</div>
<script src="connection.js"></script>
</body>
</html>
