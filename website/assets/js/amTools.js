class Graphique {
    constructor(id, figObj, cursorObj) {
        console.log(id)
        this.root = am5.Root.new(id)
        this.graph = this.root.container.children.push(figObj.new(this.root, {}))
        this.xAxis = null
        this.yAxis = null

        var cursor = this.graph.set("cursor", cursorObj.new(this.root, {}));
        cursor.lineY.set("visible", false);

        this.root.setThemes([
            am5themes_Animated.new(this.root)
        ]);
    }

    newXRenderer(obj) {
        var xRenderer = obj.new(this.root, {
            cellStartLocation: 0.1,
            cellEndLocation: 0.9
        });
        xRenderer.labels.template.setAll({
            fill:"#FFFFFF",
        });
        return xRenderer
    }

    newYRenderer(obj) {
        var yRenderer = obj.new(this.root, {});
        yRenderer.labels.template.setAll({
            fill:"#FFFFFF"
        });
        return yRenderer
    }

    initXAxis(rendererObj, field, data) {
        var base = this
        this.xAxis = this.graph.xAxes.push(am5xy.CategoryAxis.new(base.root, {
            categoryField: field,
            renderer: base.newXRenderer(rendererObj),
            tooltip: am5.Tooltip.new(base.root, {})
        }));
        this.xAxis.data.setAll(data)
    }

    initYAxis(rendererObj) {
        var base = this
        this.yAxis = this.graph.yAxes.push(am5xy.ValueAxis.new(base.root, {
            renderer: base.newYRenderer(rendererObj)
        }));
    }

    addSerie(data, name, color, xField, yField, obj) {
        var base = this

        var serie = this.graph.series.push(obj.new(base.root, {
            name: name,
            xAxis: base.xAxis,
            yAxis: base.yAxis,
            valueYField: yField,
            categoryXField: xField,
            tooltip: am5.Tooltip.new(base.root, {
                labelText: "{name} : {valueY}"
            }),
            stroke:color,
            fill:color,
        }));        
        
        serie.data.setAll(data)
        serie.appear(1000)

        return serie
    }

    addBullets(serie, color) {
        var base = this

        serie.bullets.push(function() {
            return am5.Bullet.new(base.root, {
                sprite: am5.Circle.new(base.root, {
                    radius: 4,
                    stroke: color,
                    fill: color
                })
            });
        });
    }
}

class Spider extends Graphique {
    constructor(id) {
        super(id, am5radar.RadarChart, am5radar.RadarCursor)
    }
    initXAxis(field, data) {
        super.initXAxis(am5radar.AxisRendererCircular, field, data)
    }
    initYAxis() {
        super.initYAxis(am5radar.AxisRendererRadial)
    }
    addSerie(data, name, color, xField, yField) {
        var serie = super.addSerie(data, name, color, xField, yField, am5radar.RadarLineSeries)
        super.addBullets(serie, color)
        return serie
    }

}

class Line extends Graphique {
    constructor(id) {
        super(id, am5xy.XYChart, am5xy.XYCursor)
    }
    initXAxis(field, data) {
        super.initXAxis(am5xy.AxisRendererX, field, data)
    }
    initYAxis() {
        super.initYAxis(am5xy.AxisRendererY)
    }
    addSerie(data, name, color, xField, yField) {
        var serie = super.addSerie(data, name, color, xField, yField, am5xy.LineSeries)
        super.addBullets(serie, color)
        return serie
    }
}

class Bar extends Graphique {
    constructor(id) {
        super(id, am5xy.XYChart, am5xy.XYCursor)
    }
    initXAxis(field, data) {
        super.initXAxis(am5xy.AxisRendererX, field, data)
    }
    initYAxis() {
        super.initYAxis(am5xy.AxisRendererY)
    }
    addSerie(data, name, color, xField, yField) {
        return super.addSerie(data, name, color, xField, yField, am5xy.ColumnSeries)
    }
}
