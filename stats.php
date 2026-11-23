
<?php
require_once "admin_check.php";
require_once "db.php";

// Fonction pour récupérer une valeur SQL
function getStat($conn, $sql) {
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_row($result);

    return $row[0] ?? 0;
}

// Statistiques générales
$totalProducts = getStat(
    $conn,
    "SELECT COUNT(*) FROM products"
);

$totalOrders = getStat(
    $conn,
    "SELECT COUNT(*) FROM orders"
);

$totalCustomCakes = getStat(
    $conn,
    "SELECT COUNT(*) FROM custom_cakes"
);

$totalReviews = getStat(
    $conn,
    "SELECT COUNT(*) FROM reviews"
);

$totalSold = getStat(
    $conn,
    "SELECT COALESCE(SUM(sold_count), 0) FROM products"
);

// Valeur totale des commandes
$totalAmount = getStat(
    $conn,
    "SELECT COALESCE(SUM(total), 0) FROM orders"
);

// Nombre de clients uniques selon leur téléphone
$totalCustomers = getStat(
    $conn,
    "SELECT COUNT(DISTINCT phone)
     FROM orders
     WHERE phone IS NOT NULL AND phone != ''"
);


// Commandes par statut
$statusQuery = mysqli_query(
    $conn,
    "SELECT status, COUNT(*) AS total
     FROM orders
     GROUP BY status
     ORDER BY total DESC"
);

$statuses = [];

if ($statusQuery) {
    while ($row = mysqli_fetch_assoc($statusQuery)) {
        $statuses[] = $row;
    }
}

// Dernières commandes
$latestOrders = mysqli_query(
    $conn,
    "SELECT id, customer_name, phone, total, status
     FROM orders
     ORDER BY id DESC
     LIMIT 8"
);

function e($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function money($value) {
    return number_format((float)$value, 2, '.', ' ') . ' DH';
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Statistiques | V&A Macarons & Gâteaux</title>

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
            padding: 30px;
        }

        .container {
            max-width: 1250px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #d63384;
            font-size: 28px;
        }

        .header p {
            color: #999;
            font-size: 13px;
            margin-top: 5px;
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

        /* Statistics Cards */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 18px;
            border: 1px solid #ffe0ec;
            box-shadow: 0 5px 20px rgba(232,67,147,.05);
            transition: .3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(232,67,147,.12);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 14px;
            background: #fff0f6;
            color: #e84393;
            font-size: 20px;
        }

        .stat-card h3 {
            font-size: 13px;
            color: #999;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-card h2 {
            color: #333;
            font-size: 25px;
            font-weight: 700;
        }

        .stat-card small {
            display: block;
            color: #aaa;
            font-size: 11px;
            margin-top: 8px;
        }

        /* Sections */

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 22px;
            margin-bottom: 25px;
        }

        .panel {
            background: white;
            padding: 25px;
            border-radius: 18px;
            border: 1px solid #ffe0ec;
            box-shadow: 0 5px 20px rgba(232,67,147,.05);
        }

        .panel h2 {
            color: #d63384;
            font-size: 18px;
            margin-bottom: 22px;
        }

        /* Status */

        .status-item {
            margin-bottom: 20px;
        }

        .status-info {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 9px;
        }

        .status-info strong {
            color: #555;
        }

        .progress {
            height: 10px;
            background: #ffeaf2;
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #ff69b4, #e84393);
            border-radius: 20px;
        }

        .empty {
            color: #aaa;
            text-align: center;
            padding: 25px 10px;
            font-size: 13px;
        }

        /* Summary */

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f8e6ee;
            font-size: 13px;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item span {
            color: #888;
        }

        .summary-item strong {
            color: #d63384;
        }

        /* Table */

        .table-panel {
            background: white;
            padding: 25px;
            border-radius: 18px;
            border: 1px solid #ffe0ec;
            box-shadow: 0 5px 20px rgba(232,67,147,.05);
            overflow-x: auto;
        }

        .table-panel h2 {
            color: #d63384;
            font-size: 18px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th {
            background: #fff0f6;
            color: #d63384;
            font-size: 12px;
            font-weight: 600;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f8e6ee;
            font-size: 12px;
            color: #666;
        }

        tr:hover td {
            background: #fffafd;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            background: #fff0f6;
            color: #d63384;
            font-size: 11px;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            color: #bbb;
            font-size: 11px;
            margin-top: 30px;
        }

        @media(max-width: 1000px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 550px) {
            body {
                padding: 15px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 22px;
            }

            .stat-card {
                padding: 20px;
            }

            .panel,
            .table-panel {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <div>
            <h1>
                <i class="fa-solid fa-chart-pie"></i>
                Statistiques
            </h1>

            <p>Vue générale de votre pâtisserie</p>
        </div>

        <a href="admin.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Dashboard
        </a>

    </div>

    <!-- Statistics Cards -->

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-box"></i>
                </div>
                <i class="fa-solid fa-chart-line" style="color:#ddd"></i>
            </div>

            <h3>Total Produits</h3>
            <h2><?= number_format((int)$totalProducts) ?></h2>
            <small>Produits disponibles dans la boutique</small>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <i class="fa-solid fa-chart-line" style="color:#ddd"></i>
            </div>

            <h3>Total Commandes</h3>
            <h2><?= number_format((int)$totalOrders) ?></h2>
            <small>Toutes les commandes enregistrées</small>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <i class="fa-solid fa-chart-line" style="color:#ddd"></i>
            </div>

            <h3>Clients</h3>
            <h2><?= number_format((int)$totalCustomers) ?></h2>
            <small>Clients uniques identifiés par téléphone</small>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <i class="fa-solid fa-coins" style="color:#ddd"></i>
            </div>

            <h3>Valeur des commandes</h3>
            <h2 style="font-size:21px">
                <?= money($totalAmount) ?>
            </h2>
            <small>Somme des montants enregistrés</small>
        </div>

    </div>

    <!-- Second Statistics -->

    <div class="content-grid">

        <div class="panel">

            <h2>
                <i class="fa-solid fa-chart-bar"></i>
                Commandes par statut
            </h2>

            <?php if (!empty($statuses)): ?>

                <?php foreach ($statuses as $item): ?>

                    <?php
                    $percentage = $totalOrders > 0
                        ? ((int)$item['total'] / $totalOrders) * 100
                        : 0;
                    ?>

                    <div class="status-item">

                        <div class="status-info">
                            <strong>
                                <?= e($item['status'] ?: 'Non défini') ?>
                            </strong>

                            <span>
                                <?= (int)$item['total'] ?>
                                (<?= round($percentage, 1) ?>%)
                            </span>
                        </div>

                        <div class="progress">
                            <div class="progress-bar"
                                 style="width:<?= $percentage ?>%">
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="empty">
                    Aucune commande enregistrée.
                </div>

            <?php endif; ?>

        </div>

        <div class="panel">

            <h2>
                <i class="fa-solid fa-chart-simple"></i>
                Résumé de la boutique
            </h2>

            <div class="summary-item">
                <span>Produits vendus</span>
                <strong><?= number_format((int)$totalSold) ?></strong>
            </div>

            <div class="summary-item">
                <span>Gâteaux personnalisés</span>
                <strong><?= number_format((int)$totalCustomCakes) ?></strong>
            </div>

            <div class="summary-item">
                <span>Avis clients</span>
                <strong><?= number_format((int)$totalReviews) ?></strong>
            </div>

            <div class="summary-item">
                <span>Valeur moyenne par commande</span>

                <strong>
                    <?= money($totalOrders > 0 ? $totalAmount / $totalOrders : 0) ?>
                </strong>
            </div>

        </div>

    </div>

    <!-- Latest Orders -->

    <div class="table-panel">

        <h2>
            <i class="fa-solid fa-clock-rotate-left"></i>
            Dernières commandes
        </h2>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Total</th>
                    <th>Statut</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($latestOrders && mysqli_num_rows($latestOrders) > 0): ?>

                    <?php while ($order = mysqli_fetch_assoc($latestOrders)): ?>

                        <tr>
                            <td>#<?= (int)$order['id'] ?></td>

                            <td>
                                <?= e($order['customer_name']) ?>
                            </td>

                            <td>
                                <?= e($order['phone']) ?>
                            </td>

                            <td>
                                <strong><?= money($order['total']) ?></strong>
                            </td>

                            <td>
                                <span class="badge">
                                    <?= e($order['status'] ?: 'Non défini') ?>
                                </span>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="empty">
                            Aucune commande pour le moment.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <div class="footer">
        © <?= date('Y') ?> Y&A Macarons & Gâteaux - Admin Statistics
    </div>

</div>
<script src="connection.js"></script>
</body>
</html>
