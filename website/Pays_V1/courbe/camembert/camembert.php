<?php
// Votre connexion MySQLi
$conn = new mysqli('127.0.0.1', 'root', '', 'Ecotourisme', 3306);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$query = "
SELECT id_pays as nom , SUM(arriveesTotal) as total
FROM arrivees
GROUP BY id_pays  
ORDER BY `total` DESC
LIMIT 10;


";

$result = $conn->query($query);

$chartData = array();
while ($rs = $result->fetch_assoc()) {
    $chartData[] = array(
        'country' => $rs['nom'],
        'sales' => $rs['total']
    );
}

// Convertir les données en JSON
$chartDataJson = json_encode($chartData);
?>


<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {

// Create root element
var root = am5.Root.new("chartdiv");

// Set themes
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create chart
var chart = root.container.children.push(am5percent.PieChart.new(root, {
  radius: am5.percent(90),
  innerRadius: am5.percent(50),
  layout: root.horizontalLayout
}));

// Create series
var series = chart.series.push(am5percent.PieSeries.new(root, {
  name: "Series",
  valueField: "sales",
  categoryField: "country"
}));

// Fetch data from your MySQL database using PHP
var chartData = <?= $chartDataJson ?>; // Assuming you have the data in PHP variable $chartData

// Set data from the database
series.data.setAll(chartData);

// Disabling labels and ticks
series.labels.template.set("visible", false);
series.ticks.template.set("visible", false);

// Adding gradients
series.slices.template.set("strokeOpacity", 0);
series.slices.template.set("fillGradient", am5.RadialGradient.new(root, {
  stops: [{
    brighten: -0.8
  }, {
    brighten: -0.8
  }, {
    brighten: -0.5
  }, {
    brighten: 0
  }, {
    brighten: -0.5
  }]
}));

// Create legend
var legend = chart.children.push(am5.Legend.new(root, {
  centerY: am5.percent(50),
  y: am5.percent(50),
  layout: root.verticalLayout
}));
// set value labels align to right
legend.valueLabels.template.setAll({ textAlign: "right" })
// set width and max width of labels
legend.labels.template.setAll({ 
  maxWidth: 140,
  width: 140,
  oversizedBehavior: "wrap"
});

legend.data.setAll(series.dataItems);

// Play initial series animation
series.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>
