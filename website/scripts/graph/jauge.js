function addJauge(id) {
    j = new Jauge(id)
    j.initXAxis()
    j.addClock()
    j.changeValue(70)
}