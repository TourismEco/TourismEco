<?php
require_once "../functions.php";
$conn = getDB();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (!$conn) {
    die("Erreur de connexion à la base de données");
}


$query = "SELECT id, nom FROM pays
ORDER BY RAND()
LIMIT 5";
$result = $conn->prepare($query);
$result->execute();
$images = $result->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

?>

<!-- Affiche les diapositives (images et descriptions) dans une structure HTML -->
<div class="slide-container">
    <?php foreach ($images as $image): ?>
    <div class="custom-slider">
        <img class="slide-img" src="../../website/assets/img/<?php echo $image['id']; ?>.jpg" >
        <div class="slide-text">
            <img class="slide-logo" src="../../website/assets/twemoji/<?php echo $image['id']; ?>.svg" alt="Logo SVG">
            <p><?php echo $image['nom']; ?> </p>
        </div>
        
    </div>
    <?php endforeach; ?>
    <h1> Ecotourisme </h1>
    <h2> Partez à la découverte du monde </h2>

    <!-- Ajoute des boutons de navigation précédent et suivant -->
    <a class="prev" onclick="plusSlides(-1)">❮</a>
    <a class="next" onclick="plusSlides(1)">❯</a>
</div>