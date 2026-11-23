
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion perdue - V&A</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fff5f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .offline-card {
            background: white;
            padding: 45px 30px;
            border-radius: 22px;
            text-align: center;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 10px 35px rgba(232, 67, 147, .12);
            border: 1px solid #ffe0ec;
        }

        .icon {
            font-size: 65px;
            margin-bottom: 20px;
        }

        h1 {
            color: #e84393;
            margin-bottom: 15px;
            font-size: 26px;
        }

        p {
            color: #777;
            line-height: 1.7;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .loader {
            width: 35px;
            height: 35px;
            border: 4px solid #ffe0ec;
            border-top-color: #e84393;
            border-radius: 50%;
            margin: 0 auto 15px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .status {
            color: #e84393;
            font-size: 13px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="offline-card">

        <div class="icon">📡</div>

        <h1>Connexion perdue !</h1>

        <p>
            Votre connexion Internet semble interrompue.
            Vérifiez votre connexion.
            Nous allons vous rediriger automatiquement
            dès que le réseau sera disponible.
        </p>

        <div class="loader"></div>

        <div class="status">
            En attente de connexion...
        </div>

    </div>

    <script src="connection.js"></script>

</body>
</html>
