function graphBar(data, name1, name2) {

    b = new Bar("bar")
    b.initXAxis("categ",data)
    b.initYAxis()
    b.addLegend()

    s1 = b.addSerie(data, name1, "#52796F", "categ", "Canada")
    s2 = b.addSerie(data, name2, "#83A88B", "categ", "USA")

    b.graph.appear(1000, 100);

}  
