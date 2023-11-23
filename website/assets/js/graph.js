var root2
var graph;
var xAxis;
var yAxis;
var serieBar1;
var serieBar2;

function newSeries(name, value) {
    return graph.series.push(am5xy.LineSeries.new(root2, {
        name: name,
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: value,
        categoryXField: "year",
        tooltip: am5.Tooltip.new(root2, {
            labelText: "{name} : {valueY}"
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

function createGraph(data, name1, name2) {
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

    serieBar1 = newSeries(name1,"value")
    serieBar2 = newSeries(name2,"value2")

    customLine(serieBar1)
    customLine(serieBar2)

    serieBar1.bullets.push(function() {
        var graphics = am5.Circle.new(root2, {
            radius: 4,
            interactive: true,
            cursorOverStyle: "ns-resize",
            stroke: serieBar1.get("stroke"),
            fill: am5.color(0xffffff)
        });
        return am5.Bullet.new(root2, {
            sprite: graphics
        });
        });

    console.log(serieBar1)

    graph.set("scrollbarX", am5.Scrollbar.new(root2, {
        orientation: "horizontal"
    }));

    xAxis.data.setAll(data);
    serieBar1.data.setAll(data);
    serieBar2.data.setAll(data);

    serieBar1.appear(1000);
    serieBar2.appear(1000);
    graph.appear(1000, 100);

}

function changeAjaxBar(data) {
    xAxis.data.setAll(data);
    serieBar1.data.setAll(data);
    serieBar2.data.setAll(data);
}