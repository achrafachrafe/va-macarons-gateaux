<?php include 'db.php'; 
session_start();

$count = 0;

if(isset($_SESSION['cart'])){
$count = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V&A Macarons & Gateaux - Rabat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="social-sidebar">
    <a href="https://www.facebook.com" class="social-icon fb"><i class="fab fa-facebook-f"></i></a>
    <a href="https://www.tiktok.com/@achraf2078?_r=1&_t=ZS-96Gy9z1sDn2" class="social-icon tk"><i class="fab fa-tiktok"></i></a>
    <a href="https://www.instagram.com/achr_af_77?igsh=MWttYmphZjE2NHlheg==" class="social-icon ig"><i class="fab fa-instagram"></i></a>
</div>

<header id="header">
    <div class="logo">V&A <br> <span class="ii" style="font-weight:400">Pâtisserie</span></div>
    <nav>
        <ul>
            <li><a class="ii" href="#">Accueil</a></li>
            <li><a class="ii" href="#sellers">Meilleures Ventes</a></li>
            <li><a class="ii" href="#location">Contact</a></li>
        </ul>
    </nav>
    </header>

<section class="hero">

    <div class="hero-text" data-aos="fade-right">
        <span class="badge">Artisan Pâtissier</span>

        <h1 class="ii">Douceurs d'exception à Rabat</h1>

        <p class="ii">
            Savourez nos créations uniques, faites main avec amour et des ingrédients d'excellence.
            Le goût du bonheur à chaque bouchée.
        </p>

        <a href="#menu" class="hero-btn">Explorer le Menu</a>
    </div>
    <div class="hero-img" data-aos="zoom-in" >
        <img src="mlogo.jpeg" alt="Macarons">
    </div>

</section><br>
  <a href="https://wa.me/212611146541" class="btn-main">Commander</a>
    <select class="lang-select" onchange="changeLang(this.value)">
    <option value="fr">Français</option>
    <option value="ar">العربية</option>
    <option value="en">English</option>
    <option value="sp">Espagnol</option>
    <option value="pr">Portugais</option>
</select><br><br>
<button class="dark-mode" id="mode" onclick="toggleDarkMode()">
🌙 Dark Mode
</button>
<h1 class="titre">Bienvenue dans notre boutique</h1>
<div class="products-wrapper">
<div class="video-section" id="m">

    <div class="img-card">
        <img src="uploads/cake1.jpeg" alt="Gateaux" >
    </div>

    <div class="img-card">
        <img src="uploads/cake2.jpeg" alt="Gateaux" >
    </div>

    <div class="img-card">
    <img src="uploads/cake3.jpeg" alt="Gateaux" >
    </div>

</div>
</div>
<a href="custom_cake.php">
<button class="button" >
🎂 Personnaliser mon Gateau
</button>
</a><br><br>
<a href="view_cart.php">
<button class="button" id="cart">
🛒 Panier (
    <span id="cart-count">
<?php echo $count; ?>
</span>
)
</button>
</a>

<h1>🆕 Nouveautés</h1>
<div class="products-wrapper">
<div class="products">

<?php

$new=mysqli_query($conn,"
SELECT *
FROM products
ORDER BY id DESC
LIMIT 3
");

while($row=mysqli_fetch_assoc($new)){
?>

<div class="card" data-aos="zoom-in">

<img src="<?php echo $row['image']; ?>" width="300">

<h2><?php echo $row['name']; ?></h2>

<p><?php echo $row['price']; ?> DH</p>
<?php

$avg=mysqli_query($conn,"
SELECT
AVG(rating) avg_rating,
COUNT(*) total_reviews

FROM reviews

WHERE product_id=".$row['id']);

$data=
mysqli_fetch_assoc($avg);

?>

<h3>

⭐

<?php

if($data['total_reviews']>0){

echo round(
$data['avg_rating'],
1
);

echo " (".
$data['total_reviews'].
" reviews)";

}else{

echo "No reviews yet";

}

?>

</h3>
<button class="btn-review"
onclick="toggleReviews(
<?php echo $row['id']; ?>
)">
Afficher les avis
</button>
<div
id="reviews-<?php echo $row['id']; ?>"
style="display:none;">

<?php

$reviews=mysqli_query($conn,"
SELECT *
FROM reviews
WHERE product_id=".$row['id']);

while($rev=
mysqli_fetch_assoc($reviews)){
?>

<div class="review-box">

<?php
echo str_repeat(
'⭐',
$rev['rating']
);
?>

<br>

<?php
echo $rev['comment'];
?>
<button onclick="likeReview(<?= $rev['id'] ?>, this)">
👍 Utile (
<span id="likes-<?= $rev['id'] ?>">
<?= $rev['likes'] ?>
</span>
)
</button>


<br>

✔ Acheteur vérifié

</div>

<?php } ?>

</div>
<p class="new">
🆕 Nouveau
</p>
<?php

$reviews=mysqli_query($conn,"
SELECT *
FROM reviews
WHERE product_id=".$row['id']);

while($rev=
mysqli_fetch_assoc($reviews)){
?>

<?php } ?>
<button class="btn-buy"
onclick="addCart(<?php echo $row['id']; ?>)">
Acheter
</button>
<button class="btn-rate"
onclick="showReview(<?php echo $row['id']; ?>)">
⭐ Évaluer
</button>

</div>

<?php } ?>

</div>
</div>

<h1 id="sellers">🔥 Produits les plus vendus</h1>
<div class="products-wrapper">
<div class="products">

<?php
$best_ids=[];
$best=mysqli_query($conn,
"SELECT * FROM products
ORDER BY sold_count DESC
LIMIT 3");

while($row=mysqli_fetch_assoc($best)){

$best_ids[]=$row['id'];
?>
<div class="card" data-aos="zoom-in">

<img src="<?php echo $row['image']; ?>" width="300">

<h2>
<?php echo $row['name']; ?>
</h2>

<p>
<?php echo $row['price']; ?> DH
</p>

<?php

$avg=mysqli_query($conn,"
SELECT
AVG(rating) avg_rating,
COUNT(*) total_reviews

FROM reviews

WHERE product_id=".$row['id']);

$data=
mysqli_fetch_assoc($avg);

?>

<h3>

⭐

<?php

if($data['total_reviews']>0){

echo round(
$data['avg_rating'],
1
);

echo " (".
$data['total_reviews'].
" reviews)";

}else{

echo "No reviews yet";

}

?>

</h3>
<button class="btn-review"
onclick="toggleReviews(
<?php echo $row['id']; ?>
)">
Afficher les avis
</button>
<div
id="reviews-<?php echo $row['id']; ?>"
style="display:none;">

<?php

$reviews=mysqli_query($conn,"
SELECT *
FROM reviews
WHERE product_id=".$row['id']);

while($rev=
mysqli_fetch_assoc($reviews)){
?>

<div class="review-box">

<?php
echo str_repeat(
'⭐',
$rev['rating']
);
?>

<br>

<?php
echo $rev['comment'];
?>
<button onclick="likeReview(<?= $rev['id'] ?>, this)">
👍 Utile (
<span id="likes-<?= $rev['id'] ?>">
<?= $rev['likes'] ?>
</span>
)
</button>


<br>

✔ Acheteur vérifié

</div>

<?php } ?>

</div>
<?php
if($row['sold_count'] >=5){
?>
<p class="best">
🔥 Best Seller
</p>
<?php
}
?>
<?php

$reviews=mysqli_query($conn,"
SELECT *
FROM reviews
WHERE product_id=".$row['id']);

while($rev=
mysqli_fetch_assoc($reviews)){
?>



<?php } ?>
<button class="btn-buy"
onclick="addCart(<?php echo $row['id']; ?>)">
Acheter
</button>
<button class="btn-rate"
onclick="showReview(<?php echo $row['id']; ?>)">
⭐ Évaluer
</button>

</div>

<?php } ?>

</div>
</div>

<h1 id="menu">Tous les Produits</h1>
<div class="products">

<?php
if(count($best_ids)>0){

$ids=implode(",",$best_ids);

$sql="
SELECT *
FROM products
WHERE id NOT IN ($ids)
";

}else{

$sql="
SELECT *
FROM products
";

}

$result=mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)){
?>
<div class="card" data-aos="zoom-in">
<img src="<?php echo $row['image']; ?>" width="300">
<h2><?php echo $row['name']; ?></h2>
<p><?php echo $row['price']; ?> DH</p>
<?php

$avg=mysqli_query($conn,"
SELECT
AVG(rating) avg_rating,
COUNT(*) total_reviews

FROM reviews

WHERE product_id=".$row['id']);

$data=
mysqli_fetch_assoc($avg);

?>

<h3>

⭐

<?php

if($data['total_reviews']>0){

echo round(
$data['avg_rating'],
1
);

echo " (".
$data['total_reviews'].
" reviews)";

}else{

echo "No reviews yet";

}

?>

</h3>
<button class="btn-review"
onclick="toggleReviews(
<?php echo $row['id']; ?>
)">
Afficher les avis
</button>
<div
id="reviews-<?php echo $row['id']; ?>"
style="display:none;">

<?php

$reviews=mysqli_query($conn,"
SELECT *
FROM reviews
WHERE product_id=".$row['id']);

while($rev=
mysqli_fetch_assoc($reviews)){
?>

<div class="review-box">

<?php
echo str_repeat(
'⭐',
$rev['rating']
);
?>

<br>

<?php
echo $rev['comment'];
?>

<button onclick="likeReview(<?= $rev['id'] ?>, this)">
👍 Utile (
<span id="likes-<?= $rev['id'] ?>">
<?= $rev['likes'] ?>
</span>
)
</button>
<br>

✔ Acheteur vérifié

</div>

<?php } ?>

</div>
<?php

$reviews=mysqli_query($conn,"
SELECT *
FROM reviews
WHERE product_id=".$row['id']);

while($rev=
mysqli_fetch_assoc($reviews)){
?>


<?php } ?>
<button class="btn-buy"
onclick="addCart(<?php echo $row['id']; ?>)">
Acheter
</button>
<button class="btn-rate"
onclick="showReview(<?php echo $row['id']; ?>)">
⭐ Évaluer
</button>
</div>

<?php } ?>

</div>
<div id="toast">
✅ Produit ajouté au panier
</div>
<div id="reviewBox">

<form  action="review.php" method="POST">

<h2>Taux de Produit</h2>

<input
name="name"
placeholder="Nom"
required>

<input
type="hidden"
name="product"
id="product_id">

<select name="rating">
<option value="5">
⭐⭐⭐⭐⭐
</option>

<option value="4">
⭐⭐⭐⭐
</option>

<option value="3">
⭐⭐⭐
</option>

</select>

<textarea
name="comment"
placeholder="Commentaire">
</textarea>
<button name="send">
Envoyer
</button>
<button
type="button"
onclick="closeReview()">
Fermer
</button>
</form>
</div>
<section class="features">

    <div class="feature">
        <i class="fas fa-pencil-alt"></i>
        <h3 class="bb">Faire Main</h3>
        <p class="bb">Chaque pièce est unique et travaillée avec précision artisanale.</p>
    </div>

    <div class="feature">
        <i class="fas fa-truck"></i>
        <h3 class="bb">Livraison</h3>
        <p class="bb">Nous livrons vos commandes partout à Rabat.</p>
    </div>

    <div class="feature">
        <i class="fas fa-heart"></i>
        <h3 class="bb">Passion</h3>
        <p class="bb">Plus qu'un métier, une passion pour le goût et l'esthétique.</p>
    </div>

</section>


<section class="stats">

    <div class="stat">
        <h2>5+</h2>
        <p>Ans de Savoir-faire</p>
    </div>

    <div class="stat">
        <h2>10K+</h2>
        <p>Clients Comblés</p>
    </div>

    <div class="stat">
        <h2>98%</h2>
        <p>Satisfaction</p>
    </div>

</section>
<h2>Nous trouver à Rabat</h2>

<div class="map" id="location">

    <!-- Google Map -->
    <iframe
        src="https://www.google.com/maps?q=Rabat%20Morocco&output=embed"
        width="100%"
        height="350"
        style="border:0; border-radius:15px;"
        loading="lazy">
    </iframe>

    <!-- زر فتح فـ Google Maps -->
    <div style="text-align:center; margin-top:10px;">
        <a href="https://www.google.com/maps/place/Rabat,+Morocco"
           target="_blank">

            <button class="map-btn">
                Ouvrir dans Google Maps
            </button>
<br>
        </a>
    </div>

</div>
<br><br>
<footer class="footer">

  <div class="footer-top">

    <!-- Brand -->
    <div class="box">
      <h2>V&A Pâtisserie</h2>
      <p>Des douceurs artisanales unique,préparées avec amour et les meilleurs ingrédients.
🍰<br>Un gout de bonheur à chaque bouchée.

      </p>
    </div>

    <!-- Links -->
    <div class="box">
      <h3>Liens importants</h3>
      <a href="#">🏠 Accueil</a>
      <a href="#m">🍩 Nos Délices</a>
      <a href="#menu">🛒 Commander</a>
      <a href="#location">📞 Contact</a>
    </div>

    <!-- Contact -->
    <div class="box">
      <h3>Contact</h3>
      <p>📍 Rabat, Maroc</p>
      <p>📞 +212 7 13 45 83 96</p>
      <p>✉️ contact@yapatisserie.com</p>
      <p>⏰ 9:00 - 20:00</p>
    </div>

    <!-- Social -->
    <div class="box">
      <h3>Follow</h3>
      <a href="https://www.instagram.com/achr_af_77?igsh=MWttYmphZjE2NHlheg==">📸 Instagram</a>
      <a href="https://www.facebook.com">📘 Facebook</a>
      <a href="https://www.tiktok.com/@achraf2078?_r=1&_t=ZS-96Gy9z1sDn2">🎵 TikTok</a>
      <a href="https://wa.me/212611146541">💬 WhatsApp</a>
    </div>

  </div>

  <div class="footer-bottom">
    <p>&copy;2026 V&A Pâtisserie | Tous droits réservés</p>
  </div>

</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="connection.js"></script>
<script src="scripte.js"></script>
</body>
</html>
