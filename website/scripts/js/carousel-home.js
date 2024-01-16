// Initialisation du du carrousel
var slideIndex = 1;
var userClicked = false; 
showSlides(slideIndex);

// Fonction pour avancer ou reculer dans les diapositives
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Fonction pour afficher une diapositive spécifique
function currentSlide(n) {
    showSlides(slideIndex = n);
}

// Fonction pour gérer le clic sur le slider
function clickSlider() {
    userClicked = true; 
    setTimeout(function() {
        userClicked = false; 
    }, 600000); 
}

// Fonction principale pour gérer l'affichage des diapositives
function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("custom-slider");
    var sliderPrev = document.getElementsByClassName("prev")
    var sliderNext = document.getElementsByClassName("next")
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        if (sliderNext[i]) {
            sliderNext[i].addEventListener('click', clickSlider);
        }
        if (sliderPrev[i]) {
            sliderPrev[i].addEventListener('click', clickSlider);
        }
    }
    slides[slideIndex-1].style.display = "grid";
}

// Fonction pour avancer automatiquement les diapositives à intervalles réguliers
function autoSlide() {
    if (!userClicked) {
        plusSlides(1);
    }
}

setInterval(autoSlide, 3000);

