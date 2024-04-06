<?php require_once 'head.php'?>

<body>
  <div class="home">
  <div class="grille" id="grille" hx-swap="outerHTML">
    <main style="width:100%">
      <div class ="carrousel">
        <?php 
          $conn = getDB();
          carousel($conn) 
        ?>
        <script src="scripts/js/carousel-home.js"></script>
    </div>

    <div class="sectionHome">
      <div class="content" id="R">
          <h2>Explorez le monde en équilibre</h2>
          <p>Bienvenue sur le site Écotourisme, votre destination en ligne pour explorer et comprendre les données économiques et écologiques de différentes régions du monde. Notre plateforme offre un éventail de fonctionnalités pour vous aider à planifier des voyages plus responsables, basés sur des informations pertinentes.</p>
      </div>
      <img class="imgR" src="assets/icons/airBalloon.svg" alt="Logo 3">
      <a href="explore.php" class="btnIndex">Explorez maintenant</a>
    </div>

    <div class="separator"></div>

    <div class="sectionHome">
    <img  class="imgL" src="assets/icons/loupeIndex.svg" alt="Logo 3">
    <a href="explore.php" class="btnIndex2">Page analyse</a>
      <div class="content" id="L">
        <h2>Comprendre en profondeur</h2>
        <p>Accédez à des rapports d'analyse statistique complets basés sur nos données, vous permettant de découvrir des tendances, des corrélations et des informations essentielles pour des décisions de voyage éclairées.</p>
      </div>  
    </div>

    <div class="separator"></div>

    <div class="sectionHome">
      <div class="content" id="R">
        <h2>Voyagez responsable</h2>
        <p> Planifiez vos trajets en toute conscience environnementale. Notre calculateur d'empreinte carbone vous permet d'estimer l'impact de vos voyages en fonction des moyens de transport, vous aidant ainsi à prendre des décisions éclairées pour réduire votre empreinte carbone. </p>
      </div>  
        <img class="imgR" src="assets/icons/CO2.svg" alt="Logo 2">
        <a href="explore.php" class="btnIndex">Accédez au calculateur</a>
    </div>

    <div class="separator"></div>

    <div class="sectionHome">
      <img class="imgL" src="assets/icons/balance.svg" alt="Logo 3">
      <a href="explore.php" class="btnIndex2">Accédez au comparateur</a>
      <div class="content" id="L">
      <h2>Comparez deux pays</h2>
      <p>Découvrez les différences entre les impacts environnementaux de deux pays avec notre outil de comparaison. Visualisez les données à travers des graphiques clairs et explorez les indicateurs clés pour prendre des décisions éclairées.</p>
      <p>Comparer les performances environnementales des nations vous aide à choisir des partenaires commerciaux, des destinations de voyage et des politiques favorables à la durabilité.</p>
      </div>  
    </div>

    <div class="separator"></div>

      <div class="sectionHome">
      <div class="content" id="R">
        <h2>Comprendre l'impact des pays sur notre planète</h2>
        <p>Accédez aux différents scores que nous proposons pour comparer les impacts des pays sur notre planète. Explorez nos évaluations économiques, écologiques et de découvertes pour obtenir une vision holistique des contributions de chaque nation à notre environnement mondial. Notre score global offre une synthèse complète, vous permettant de prendre des décisions informées et de contribuer à un avenir plus durable. Découvrez comment chaque pays se positionne.</p>
      </div>  
      <img  class="imgR"src="assets/icons/ScoreIndex.svg" alt="Logo 4">
    </div>

  </main>
</div>
</div>
</body>