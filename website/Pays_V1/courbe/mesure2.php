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
SELECT tourisme.departs/10 as departs 
FROM `tourisme`
WHERE tourisme.departs IS NOT NULL
AND tourisme.departs > 100 
AND tourisme.departs < 1000
LIMIT 8;
";

try {
  $result = $conn->query($query);
  $rs = $result->fetch(PDO::FETCH_ASSOC);
  $score =  $rs['departs'] ;

} catch(PDOException $e) {
  echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}

// Fermez la connexion à la base de données
$conn = null;

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


// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
var chart = root.container.children.push(am5radar.RadarChart.new(root, {
  panX: false,
  panY: false,
  startAngle: 160,
  endAngle: 380
}));


// Create axis and its renderer
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
var axisRenderer = am5radar.AxisRendererCircular.new(root, {
  innerRadius: -40
});

axisRenderer.grid.template.setAll({
  stroke: root.interfaceColors.get("background"),
  visible: true,
  strokeOpacity: 0.8
});

var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0,
  min: 0,
  max: 100,
  strictMinMax: true,
  renderer: axisRenderer
}));


// Add clock hand
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
var axisDataItem = xAxis.makeDataItem({});

var clockHand = am5radar.ClockHand.new(root, {
  pinRadius: am5.percent(20),
  radius: am5.percent(100),
  bottomWidth: 40
})

var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
  sprite: clockHand
}

));

xAxis.createAxisRange(axisDataItem);

var label = chart.radarContainer.children.push(am5.Label.new(root, {
  fill: am5.color(0xffffff),
  centerX: am5.percent(50),
  textAlign: "center",
  centerY: am5.percent(50),
  fontSize: "3em"
}));

axisDataItem.set("value", <?php echo json_encode($score); ?>);

bullet.get("sprite").on("rotation", function () {
  var value = axisDataItem.get("value");
  var text = Math.round(axisDataItem.get("value")).toString();
  var fill = am5.color(0x000000);
  xAxis.axisRanges.each(function (axisRange) {
    if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
      fill = axisRange.get("axisFill").get("fill");
    }
  })

  label.set("text", Math.round(value).toString());

  clockHand.pin.animate({ key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic) })
  clockHand.hand.animate({ key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic) })


});



chart.bulletsContainer.set("mask", undefined);


// Create axis ranges bands
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
var bandsData = [{
  title: "",
  color: "#AA0000",
  lowScore: 0,
  highScore: 20
}, {
  title: "",
  color: "#BB5C00",
  lowScore: 20,
  highScore: 40
}, {
  title: "",
  color: "#E49C15",
  lowScore: 40,
  highScore: 60
}, {
  title: "",
  color: "#446700",
  lowScore: 60,
  highScore: 80
}, {
  title: "",
  color: "#006700",
  lowScore: 80,
  highScore: 100
}];

am5.array.each(bandsData, function (data) {
  var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));

  axisRange.setAll({
    value: data.lowScore,
    endValue: data.highScore
  });

  axisRange.get("axisFill").setAll({
    visible: true,
    fill: am5.color(data.color),
    fillOpacity: 0.8
  });

  axisRange.get("label").setAll({
    text: data.title,
    inside: true,
    radius: 15,
    fontSize: "0.9em",
    fill: root.interfaceColors.get("background")
  });
});

var xRenderer = xAxis.get("renderer");

xRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});

// Make stuff animate on load
chart.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>