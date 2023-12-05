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
SELECT tourisme.annee as annee, tourisme.departs as departs, economie.pib/10000000 as pib
FROM tourisme, economie
WHERE tourisme.departs IS NOT NULL
AND economie.pib IS NOT NULL
AND tourisme.id_pays = 'FR'
AND economie.id_pays = 'FR'

AND tourisme.annee = economie.annee;
";

try {
  $result = $conn->query($query);

  $report_data = array();
  while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
      $report_data[] = array(
          "year" => $rs['annee'],
          "income" => $rs['pib'],
          "expenses" => $rs['departs']
      );
  }
} catch(PDOException $e) {
  echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}

// Fermez la connexion à la base de données
$conn = null;
print_r( $report_data);
?>


<!-- Styles -->
<style>
    #chartdiv {
      width: 40%;
      height: 60%;
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
root.setThemes([am5themes_Animated.new(root)]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(
  am5xy.XYChart.new(root, {
    panX: false,
    panY: false,
    wheelX: "panX",
    wheelY: "zoomX",
    layout: root.verticalLayout
  })
);

// Add scrollbar
// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
chart.set(
  "scrollbarX",
  am5.Scrollbar.new(root, {
    orientation: "horizontal"
  })
);

var data = <?php echo json_encode($report_data); ?>;

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, {});
var xAxis = chart.xAxes.push(
  am5xy.CategoryAxis.new(root, {
    categoryField: "year",
    renderer: xRenderer,
  })
);

var xRenderer = xAxis.get("renderer");

xRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});


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

var paretoAxisRenderer = am5xy.AxisRendererY.new(root, { opposite: true });

var paretoAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: paretoAxisRenderer,
  min: 0,
  max: 950000,
  strictMinMax: true
}));

paretoAxisRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/

var series1 = chart.series.push(
  am5xy.ColumnSeries.new(root, {
    name: "PIB",
    xAxis: xAxis,
    yAxis: paretoAxis,
    valueYField: "income",
    categoryXField: "year",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      labelText: "{name} in {categoryX}: {valueY} {info}"
    })
  })
);

series1.columns.template.setAll({
  tooltipY: am5.percent(10),
  templateField: "columnSettings"
});

series1.data.setAll(data);

var series2 = chart.series.push(
  am5xy.LineSeries.new(root, {
    name: "Tourime",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "expenses",
    categoryXField: "year",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      labelText: "{name} in {categoryX}: {valueY} {info}"
    })
  })
);

series2.strokes.template.setAll({
  strokeWidth: 3,
  templateField: "strokeSettings"
});


series2.data.setAll(data);

series2.bullets.push(function() {
  return am5.Bullet.new(root, {
    sprite: am5.Circle.new(root, {
      strokeWidth: 3,
      stroke: series2.get("stroke"),
      radius: 5,
      fill: root.interfaceColors.get("background")
    })
  });
});

chart.set("cursor", am5xy.XYCursor.new(root, {}));

// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(
  am5.Legend.new(root, {
    centerX: am5.p50,
    x: am5.p50
  })
);

legend.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});

legend.data.setAll(chart.series.values);

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);
series1.appear();


}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>