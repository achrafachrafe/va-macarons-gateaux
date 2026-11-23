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
    <title>V&A Macaron & bolos - Rabat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="social-sidebar">
    <a href="https://www.facebook.com" class="social-icon fb"><i class="fab fa-facebook-f"></i></a>
    <a href="https://www.tiktok.com/@achraf2078?_r=1&_t=ZS-96Gy9z1sDn2" class="social-icon tk"><i class="fab fa-tiktok"></i></a>
    <a href="https://www.instagram.com/achr_af_77?igsh=MWttYmphZjE2NHlheg==" class="social-icon ig">
    <i class="fab fa-instagram"></i>
</a>
</div>
<header id="header">
    <div class="logo">V&A <br> <span class="ii" style="font-weight:400">confeitaria</span></div>
    <nav>
        <ul>
            <li><a class="ii" href="#">Bem-vindo</a></li>
            <li><a class="ii" href="#sellers">Mais vendidos</a></li>
            <li><a class="ii" href="#location">contato</a></li>
        </ul>
    </nav>
    </header>

<section class="hero">
    <div class="hero-text" data-aos="fade-right">
        <span class="badge">Confeitaria artesanal</span>

        <h1 class="ii">Doces excepcionais em Rabat</h1>

        <p class="ii">
            Descubra as nossas criações exclusivas, feitas à mão com amor e os melhores ingredientes. 
            O sabor da felicidade em cada dentada.
        </p>

        <a href="#menu" class="hero-btn">Explore o Menu</a>
    </div>
    <div class="hero-img" data-aos="zoom-in">
        <img src="mlogo.jpeg" alt="Macarons">
    </div>
</section><br>
    <a href="https://wa.me/212611146541" class="btn-main">Ordem</a>
    <select class="lang-select" onchange="changeLang(this.value)">
    <option value="pr">Portugais</option>
    <option value="fr">Français</option>
    <option value="ar">العربية</option>
    <option value="en">English</option>
    <option value="sp">Espagnol</option>
</select><br><br>
<button class="dark-mode" id="mode" onclick="toggleDarkMode()">
🌙 Mode escuro
</button>
<h1 class="titre">Bem-vindo à nossa loja</h1>
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
🎂 Personalizar o meu bolo
</button>
</a><br><br>
<a href="view_cart.php">
<button class="button" id="cart">
🛒 Cesta (
    <span id="cart-count">
<?php echo $count; ?>
</span>
)
</button>
</a>

<h1>🆕 Novos produtos</h1>
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
Mostrar avaliações
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
👍 Útil (
<span id="likes-<?= $rev['id'] ?>">
<?= $rev['likes'] ?>
</span>
)
</button>


<br>

✔ Comprador verificado

</div>

<?php } ?>

</div>
<p class="new">
🆕 Novo
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
Comprar
</button>
<button class="btn-rate"
onclick="showReview(<?php echo $row['id']; ?>)">
⭐ Avaliador
</button>

</div>

<?php } ?>

</div>
</div>
<h1 id="sellers">🔥 Produtos mais vendidos</h1>
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
Mostrar avaliações
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
👍 Útil (
<span id="likes-<?= $rev['id'] ?>">
<?= $rev['likes'] ?>
</span>
)
</button>


<br>

✔ Comprador verificado

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
Comprar
</button>
<button class="btn-rate"
onclick="showReview(<?php echo $row['id']; ?>)">
⭐ Avaliar
</button>

</div>

<?php } ?>

</div>
</div>
<h1 id="menu">Todos os produtos</h1>
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
Mostrar avaliações
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
👍 Útil (
<span id="likes-<?= $rev['id'] ?>">
<?= $rev['likes'] ?>
</span>
)
</button>
<br>

✔ Comprador verificado

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
comprar
</button>
<button class="btn-rate"
onclick="showReview(<?php echo $row['id']; ?>)">
⭐ Avaliar
</button>
</div>

<?php } ?>

</div>
<div id="toast">
✅ Produto adicionado ao carrinho
</div>
<div id="reviewBox">

<form action="review.php" method="POST">

<h2>Avalie o produto</h2>

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
Enviar
</button>
<button
type="button"
onclick="closeReview()">
Fechar
</button>
</form>
</div>
<section class="features">

    <div class="feature">
        <i class="fas fa-pencil-alt"></i>
        <h3 class="bb">Feito à mão</h3>
        <p class="bb">Cada peça é única e elaborada com uma meticulosa precisão artesanal.</p>
    </div>

    <div class="feature">
        <i class="fas fa-truck"></i>
        <h3 class="bb">Entrega</h3>
        <p class="bb">Entregamos os seus pedidos em toda a Rabat.</p>
    </div>

    <div class="feature">
        <i class="fas fa-heart"></i>
        <h3 class="bb">Paixão</h3>
        <p class="bb">Mais do que uma profissão, uma paixão pelo bom gosto e pela estética.</p>
    </div>

</section>


<section class="stats">

    <div class="stat">
        <h2>5+</h2>
        <p>Anos de experiência</p>
    </div>

    <div class="stat">
        <h2>10K+</h2>
        <p>Clientes satisfeitos</p>
    </div>

    <div class="stat">
        <h2>98%</h2>
        <p>Satisfação</p>
    </div>

</section>
<h2>Encontre-nos em Rabat</h2>

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
                Abrir no Google Maps
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
      <h2>V&A Confeitaria</h2>
      <p>
        Doces artesanais únicos, preparados com amor e os melhores ingredientes.
🍰<br>Um gostinho de felicidade em cada dentada.
      </p>
    </div>

    <!-- Links -->
    <div class="box">
      <h3>Links importantes</h3>
      <a href="#">🏠 Bem-vindo</a>
      <a href="#m">🍩 Nossas Delícias</a>
      <a href="#menu">🛒 Ordem</a>
      <a href="#location">📞 contato</a>
    </div>

    <!-- Contact -->
    <div class="box">
      <h3>contato</h3>
      <p>📍 Rabat, Marrocos</p>
      <p>📞 +212 6 11 14 65 41</p>
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
    <p>&copy;2026 V&A Confeitaria | Todos os direitos reservados</p>
  </div>

</footer>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="connection.js"></script>
<script src="scripte.js"></script>
</body>
</html>