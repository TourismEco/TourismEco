var root2
var graph;
var xAxis;
var yAxis;

function newSeries(name, value) {
    return graph.series.push(am5xy.LineSeries.new(root2, {
        name: name,
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: value,
        categoryXField: "year",
        tooltip: am5.Tooltip.new(root2, {
            labelText:"{name}"+":"+"{valueY}"
        })
    }));
}

function customLine(serie) {
    serie.bullets.push(function() {
        var graphics = am5.Circle.new(root2, {
            radius: 4,
            interactive: true,
            cursorOverStyle: "ns-resize",
            stroke: serie.get("stroke"),
            fill: am5.color(0xffffff)
        });

        return am5.Bullet.new(root2, {
            sprite: graphics
        });
    });
}

function createGraph(data,name1,name2) {
    root2 = am5.Root.new("chartdiv");
    // Set themes
    root2.setThemes([
        am5themes_Animated.new(root2)
    ]);

    // Create chart
    graph = root2.container.children.push(am5xy.XYChart.new(root2, {
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true
    }));

    graph.get("colors").set("step", 3);

    // Add cursor
    var cursor = graph.set("cursor", am5xy.XYCursor.new(root2, {
        behavior: "none"
    }));
    cursor.lineY.set("visible", false);

    // Create axes
    xAxis = graph.xAxes.push(am5xy.CategoryAxis.new(root2, {
        maxDeviation: 0.2,
        categoryField: "year",
        renderer: am5xy.AxisRendererX.new(root2, {}),
        tooltip: am5.Tooltip.new(root2, {})
    }));

    yAxis = graph.yAxes.push(am5xy.ValueAxis.new(root2, {
        renderer: am5xy.AxisRendererY.new(root2, {
            pan: "zoom"
        }),
    }));

    // Add series 1
    var series = newSeries(name1,"value")
    var series2 = newSeries(name2,"value2")

    customLine(series)
    customLine(series2)

    series.bullets.push(function() {
        var graphics = am5.Circle.new(root2, {
            radius: 4,
            interactive: true,
            cursorOverStyle: "ns-resize",
            stroke: series.get("stroke"),
            fill: am5.color(0xffffff)
        });
        return am5.Bullet.new(root2, {
            sprite: graphics
        });
        });

    console.log(series)

    // Add scrollbar
    // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
    graph.set("scrollbarX", am5.Scrollbar.new(root2, {
        orientation: "horizontal"
    }));

    xAxis.data.setAll(data);
    series.data.setAll(data);
    series2.data.setAll(data);

    // Make stuff animate on load
    series.appear(1000);
    series2.appear(1000);
    graph.appear(1000, 100);

}