<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <link href="styles/style.css" rel="stylesheet" type="text/css" />

  <style>
/* Style pour la première section */
.section,
.section2 {
    text-align: center;
    padding: 80px;
    margin-bottom: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.section img,
.section2 img {
    max-width: 200px;
    height: auto;
    margin-bottom: 30px;
    border-radius: 10px;
}

/* Style pour la section grise */
.grey {
    background-color: #f8f8f8;
    color: #333;
}

/* Style pour la section light-green */
.light-green {
    background-color: #a2d5c6;
    color: #333;
}

/* Style pour la section dark-grey */
.dark-grey {
    background-color: #333;
    color: #fff;
}

/* Ajoutez d'autres styles au besoin pour les différentes sections */

/* Style pour les titres h2 */
h2 {
    color: #333;
    font-size: 2em;
    margin-bottom: 20px;
}

/* Style pour les paragraphes p */
p {
    line-height: 1.6;
    color: #666;
    font-size: 1.1em;
}

/* Ajoutez d'autres styles au besoin */

/* Ajoutez un style de survol pour les sections si nécessaire */
.section:hover,
.section2:hover {
    background-color: #e0e0e0;
}
  </style>
  <title> Home </title>
</head>

<?php require_once '../navbar/navbar.html';?>


<div class ="carrousel">
    <?php carousel($conn) ?>
    <script src="scripts/js/carousel-home.js"></script>
  </div>
    

<body>

    <div class="section2 grey">
        <img src="../assets/img/avion.png" alt="Logo 3">
        <h2>Explorez Le Monde En équilibre</h2>
        <p>Bienvenue sur le site Écotourisme, votre destination en ligne pour explorer et comprendre les données économiques et écologiques de différentes régions du monde. Notre plateforme offre un éventail de fonctionnalités pour vous aider à planifier des voyages plus responsables, basés sur des informations pertinentes.</p>
    </div>

    <div class="section light-green">
        <img src="../assets/img/loupe.png" alt="Logo 4">
        <h2>Comprendre En Profondeur</h2>
        <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>
    <div class="section2 dark-grey">
        <img src="../assets/img/responsable.png" alt="Logo 2">
        <h2>Voyagez Responsable</h2>
        <p> Planifiez vos trajets en toute conscience environnementale. Notre calculateur d'empreinte carbone vous permet d'estimer l'impact de vos voyages en fonction des moyens de transport, vous aidant ainsi à prendre des décisions éclairées pour réduire votre empreinte carbone. </p>
    </div>
    <div class="section grey">
        <img src="../assets/img/budget.png" alt="Logo 3">
        <h2>Planifiez et Maîtrisez Votre Budget de Voyage</h2>
        <p>Obtenez des estimations détaillées des coûts de vos trajets, y compris le prix des billets de transport, l'hébergement, et d'autres dépenses associées. Explorez les destinations de votre choix et obtenez des estimations des coûts de séjour, incluant les frais de logement, de nourriture et d'activités. Notre outil vous permet de planifier vos voyages en fonction de votre budget personnel, garantissant ainsi un séjour sans surprise.</p>
    </div>
    <div class="section2 light-green">
        <img src="../assets/img/analyse.png" alt="Logo 4">
        <h2>Comprendre En Profondeur</h2>
        <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>
</body>
</html>



