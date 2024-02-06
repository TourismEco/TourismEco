<?php require_once 'head.php'?>

<body>
    <div class="container-map" id="container-map">
        <div id="map"></div>
    </div>

    <div class="grille" id="grille" hx-swap="outerHTML">
  <main style="width:100%">
  <div class ="carrousel">
    <?php 
      $conn = getDB();
      carousel($conn) 
    ?>
    <script src="scripts/js/carousel-home.js"></script>
  </div>

  <div class="sectionHome grey">
    <div class="content">
        <h2>Explorez Le Monde En équilibre</h2>
        <p>Bienvenue sur le site Écotourisme, votre destination en ligne pour explorer et comprendre les données économiques et écologiques de différentes régions du monde. Notre plateforme offre un éventail de fonctionnalités pour vous aider à planifier des voyages plus responsables, basés sur des informations pertinentes.</p>
    </div>
    <img src="assets/img/avion.png" alt="Logo 3">
  </div>

  <div class="sectionHome light-green">
    <img src="assets/img/loupe.png" alt="Logo 4">
    <div class="content">
      <h2>Comprendre En Profondeur</h2>
      <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>  
    </div>

  <div class="sectionHome dark-green">
    <div class="content">
      <h2>Voyagez Responsable</h2>
      <p> Planifiez vos trajets en toute conscience environnementale. Notre calculateur d'empreinte carbone vous permet d'estimer l'impact de vos voyages en fonction des moyens de transport, vous aidant ainsi à prendre des décisions éclairées pour réduire votre empreinte carbone. </p>
    </div>  
      <img src="assets/img/responsable.png" alt="Logo 2">
  </div>

  <div class="sectionHome grey">
    <img src="assets/img/budget.png" alt="Logo 3">
    <div class="content">
      <h2>Planifiez et Maîtrisez Votre Budget de Voyage</h2>
      <p>Obtenez des estimations détaillées des coûts de vos trajets, y compris le prix des billets de transport, l'hébergement, et d'autres dépenses associées. Explorez les destinations de votre choix et obtenez des estimations des coûts de séjour, incluant les frais de logement, de nourriture et d'activités. Notre outil vous permet de planifier vos voyages en fonction de votre budget personnel, garantissant ainsi un séjour sans surprise.</p>
    </div>  
    </div>

  <div class="sectionHome light-green">
    <div class="content">
      <h2>Comprendre En Profondeur</h2>
      <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
    </div>
    <img src="assets/img/analyse.png" alt="Logo 4">
  </div>

    <script id=scripting>
        hideMap()
    </script>

  </main>
</div>
  
    <?php require_once 'footer.html'?>
</body>