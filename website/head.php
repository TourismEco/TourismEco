<?php 
require_once "functions.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ecotourisme</title>
	<link rel="stylesheet" href="assets/css/UI3.css">

	<!-- Stack -->
	<script src="https://unpkg.com/htmx.org"></script>
	<script src="https://unpkg.com/jquery.min.js"></script>

	<!-- Base -->
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

	<!-- Map -->
	<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/region/world/europeLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/region/world/africaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/region/world/asiaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/region/world/oceaniaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/region/world/northAmericaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/region/world/southAmericaLow.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>

	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>

	<script src="scripts/graph/amTools.js"></script>

    <script src="scripts/map/map.js"></script>

	<script src="scripts/graph/lineCompare.js"></script>

	<script src="scripts/graph/spider.js"></script>
	
	<script src="scripts/graph/barCompare.js"></script>
	<script src="scripts/graph/barreLine.js"></script>
	<script src="scripts/graph/jauge.js"></script>

	<script src="scripts/graph/barre.js"></script>
	<script src="scripts/graph/linePays.js"></script>

	<script src="scripts/js/functions.js"></script>

	<link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>
<body>
<?php require_once 'navbar.php' ?>
