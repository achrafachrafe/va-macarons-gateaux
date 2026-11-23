function addCart(id){

fetch("cart.php?id="+id)
.then(r=>r.text())
.then(()=>{

let toast=
document.getElementById("toast");

toast.innerHTML=
"🔥 Added successfully";

toast.style.display="block";

setTimeout(function(){
toast.style.display="none";
},2500);

updateCartCount();
});
}

function updateCartCount(){
fetch("cart_count.php")
.then(r=>r.text())
.then(count=>{
document.getElementById(
"cart-count"
).innerHTML=count;

});

}
setInterval(
updateCartCount,
1000
);
updateCartCount();
//////
function showReview(id){

document
.getElementById(
"reviewBox"
).style.display="block";

document
.getElementById(
"product_id"
).value=id;

}

function closeReview(){

document
.getElementById(
"reviewBox"
).style.display="none";

}
//////

function toggleReviews(id){

let box=
document.getElementById(
"reviews-"+id
);

if(
box.style.display=="none"
){
box.style.display="block";
}
else{
box.style.display="none";
}

}
///////
function likeReview(id, btn) {

    fetch("like_review.php?id=" + id)
    .then(res => res.json())
    .then(data => {

        // غير العدد (ما تمسحش HTML)
        document.getElementById("likes-" + id).innerText = data.count;

        // غير اللون فقط
        if (data.status === "liked") {
            btn.style.color = "green";
        } else {
            btn.style.color = "black";
        }

    });

}
////////
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    // تغيير النص ديال الزر
    let btn = document.getElementById("mode");

    if (document.body.classList.contains("dark-mode")) {
        btn.innerHTML = "☀️ Light Mode";
        localStorage.setItem("mode", "dark");
    } else {
        btn.innerHTML = "🌙 Dark Mode";
        localStorage.setItem("mode", "light");
    }
}

// باش يبقى محفوظ حتى من بعد refresh
window.onload = function () {
    let savedMode = localStorage.getItem("mode");

    if (savedMode === "dark") {
        document.body.classList.add("dark-mode");
        document.getElementById("mode").innerHTML = "☀️ Light Mode";
    }
}
//////

function changeLang(lang){
    if(lang === "ar"){
        window.location.href = "arabic.php";
    }else if(lang === "fr"){
        window.location.href = "index.php";
    }else if(lang === "en"){
        window.location.href = "english.php";
    }else if(lang === "sp"){
        window.location.href = "spanish.php";
    }else if(lang === "pr"){
        window.location.href = "portugais.php";
    }
}
////////
    AOS.init({ duration: 1000, once: true });

    window.addEventListener('scroll', () => {
        document.getElementById('header').classList.toggle('header-scrolled', window.scrollY > 50);
    });