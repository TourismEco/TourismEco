class Graphique {
    constructor(id, figObj, cursorObj) {
        this.root = am5.Root.new(id)
        this.graph = this.root.container.children.push(figObj.new(this.root, {}))
        this.xAxis = null
        this.yAxis = null
        this.legend = null
        this.series = []

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

    addSerie(data, name, color, xField, yField, obj, labelText) {
        var base = this

        var serie = this.graph.series.push(obj.new(base.root, {
            name: name,
            xAxis: base.xAxis,
            yAxis: base.yAxis,
            valueYField: yField,
            categoryXField: xField,
            tooltip: am5.Tooltip.new(base.root, {
                labelText: labelText
            }),
            stroke:color,
            fill:color,
        }));        
        
        serie.data.setAll(data)

        if (this.legend != null) {
            this.legend.data.push(serie)
        }
        var s = new Serie(data,serie) 
        this.series.push(s)

        return s
    }

    addBullets(serie, color) {
        var base = this

        serie.serie.bullets.push(function() {
            return am5.Bullet.new(base.root, {
                sprite: am5.Circle.new(base.root, {
                    radius: 4,
                    stroke: color,
                    fill: color
                })
            });
        });
    }

    addLegend() {
        this.legend = this.graph.children.push(
            am5.Legend.new(this.root, {
                centerX: am5.p50,
                x: am5.p50,
                fill:"#FFFFFF"
            })
        );
    }
}

class Serie {
    constructor(data,serie) {
        this.data = data
        this.serie = serie
        this.comp = null
    }
    addComp(data){
        this.comp = data
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
        var base = this
        this.yAxis = this.graph.yAxes.push(am5xy.ValueAxis.new(base.root, {
            min:0,
            max:100,
            renderer: base.newYRenderer(am5radar.AxisRendererRadial)
        }));
    }

    addSerie(data, dataComp, name, color, xField, yField) {
        var serie = super.addSerie(data, name, color, xField, yField, am5radar.RadarLineSeries, "{name} : {valueY}")
        serie.addComp(dataComp)
        super.addBullets(serie, color)
        return serie
    }

}

class Line extends Graphique {
    constructor(id) {
        super(id, am5xy.XYChart, am5xy.XYCursor)
        this.type = "co2"
    }
    initXAxis(field, data) {
        super.initXAxis(am5xy.AxisRendererX, field, data)
    }
    initYAxis() {
        super.initYAxis(am5xy.AxisRendererY)
    }
    addSerie(data, name, color, xField, yField) {
        var serie = super.addSerie(data, name, color, xField, yField, am5xy.LineSeries, "{name} : {valueY}")
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
        var base = this
        this.yAxis = this.graph.yAxes.push(am5xy.ValueAxis.new(base.root, {
            renderer: base.newYRenderer(am5xy.AxisRendererY),
            numberFormat: "#'%'",
        }));
    }
    addSerie(data, name, color, xField, yField) {
        return super.addSerie(data, name, color, xField, yField, am5xy.ColumnSeries, "{name} : {valueY}%")
    }
}
