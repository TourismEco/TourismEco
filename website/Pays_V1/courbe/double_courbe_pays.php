<?php
// Votre configuration PDO
require '../functions.php';

try {
    $conn = getDB();
    // Définissez le mode d'erreur PDO sur exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage();
}

$query = "
SELECT annee, co2, ges, elecRenew*10000 as elecRenew
FROM ecologie
WHERE id_pays ='FR';";

try {
  $result = $conn->query($query);

  $report_data = array();
  while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
    $report_data[] = array(
      "year" => (string)$rs['annee'],
      "value" => $rs['co2'],
      "value2" => $rs['ges'],
      "value3" => $rs['elecRenew'],
      );
  }
} catch(PDOException $e) {
  echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}

// Fermez la connexion à la base de données
$conn = null;
//print_r( $report_data);
?>

<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
  background-color: #2F3E46;
}
</style>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
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


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: true,
  panY: true,
  wheelX: "panX",
  wheelY: "zoomX",
  pinchZoomX:true
}));


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
  behavior: "none"
}));

cursor.lineY.set("visible", false);


var data = <?php echo json_encode($report_data); ?>;


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: "year",
  renderer: am5xy.AxisRendererX.new(root, {}),
  tooltip: am5.Tooltip.new(root, {})
}));

var xRenderer = xAxis.get("renderer");

xRenderer.labels.template.setAll({
  fill: am5.color(0xCAD2C5),
  fillOpacity: 0.7
});
xRenderer.grid.template.set("forceHidden", true);


xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(
  am5xy.ValueAxis.new(root, {
    min: 0,
    extraMax: 0.1,
    renderer: am5xy.AxisRendererY.new(root, {
      strokeOpacity: 0.1
    })
  })
);
var yRenderer = yAxis.get("renderer");

yRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});

// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/

function createSeries(name, field) {
  var series = chart.series.push(am5xy.LineSeries.new(root, {
    name: name,
    xAxis: xAxis,
    yAxis: yAxis,
    stacked:true,
    valueYField: field,
    categoryXField: "year",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      labelText:"[bold]{name}:[/]\n{valueY}"
    }),
    stroke: am5.color("#FBFBFD")
  }));


  series.fills.template.setAll({
    fillOpacity: 0.06,
    visible: true
  });

  series.data.setAll(data);
  series.appear(1000);
}

createSeries("co2", "value");
createSeries("ges", "value2");
createSeries("energie new", "value3");

// Add scrollbar
// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
chart.set("scrollbarX", am5.Scrollbar.new(root, {
  orientation: "horizontal"
}));

chart.set("scrollbarY", am5.Scrollbar.new(root, {
  orientation: "vertical"
}));

// Create axis ranges
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/axis-ranges/
var rangeDataItem = xAxis.makeDataItem({
  category: "2019",
  endCategory: "2021"
});

var range = xAxis.createAxisRange(rangeDataItem);

rangeDataItem.get("grid").setAll({
  stroke: am5.color(0x00ff33),
  strokeOpacity: 0.5,
  strokeDasharray: [3]
});

rangeDataItem.get("axisFill").setAll({
  fill: am5.color(0x84A98C),
  fillOpacity: 0.1,
  visible:true
});

rangeDataItem.get("label").setAll({
  inside: true,
  text: "COVID",
  rotation: 90,
  centerX: am5.p100,
  centerY: am5.p100,
  location: 0,
  paddingBottom: 10,
  paddingRight: 15
});


var rangeDataItem2 = xAxis.makeDataItem({
  category: "2008"
});

var range2 = xAxis.createAxisRange(rangeDataItem2);

rangeDataItem2.get("grid").setAll({
  stroke: am5.color(0x00ff33),
  strokeOpacity: 1,
  strokeDasharray: [3]
});

rangeDataItem2.get("axisFill").setAll({
  fill: am5.color(0x84A98C),
  fillOpacity: 0.1,
  visible:true
});

rangeDataItem2.get("label").setAll({
  inside: true,
  text: "Crise économique",
  rotation: 90,
  centerX: am5.p100,
  centerY: am5.p100,
  location: 0,
  paddingBottom: 10,
  paddingRight: 15
});

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>