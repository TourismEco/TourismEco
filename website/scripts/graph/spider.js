function g(data) {

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
    wheelX: "panX",
    wheelY: "zoomX"
    }));

    // Add cursor
    // https://www.amcharts.com/docs/v5/charts/radar-chart/#Cursor
    var cursor = chart.set("cursor", am5radar.RadarCursor.new(root, {
    behavior: "zoomX"
    }));

    cursor.lineY.set("visible", false);


    // Create axes and their renderers
    // https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_axes
    var xRenderer = am5radar.AxisRendererCircular.new(root, {});
    xRenderer.labels.template.setAll({
    radius: 10,
    fill:"#FFFFFF"
    });

    var yRenderer = am5radar.AxisRendererRadial.new(root, {});
    yRenderer.labels.template.setAll({
    fill:"#FFFFFF"
    });

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
    maxDeviation: 0,
    categoryField: "var",
    renderer: xRenderer,
    tooltip: am5.Tooltip.new(root, {})
    }));

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
    renderer: yRenderer
    }));


    // Create series
    // https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_series
    var series = chart.series.push(am5radar.RadarLineSeries.new(root, {
    name: "Series",
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "value",
    categoryXField: "var",
    tooltip:am5.Tooltip.new(root, {
        labelText:"{valueY}",
    }),
    stroke:"#52796F",
    fill:"#52796F",
    }));


    series.strokes.template.setAll({
        strokeWidth: 2,
    });

    series.bullets.push(function () {
    return am5.Bullet.new(root, {
        sprite: am5.Circle.new(root, {
        radius: 5,
        fill: "#52796F"
        })
    });
    });


    // Set data
    // https://www.amcharts.com/docs/v5/charts/radar-chart/#Setting_data
    console.log(data)
    
    series.data.setAll(data["2020"]);
    xAxis.data.setAll(data["2020"]);

    // Create controls
    var container = chart.children.push(am5.Container.new(root, {
    y: am5.p100,
    centerX: am5.p50,
    centerY: am5.p100,
    x: am5.p50,
    width: am5.percent(90),
    layout: root.horizontalLayout,
    paddingBottom: 10
    }));

    var firstYear = 2008
    var lastYear = 2020

    var slider = container.children.push(am5.Slider.new(root, {
    //width: am5.percent(80),
    orientation: "horizontal",
    start: 1,
    centerY: am5.p50,
    
    }));

    slider.get("background").setAll(
        {fill:"#52796F"}
    )

    slider.startGrip.get("icon").set("forceHidden", true);
    slider.startGrip.set("label", am5.Label.new(root, {
    text: firstYear + "",
    paddingTop: 0,
    paddingRight: 0,
    paddingBottom: 0,
    paddingLeft: 0,
    fill:"#000000",

    }));

    updateData(2020);
    var yearTemp = 2020

    slider.events.on("rangechanged", function () {
    var year = firstYear + Math.round(slider.get("start", 0) * (lastYear - firstYear));
    slider.startGrip.get("label").set("text", year + "");
    if (year != yearTemp) {
        updateData(year);
        console.log(year, yearTemp)
        yearTemp = year
    }
    });

    var stepDuration = 100;

    function updateData(year) {
    if (data[year]) {

        am5.array.each(series.dataItems, function (dataItem) {
        var value = data[year]["value"];

            series.data.setAll(data[year]);
        });

    }
    }


    // Animate chart and series in
    // https://www.amcharts.com/docs/v5/concepts/animations/#Initial_animation
    series.appear(1000);
    chart.appear(1000, 100);
}
