<style>
  #chartdiv {
    /*width: 100%;
    height: 100%;
    background-color: #f2f2f2;*/
  }
  </style>

<?php


require '../functions.php';

try {
    $conn = getDB();
    // Définissez le mode d'erreur PDO sur exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}


$query = "
SELECT annee,pib/1000000 as pib FROM economie Where id_pays ='FR';";

$result = $conn->query($query);

$report_data = array();
while ($rs = $result->fetch()) {
    $report_data[] = array(
        "year" => $rs['annee'], // Renommez "country" en "year"
        "value" => $rs['pib']
    );
}

// Fermez la connexion à la base de données
$conn = null;
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
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script src="https://www.mywebsite.com/amcharts5/index.js"></script>
<script src="https://www.mywebsite.com/amcharts5/xy.js"></script>

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
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  wheelX: "panX",
  wheelY: "zoomX",
  pinchZoomX: true,
  layout: root.verticalLayout,
  background: am5.Rectangle.new(root, {
    fillGradient: gradient
  })
}));


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
  behavior: "none"
}));
cursor.lineY.set("visible", false);


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0.2,
  categoryField: "year", // Utilisez "year" comme le champ de catégorie
  renderer: am5xy.AxisRendererX.new(root, {}),
  tooltip: am5.Tooltip.new(root, {})
}));


var xRenderer = xAxis.get("renderer");

xRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});
xRenderer.grid.template.set("forceHidden", true);

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: am5xy.AxisRendererY.new(root, {
    pan: "zoom"
  }),
  min: 0, // Définissez la valeur minimale de l'axe Y
  max: 3850000, // Définissez la valeur maximale de l'axe Y
}));

var yRenderer = yAxis.get("renderer");

yRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(am5xy.LineSeries.new(root, {
  name: "Series",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  categoryXField: "year", // Utilisez "year" comme le champ de catégorie
  tooltip: am5.Tooltip.new(root, {
    labelText: "{valueY}"
  }),
  stroke: am5.color("#BB5C00")
}));


series.bullets.push(function() {
  var graphics = am5.Circle.new(root, {
    radius: 4,
    interactive: true,
    cursorOverStyle: "ns-resize",
    stroke: series.get("stroke"),
    fill: am5.color(0xBB5C00)
  });

  return am5.Bullet.new(root, {
    sprite: graphics
  });
});

// Add scrollbar
// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
chart.set("scrollbarX", am5.Scrollbar.new(root, {
  orientation: "horizontal"
}));

chart.set("scrollbarY", am5.Scrollbar.new(root, { orientation: "vertical" }));


// Définir les données
var data = <?php echo json_encode($report_data); ?>;
xAxis.data.setAll(data);
series.data.setAll(data);

// Animer les éléments lors du chargement
series.appear(1000);
chart.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>
