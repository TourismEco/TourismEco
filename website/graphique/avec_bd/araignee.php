<?php
// Votre connexion MySQLi
$servername = "127.0.0.1";
$username = "root";
$password = "root";
$database = "Ecotourisme";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$query = "
SELECT departs, id_pays
FROM `departs`
where departs is not null
and departs>100 
and departs<1000
limit 8;";

$result = $conn->query($query);

$report_data = array();
while ($rs = $result->fetch_assoc()) {
    $report_data[] = array(
        "value" => $rs['departs'], // Renommez "country" en "year"
        "country" => $rs['id_pays']
    );
}

// Fermez la connexion à la base de données
$conn->close();
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
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<!-- Chart code -->
<script>
am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

var gradient = am5.LinearGradient.new(root, {
  stops: [{
    color: am5.color(0x2F3E46)
  }, {
    color: am5.color(0x2F3E46)
  }, {
    color: am5.color(0x2F3E46)
  }]
});

// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
var chart = root.container.children.push(am5radar.RadarChart.new(root, {
  panX: false,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX",
  background: am5.Rectangle.new(root, {
    fillGradient: gradient
  })
}));

// Add cursor
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Cursor
var cursor = chart.set("cursor", am5radar.RadarCursor.new(root, {
  behavior: "zoomX"
}));



// Create axes and their renderers
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_axes
var xRenderer = am5radar.AxisRendererCircular.new(root, {});
xRenderer.labels.template.setAll({
  radius: 10
});

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0,
  categoryField: "country",
  renderer: xRenderer,
  
}));

var xRenderer = xAxis.get("renderer");

xRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});


var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: am5radar.AxisRendererRadial.new(root, {}),
  min: 0, // Définissez la valeur minimale de l'axe Y
  max: 1000, // Définissez la valeur maximale de l'axe Y
}));

var yRenderer = yAxis.get("renderer");

yRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});

// Create series
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_series
var series = chart.series.push(am5radar.RadarLineSeries.new(root, {
  name: "Series",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  categoryXField: "country",
  stroke: am5.color("#84A98C") // Remplacez la couleur par celle de votre choix
}));


var tooltip = am5.Tooltip.new(root, {
  getLabelFillFromSprite: true,
  labelText: "[bold]{name}[/]\n{categoryX}: {valueY}",
  pointerOrientation: "up"
});

tooltip.get("background").setAll({
  fill: am5.color(0x84A98C),
  fillOpacity: 0.1
});

series.set("tooltip", tooltip);
series.strokes.template.setAll({
  strokeWidth: 2
});

series.bullets.push(function () {
  return am5.Bullet.new(root, {
    sprite: am5.Circle.new(root, {
      radius: 5,
      fill: am5.color("#84A98C") // Remplacez la couleur par celle de votre choix
    })
  });
});


// Set data
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Setting_data

var data = <?php echo json_encode($report_data); ?>;
series.data.setAll(data);
xAxis.data.setAll(data);


// Animate chart and series in
// https://www.amcharts.com/docs/v5/concepts/animations/#Initial_animation
series.appear(1000);
chart.appear(1000, 100);



}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>