<?php

require_once "./functions.php";
$conn = getDB();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (!$conn) {
    die("Erreur de connexion à la base de données");
}

// requête temporaire (qui fonctionne pas pour l'instant), car la base de donnée n'est pas encore faite 
$query = "SELECT url, description FROM pays";
$result = $conn->prepare($query);
$result->execute();
$images = $result->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

?>

<!-- Affiche les diapositives (images et descriptions) dans une structure HTML -->
<div class="slide-container">
    <?php foreach ($images as $image): ?>
    <div class="custom-slider fade">
        <img class="slide-img" src="<?php echo $image['url']; ?>">
        <div class="slide-text"><?php echo $image['description']; ?></div>
    </div>
    <?php endforeach; ?>

    <!-- Ajoute des boutons de navigation précédent et suivant -->
    <a class="prev" onclick="plusSlides(-1)">❮</a>
    <a class="next" onclick="plusSlides(1)">❯</a>
</div>
<br>

<!-- Affiche les indicateurs de diapositive (points) pour la navigation -->
<div class="slide-dot">
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
</div>