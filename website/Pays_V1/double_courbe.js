
function double_courbe(data) {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv2");

// Set themes
root.setThemes([
  am5themes_Animated.new(root)
]);

var gradient = am5.LinearGradient.new(root, {
  stops: [{
    color: am5.color(0x2F3E46)
  }]
});


// Create chart
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  wheelX: "panX",
  wheelY: "zoomX",
  pinchZoomX: true
}));

chart.get("colors").set("step", 3);

// Add cursor
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
  behavior: "none"
}));
cursor.lineY.set("visible", false);

// Create axes
var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0.2,
  categoryField: "year",
  renderer: am5xy.AxisRendererX.new(root, {}),
  tooltip: am5.Tooltip.new(root, {})
}));

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  renderer: am5xy.AxisRendererY.new(root, {
    pan: "zoom"
  }),
  min: 0, // Définissez la valeur minimale de l'axe Y
  max: 1000000, // Définissez la valeur maximale de l'axe Y
}));

// Add series 1
var series = chart.series.push(am5xy.LineSeries.new(root, {
  name: "Series 1",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "value",
  categoryXField: "year",
  tooltip: am5.Tooltip.new(root, {
    labelText: "{valueY}"
  })
}));

var xRenderer = xAxis.get("renderer");

xRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});
var yRenderer = yAxis.get("renderer");

yRenderer.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});
series.set("stroke", am5.color(0x02a07e));

series.bullets.push(function() {
  var graphics = am5.Circle.new(root, {
    radius: 4,
    interactive: true,
    cursorOverStyle: "ns-resize",
    stroke: series.get("stroke"),
    fill: am5.color(0x02a07e)
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


xAxis.data.setAll(data);
series.data.setAll(data);
// series2.data.setAll(data);

// Make stuff animate on load
series.appear(1000);
// series2.appear(1000);
chart.appear(1000, 100);


}; // end am5.ready()
