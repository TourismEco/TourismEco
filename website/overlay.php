<?php require_once 'config.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ecotourisme</title>
	<link rel="stylesheet" href="assets/css/styles.css">

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
	<script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>

	<!-- Our own scripts -->
	<script src="scripts/map/map.js"></script>

</head>

<body>
<?php require_once 'navbar.php'?>

<!-- TO-DO: trouver un moyen de le faire disparaître pour les pages concernées -->
<template class="map-container">
	<div id="map"></div>
</template>

<!-- Main content of the page -->
<main></main>

 <?php require_once 'footer.html'?>
</body> 

</html>