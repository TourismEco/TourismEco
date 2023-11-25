function graphBar(data, name1, name2) {

    g = new Bar("bar")
    g.initXAxis("year",data)
    g.initYAxis()

    s1 = g.addSerie(data, name1, "#52796F", "year", "value")
    s2 = g.addSerie(data, name2, "#83A88B", "year", "value2")
    
    legend = g.graph.children.push(
        am5.Legend.new(g.root, {
            centerX: am5.p50,
            x: am5.p50,
            fill:"#FFFFFF"
        })
    );

    legend.data.push(s1)
    legend.data.push(s2)

    g.graph.appear(1000, 100);

}  
