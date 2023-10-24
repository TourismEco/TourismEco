<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 550px;
  background-color: #2F3E46;
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
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
// start and end angle must be set both for chart and series
var chart = root.container.children.push(am5percent.PieChart.new(root, {
  startAngle: 180,
  endAngle: 360,
  layout: root.verticalLayout,
  innerRadius: am5.percent(50)
}));

// Create series
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
// start and end angle must be set both for chart and series
var series = chart.series.push(am5percent.PieSeries.new(root, {
  startAngle: 180,
  endAngle: 360,
  valueField: "value",
  categoryField: "category",
  alignLabels: false,
  
}));

var bgColor = root.interfaceColors.get("background");

series.slices.template.setAll({
  stroke: bgColor,
  
  templateField: "settings"
});

series.labels.template.setAll({
  text: "{category}"
});

series.states.create("hidden", {
  startAngle: 180,
  endAngle: 180
});

series.slices.template.setAll({
  cornerRadius: 5
});

series.ticks.template.setAll({
  forceHidden: true
});

// Set data
// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
series.data.setAll([
  { value: 7, category: "A",
  settings: { fill: am5.color(0x006700) }  },
  { value: 4, category: "B",
  settings: { fill: am5.color(0x446700) }  },
  { value: 5, category: "C",
    settings: { fill: am5.color(0xE49C15) }  },
  { value: 4, category: "C",
    settings: { fill: am5.color(0xBB5C00) }  },
 
  { value: 3, category: "E", 
    settings: { fill: am5.color(0xAA0000) }  }
]);

series.appear(1000, 100);


series.labels.template.set("visible", true );

series.labels.template.setAll({
  fill: am5.color(0xFBFBFD),
  fillOpacity: 0.7
});

series.ticks.template.set("visible", false);



}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>