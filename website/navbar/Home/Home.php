<!DOCTYPE html>
<html lang= "fr">

<head>
  <meta charset="UTF-8">
  <link href="styles/style.css" rel="stylesheet" type="text/css" />
  <title> Home </title>

  <?php
 
    $bdd = getDB();
  ?>

    <!-- <script src="./assets/js/map.js"></script> -->

</head>

<body>
  
  <div class = "navbar"> 
  <?php require 'navbar/navbar.php'?>
  </div>
    
  
  <div class ="carrousel">
    <?php require "./carrousel/carrousel.php" ?>
    <script src="./carrousel/carrousel.js"></script>
  </div>

  <div id = "map"> </div>

<!-- 
  <script>
      createMap()
  </script> -->

  <div class="section dark-grey">
        <!-- <img src="chemin_vers_votre_logo1.png" alt="Logo 1"> -->
        <h2>Ecotourisme</h2>
        <p>Partez à la découverte du monde</p>
    </div>

    <div class="section light-green">
        <!-- <img src="avion.png" alt="Logo 3"> -->
        <h2>Explorez Le Monde En équilibre</h2>
        <p>Bienvenue sur le site Écotourisme, votre destination en ligne pour explorer et comprendre les données économiques et écologiques de différentes régions du monde. Notre plateforme offre un éventail de fonctionnalités pour vous aider à planifier des voyages plus responsables, basés sur des informations pertinentes.</p>
    </div>

    <div class="section grey">
        <!-- <img src="chemin_vers_votre_logo4.png" alt="Logo 4"> -->
        <h2>Comprendre En Profondeur</h2>
        <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>
    <div class="section dark-grey">
        <!-- <img src="chemin_vers_votre_logo2.png" alt="Logo 2"> -->
        <h2>Voyagez Responsable</h2>
        <p> Planifiez vos trajets en toute conscience environnementale. Notre calculateur d'empreinte carbone vous permet d'estimer l'impact de vos voyages en fonction des moyens de transport, vous aidant ainsi à prendre des décisions éclairées pour réduire votre empreinte carbone. </p>
    </div>
    <div class="section light-green">
        <!-- <img src="chemin_vers_votre_logo3.png" alt="Logo 3"> -->
        <h2>Planifiez et Maîtrisez Votre Budget de Voyage</h2>
        <p>Obtenez des estimations détaillées des coûts de vos trajets, y compris le prix des billets de transport, l'hébergement, et d'autres dépenses associées. Explorez les destinations de votre choix et obtenez des estimations des coûts de séjour, incluant les frais de logement, de nourriture et d'activités. Notre outil vous permet de planifier vos voyages en fonction de votre budget personnel, garantissant ainsi un séjour sans surprise.</p>
    </div>
    <div class="section grey">
        <!-- <img src="chemin_vers_votre_logo4.png" alt="Logo 4"> -->
        <h2>Comprendre En Profondeur</h2>
        <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>

</body>
</html>