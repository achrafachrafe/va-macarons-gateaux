<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V&A Macarons & Gateaux - Rabat</title>
    <style>
    /* 🔹 BACKGROUND */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #ffe4ec, #fff0f6);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* 🔹 FORM BOX */
form {
    background: white;
    padding: 40px;
    border-radius: 25px;
     width: 100%;
    max-width: 800px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

/* 🔹 TITLE */
form h1 {
    text-align: center;
    color: #ff4fa3;
    margin-bottom: 25px;
}

/* 🔹 INPUTS */
input, select, textarea {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 12px;
    border: 1px solid #ddd;
    outline: none;
    font-size: 14px;
    transition: 0.3s;
}

/* 🔹 FOCUS */
input:focus, select:focus, textarea:focus {
    border-color: #ff4fa3;
    box-shadow: 0 0 5px rgba(255,79,163,0.3);
}

/* 🔹 LABEL */
label {
    font-weight: 600;
    margin-top: 10px;
    display: block;
}

/* 🔹 PRICE BOX */
h2 {
    background: #fff0f6;
    padding: 12px;
    border-radius: 12px;
    text-align: center;
    color: #ff4fa3;
}

/* 🔹 BUTTON */
button {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 30px;
    background: #ff4fa3;
    color: white;
    font-size: 16px;
    cursor: pointer;
    margin-top: 15px;
    transition: 0.3s;
}

button:hover {
    background: #ff2d8d;
    transform: translateY(-2px);
}

/* 🔹 FILE INPUT */
input[type="file"] {
    background: #fff0f6;
    border: 1px dashed #ff4fa3;
}
textarea{
    resize:vertical;
    min-height:100px;
}
/* 🔹 RESPONSIVE */
@media(max-width: 500px) {
    form {
        width: 90%;
        padding: 25px;
    }
}
#price {
    display: inline-block;
    transition: 0.3s;
}
.group {
    background: #fff0f6;
    padding: 15px;
    border-radius: 15px;
    margin-bottom: 15px;
}

.group h3 {
    margin-bottom: 10px;
    color: #ff4fa3;
}
html,
body {
    overflow-x: hidden;
    max-width: 100%;
}
* {
    box-sizing: border-box;
}
/*////////*/
.success{
    max-width:500px;
    margin:40px auto;
    background:#fff;
    padding:35px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
}

.success h2{
    color:#28a745;
    margin-bottom:15px;
}

.success p{
    color:#555;
    font-size:17px;
    line-height:1.7;
    margin:12px 0;
}

.home-btn{
    display:inline-block;
    margin-top:20px;
    padding:12px 25px;
    background:#ff4fa3;
    color:#fff;
    text-decoration:none;
    border-radius:30px;
    transition:.3s;
}

.home-btn:hover{
    background:#ff2d8d;
}
</style>
</head>
<body>
<?php include 'db.php'; ?>

<?php

if(isset($_POST['send'])){

$name=$_POST['name'];
$phone=$_POST['phone'];
$address=$_POST['address'];

$size=$_POST['size'];
$flavor=$_POST['flavor'];
$color=$_POST['color'];
$layers=$_POST['layers'];
$message=$_POST['message'];

$price=0;

$price=(int)$size;
$price += ($layers*30);
if($color=="Gold"){
$price+=30;
}
$image="";

if(!empty($_FILES['image']['name'])){

$image=$_FILES['image']['name'];

move_uploaded_file(
$_FILES['image']['tmp_name'],
"uploads/".$image
);

}

$stmt = $conn->prepare("
INSERT INTO custom_cakes
(name,phone,address,size,flavor,color,layers,message,image,price)
VALUES (?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "ssssssissi",
    $name,
    $phone,
    $address,
    $size,
    $flavor,
    $color,
    $layers,
    $message,
    $image,
    $price
);

$stmt->execute();
echo "
<div class='success'>

<h2>🎉 Commande personnalisée confirmée !</h2>

<p>
Merci <strong>$name</strong>,
<br>
Votre commande personnalisée a été envoyée avec succès.
</p>

<p>
💰 Prix estimé : <strong>$price DH</strong>
</p>

<p>
🚚 Livraison prévue dans les 24 heures.
</p>

<a href='index.php' class='home-btn'>
Retour à l'accueil
</a>

</div>
";

exit();
}
?>

<form method="POST"
enctype="multipart/form-data">

<h1>🎂 Design Your Cake</h1>

<div class="group">
    <h3>Informations</h3>
<input name="name"
placeholder="Nom" required>

<input name="phone"
placeholder="Téléphone" required>

<textarea
name="address"
placeholder="Votre adresse" required>
</textarea>
</div>

<div class="group">
    <h3>Choix du gâteau</h3>
<label>Taille</label>

<select id="size" name="size" onchange="calcPrice()">
<option value="150">Petit</option>
<option value="200">Moyen</option>
<option value="250">Grand</option>
</select>

<select name="flavor">
<option>Chocolate</option>
<option>Vanilla</option>
<option>Red Velvet</option>
</select>

<select id="color" name="color" onchange="calcPrice()">
<option>Pink</option>
<option>White</option>
<option>Gold</option>
</select>

<label>Nombre d'étages</label>

<input
type="number"
id="layers"
name="layers"
value="1"
min="1"
oninput="calcPrice()">
</div>


<h2>
Prix:
<span id="price">
180
</span> DH
</h2>

<input name="message"
placeholder="Message sur gâteau">

<label>Image inspiration</label>

<input type="file"
name="image">

<button name="send">
Commander
</button>

</form>
<script>
function calcPrice(){

let size=
parseInt(
document.getElementById("size").value
);

let layers=
parseInt(
document.getElementById("layers").value
);

let color=
document.getElementById("color").value;

let total=
size + (layers*30);

if(color=="Gold"){
total+=30;
}

document.getElementById("price").innerHTML=
total;
document.getElementById("price").style.transform = "scale(1.2)";
setTimeout(() => {
    document.getElementById("price").style.transform = "scale(1)";
}, 200);
}

</script>
<script src="connection.js"></script>
</body>
</html>