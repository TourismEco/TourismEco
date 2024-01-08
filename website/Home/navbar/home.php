<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .section, .section2 {
            padding: 20px;
            height: auto;
            overflow: hidden;
            color: white;
        }
    
        .dark-grey {
            background-color: #183A37;
        }
        .grey {
            background-color: #2F3E46;
        }
        .light-green{
            background-color: #52796F;
        }
        img {
            width: 35%; /* Ajustez la largeur de l'image selon vos besoins */
        }
        
        .section img {
            float: right; 
            margin-left: 20px;
        }

        .section2 img {
            float: left; 
            margin-right: 20px;
        }

.section.grey h2,
.section.grey p {
    margin-right: 20px; /* Ajoute une marge à droite du texte pour l'espacement */
}

    </style>
</head>

<?php
    require 'functions.php';
    $bdd = getDB();
  ?>

</head>

<body>
  
  <div class = "navbar"> 
  <?php require 'navbar.php'?>
  </div>
    
  
  <div class ="carrousel">
    <?php require "Home/carrousel/carrousel.php" ?>
    <script src="Home/carrousel/carrousel.js"></script>
  </div>
<body>

    <div class="section dark-grey">
        <img src="vide.png" alt="Logo 1">
        <h2>Ecotourisme</h2>
        <p>Partez à la découverte du monde</p>
    </div>

    <div class="section2 grey">
        <img src="avion.png" alt="Logo 3">
        <h2>Explorez Le Monde En équilibre</h2>
        <p>Bienvenue sur le site Écotourisme, votre destination en ligne pour explorer et comprendre les données économiques et écologiques de différentes régions du monde. Notre plateforme offre un éventail de fonctionnalités pour vous aider à planifier des voyages plus responsables, basés sur des informations pertinentes.</p>
    </div>

    <div class="section light-green">
        <img src="loupe.png" alt="Logo 4">
        <h2>Comprendre En Profondeur</h2>
        <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>
    <div class="section2 dark-grey">
        <img src="responsable.png" alt="Logo 2">
        <h2>Voyagez Responsable</h2>
        <p> Planifiez vos trajets en toute conscience environnementale. Notre calculateur d'empreinte carbone vous permet d'estimer l'impact de vos voyages en fonction des moyens de transport, vous aidant ainsi à prendre des décisions éclairées pour réduire votre empreinte carbone. </p>
    </div>
    <div class="section grey">
        <img src="budget.png" alt="Logo 3">
        <h2>Planifiez et Maîtrisez Votre Budget de Voyage</h2>
        <p>Obtenez des estimations détaillées des coûts de vos trajets, y compris le prix des billets de transport, l'hébergement, et d'autres dépenses associées. Explorez les destinations de votre choix et obtenez des estimations des coûts de séjour, incluant les frais de logement, de nourriture et d'activités. Notre outil vous permet de planifier vos voyages en fonction de votre budget personnel, garantissant ainsi un séjour sans surprise.</p>
    </div>
    <div class="section2 light-green">
        <img src="analyse.png" alt="Logo 4">
        <h2>Comprendre En Profondeur</h2>
        <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>
</body>
</html>



