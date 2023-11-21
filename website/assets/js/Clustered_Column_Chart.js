var xAxisbar
var yAxisBar
var chartBar
var rootBar

function graphBar(data, name1, name2) {

  rootBar = am5.Root.new("bar");

    rootBar.setThemes([
      am5themes_Animated.new(rootBar)
    ]);

    chartBar = rootBar.container.children.push(am5xy.XYChart.new(rootBar, {
      panX: false,
      panY: false,
      wheelX: "panX",
      wheelY: "zoomX",
      layout: rootBar.verticalLayout,
    }));

    legend = chartBar.children.push(
      am5.Legend.new(rootBar, {
        centerX: am5.p50,
        x: am5.p50
      })
    );


    var xRenderer = am5xy.AxisRendererX.new(rootBar, {
      cellStartLocation: 0.1,
      cellEndLocation: 0.9
    })
    
    xAxis = chartBar.xAxes.push(am5xy.CategoryAxis.new(rootBar, {
      categoryField: "year",
      renderer: xRenderer,
      tooltip: am5.Tooltip.new(rootBar, {})
    }));
    
    xRenderer.grid.template.setAll({
      location: 1
    })
    
    xAxis.data.setAll(data);
    
    yAxis = chartBar.yAxes.push(am5xy.ValueAxis.new(rootBar, {
      renderer: am5xy.AxisRendererY.new(rootBar, {
        strokeOpacity: 0.1
      })
    }));

    makeSeries(name2,"value", data)
    makeSeries(name1,"value2",data)

    chartBar.appear(1000, 100);
}

  
  
  // Add series
  // https://www.amcharts.com/docs/v5/charts/xy-chartBar/series/
function makeSeries(name, fieldName, data) {

    var series = chartBar.series.push(am5xy.ColumnSeries.new(rootBar, {
      name: name,
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: fieldName,
      categoryXField: "year"     
    }));
  
    series.columns.template.setAll({
      tooltipText: "{name}, {categoryX}:{valueY}",
      width: am5.percent(90),
      tooltipY: 0,
      strokeOpacity: 0
    });
  
    series.data.setAll(data);
  
    // Make stuff animate on load
    // https://www.amcharts.com/docs/v5/concepts/animations/
    series.appear();
  
    series.bullets.push(function() {
      return am5.Bullet.new(rootBar, {
        locationY: 0,
        sprite: am5.Label.new(rootBar, {
          text: "{valueY}",
          fill: rootBar.interfaceColors.get("alternativeText"),
          centerY: 0,
          centerX: am5.p50,
          populateText: true
        })
      });
    });
  
    legend.data.push(series);
  }
  
   
