<!DOCTYPE html>
<html lang= "fr">

<head>
  <meta charset="UTF-8">
  <link href="styles/style.css" rel="stylesheet" type="text/css" />
  <title> Ecotourisme </title>

  <?php
    require 'functions.php';
    $bdd = getDB();
  ?>

  <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/hierarchy.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

</head>

<body>
  <div class = map> 

  </div>
  
  <div class= bandeau> <!-- risque d'avoir un functions -->
    <h1> Statistiques mondiales </h1>
  </div>
  
  <div class="section1">
    <div class="graph-container" id="bar-chart-container">
      <?php require 'graphiques/en_barres.php'; ?>
      <h2>Top 10 des pays les plus visités</h2>
    </div>
    
    <div class="graph-container" id="pie-chart-container">
      <?php require 'graphiques/proportionScore.php'; ?>
      <h2>Proportion de chaque score écotourisme attribué</h2>
    </div>

  </div>


  <div class="section2">
    <h2>Comparaison rapide des continents</h2>
    <div id="treemap">
      <?php require 'graphiques/treemap.php'; ?>
    </div>
  </div>

  <div class=footer>

  </div>

</body>
</html>