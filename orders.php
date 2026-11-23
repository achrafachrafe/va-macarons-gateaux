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
    color: #44343c;
    margin: 0;
    padding: 30px 25px;
}

/* Page Title */
h1 {
    text-align: center;
    color: #e84393;
    font-size: 30px;
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

/* Links */
a {
    text-decoration: none;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: linear-gradient(135deg, #ff69b4, #e84393);
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    transition: .3s;
    box-shadow: 0 5px 15px rgba(232,67,147,.15);
}

.back-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 7px 18px rgba(232,67,147,.25);
}

/* Table Container */
.table-container {
    width: 100%;
    overflow-x: auto;
    margin: 30px auto;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(180,90,130,.08);
}

/* Table */
table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
    background: #fff;
    overflow: hidden;
}

/* Header */
th {
    background: linear-gradient(135deg, #ff69b4, #e84393);
    color: white;
    padding: 16px 12px;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: .5px;
    white-space: nowrap;
}

/* Cells */
td {
    padding: 15px 12px;
    text-align: center;
    border-bottom: 1px solid #f8e2eb;
    color: #55464e;
    font-size: 13px;
    line-height: 1.6;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background: #fff3f7;
}

/* Status */
.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.status-btn {
    margin-top: 6px;
    display: block;
    width: 100%;
    min-width: 95px;
}

/* Buttons */
button {
    background: #ff4d88;
    color: white;
    border: none;
    padding: 9px 13px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 600;
    transition: .3s;
    white-space: nowrap;
}

button:hover {
    background: #e6005c;
    transform: translateY(-2px);
}

/* Delete Button */
td:last-child button {
    background: #fff0f0;
    color: #e04444;
    border: 1px solid #ffd1d1;
}

td:last-child button:hover {
    background: #e04444;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    body {
        padding: 20px 12px;
    }

    h1 {
        font-size: 25px;
    }

    .table-container {
        margin-top: 25px;
    }

    .back-btn {
        padding: 10px 15px;
    }
}

</style>
</head>
<body>
<?php
include "db.php";

$result = mysqli_query($conn,
"SELECT * FROM orders ORDER BY id DESC");
?>

<h1>Liste des commandes</h1>
<a href="admin.php" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Dashboard
        </a>
        <div class="table-container">
<table border="1" cellpadding="10">
<tr>
<th>Status</th>
<th>ID</th>
<th>Client</th>
<th>Téléphone</th>
<th>Adresse</th>
<th>Total</th>
<th>Supprimer</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result)){
?>

<tr>
    <td>

<?php
if($row['status']=="livré"){
echo "<span style='color:green;font-weight:bold'>
✔ Livré
</span>";
}
elseif($row['status']=="annulé"){
echo "<span style='color:red;font-weight:bold'>
❌ Annulé
</span>";
}
else{
echo "<span style='color:orange;font-weight:bold'>
⏳ En attente
</span>";
}
?>

<br><br>

<a href="update_status.php?id=<?php echo $row['id']; ?>&status=livré">
<button class="status-btn">
✔ Livré
</button>
</a>

<a href="update_status.php?id=<?php echo $row['id']; ?>&status=annulé">
<button class="status-btn">
❌ Annulé
</button>
</a>

</td>
<td><?php echo $row['id']; ?></td>

<td>
<?php echo $row['customer_name']; ?>
</td>

<td>
<?php echo $row['phone']; ?>
</td>

<td>
<?php echo $row['address']; ?>
</td>

<td>
<?php echo $row['total']; ?> DH
</td>

<td>
<a href="delete_order.php?id=<?=$row['id']?>"
onclick="return confirm('Supprimer cette commande ?')">
<button>Supprimer</button>
</a>
</td>
</tr>

<?php } ?>

</table>
</div>
<script src="connection.js"></script>
</body>
</html>