<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Votre connexion MySQLi
$conn = new mysqli('127.0.0.1', 'root', '', 'ecotourisme', 3306);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$query = "
SELECT pays.nom AS country, SUM(tourisme.arriveesTotal) AS value
FROM tourisme, pays 
WHERE tourisme.id_pays = pays.id 
GROUP BY pays.nom 
ORDER BY value DESC
LIMIT 10;
";

$result = $conn->query($query);

// Récupérez les données de la requête SQL
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();
?>

<!-- Styles -->
<style>
#barchart {
  width: 80%;
  height: 500px;
}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<!-- Chart code -->
<script>
am5.ready(function() {

  // Create root element
  var root = am5.Root.new("barchart");

  // Set themes
  root.setThemes([
    am5themes_Animated.new(root)
  ]);

  // Create chart
  var chart = root.container.children.push(am5xy.XYChart.new(root, {
    panX: true,
    panY: true,
    wheelX: "panX",
    wheelY: "zoomX",
    pinchZoomX: true,
    paddingLeft: 0,
    paddingRight: 1
  }));

  // Add cursor
  var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
  cursor.lineY.set("visible", false);

  // Create axes
  var xRenderer = am5xy.AxisRendererX.new(root, {
    minGridDistance: 30,
    minorGridEnabled: true
  });

  xRenderer.labels.template.setAll({
    rotation: -90,
    centerY: am5.p50,
    centerX: am5.p100,
    paddingRight: 15
  });

  xRenderer.grid.template.setAll({
    location: 1
  });

  var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
    maxDeviation: 0.3,
    categoryField: "country", // Utiliser le champ "country"
    renderer: xRenderer,
    tooltip: am5.Tooltip.new(root, {})
  }));

  var yRenderer = am5xy.AxisRendererY.new(root, {
    strokeOpacity: 0.1
  });

  var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    maxDeviation: 0.3,
    renderer: yRenderer
  }));

  // Create series
  var series = chart.series.push(am5xy.ColumnSeries.new(root, {
    name: "Series 1",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "value", // Utiliser le champ "value"
    sequencedInterpolation: true,
    categoryXField: "country",
    tooltip: am5.Tooltip.new(root, {
      labelText: "{valueY}"
    })
  }));

  series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5, strokeOpacity: 0 });
  series.columns.template.adapters.add("fill", function(fill, target) {
    return chart.get("colors").getIndex(series.columns.indexOf(target));
  });

  series.columns.template.adapters.add("stroke", function(stroke, target) {
    return chart.get("colors").getIndex(series.columns.indexOf(target));
  });

  // Set data dynamically
  xAxis.data.setAll(<?php echo json_encode($data); ?>);
  series.data.setAll(<?php echo json_encode($data); ?>);

  // Make stuff animate on load
  series.appear(1000);
  chart.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="barchart"></div>