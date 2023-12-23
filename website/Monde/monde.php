<!DOCTYPE html>
<html lang= "fr">

<head>
  <meta charset="UTF-8">
  <link href="styles/style.css" rel="stylesheet" type="text/css" />
  <title> Ecotourisme </title>

  <?php
    require '../functions.php';
    $bdd = getDB();
  ?>

  <script src="https://cdn.amcharts.com/lib/5/hierarchy.js"></script>
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
  <script src="../assets/js/map.js"></script>

  <style>
    #map {
        width: 100%;
        height: 500px;
        background-color: #354F52;
        display:flex
    }
</style>
</head>

<body>
  <div id = 'map'> </div>


  <script>
      createMap()
  </script>



  
  <div class= bandeau> <!-- risque d'avoir un functions -->
    <h1> Statistiques mondiales </h1>
  </div>
  
  <div class="section1">
    <div class="graph-container" id="bar-chart-container">
      <?php require 'graphique/en_barres.php'; ?>
      <h2>Top 10 des pays les plus visités</h2>
    </div>
    
    <div class="graph-container" id="pie-chart-container">
      <?php require 'graphique/proportionScore.php'; ?>
      <h2>Proportion de chaque score écotourisme attribué</h2>
    </div>

  </div>


  <div class="section2">
    <h2>Comparaison rapide des continents</h2>
    <div id="treemap">
      <?php require 'graphique/treemap.php'; ?>
    </div>
  </div>

  <div class=footer>

  </div>

</body>
</html>