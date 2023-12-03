function graphBar(data, name1, name2) {

    g = new Bar("bar")
    g.initXAxis("categ",data)
    g.initYAxis()
    g.addLegend()

    s1 = g.addSerie(data, name1, "#52796F", "categ", "Canada")
    s2 = g.addSerie(data, name2, "#83A88B", "categ", "USA")

    g.graph.appear(1000, 100);

}  
