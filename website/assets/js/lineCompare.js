var rootLine
var graph;
var xAxisLine;
var yAxisLine;
var serieLine1;
var serieLine2;

class LineGraph {
    
}

function newSeries(name, value) {
    return graph.series.push(am5xy.LineSeries.new(rootLine, {
        name: name,
        xAxis: xAxisLine,
        yAxis: yAxisLine,
        valueYField: value,
        categoryXField: "year",
        tooltip: am5.Tooltip.new(rootLine, {
            labelText: "{name} : {valueY}"
        })
    }));
}

function customLine(serie) {
    serie.bullets.push(function() {
        var graphics = am5.Circle.new(rootLine, {
            radius: 4,
            interactive: true,
            cursorOverStyle: "ns-resize",
            stroke: serie.get("stroke"),
            fill: am5.color(0xffffff)
        });

        return am5.Bullet.new(rootLine, {
            sprite: graphics
        });
    });
}

function createGraph(data, name1, name2) {
    rootLine = newRoot("chartdiv")
    fig = am5xy.XYChart.new(rootLine, {})
    
    graph = addToRoot(rootLine, fig, am5xy.XYCursor)

    graph.get("colors").set("step", 3);

    // Add cursor

    // Create axes
    xAxisLine = graph.xAxes.push(am5xy.CategoryAxis.new(rootLine, {
        maxDeviation: 0.2,
        categoryField: "year",
        renderer: newXRenderer(rootLine,am5xy.AxisRendererX),
        tooltip: am5.Tooltip.new(rootLine, {})
    }));

    yAxisLine = graph.yAxes.push(am5xy.ValueAxis.new(rootLine, {
        renderer:newYRenderer(rootLine,am5xy.AxisRendererY)
    }));

    serieLine1 = newSeries(name1,"value")
    serieLine2 = newSeries(name2,"value2")

    customLine(serieLine1)
    customLine(serieLine2)

    serieLine1.bullets.push(function() {
        var graphics = am5.Circle.new(rootLine, {
            radius: 4,
            interactive: true,
            cursorOverStyle: "ns-resize",
            stroke: serieLine1.get("stroke"),
            fill: am5.color(0xffffff)
        });
        return am5.Bullet.new(rootLine, {
            sprite: graphics
        });
        });

    console.log(serieLine1)

    graph.set("scrollbarX", am5.Scrollbar.new(rootLine, {
        orientation: "horizontal"
    }));

    xAxisLine.data.setAll(data);
    serieLine1.data.setAll(data);
    serieLine2.data.setAll(data);

    serieLine1.appear(1000);
    serieLine2.appear(1000);
    graph.appear(1000, 100);

}

function lineAjax(data) {
    xAxisLine.data.setAll(data);
    serieLine1.data.setAll(data);
    serieLine2.data.setAll(data);
}