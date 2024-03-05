function addJauge(id) {
    j = new Jauge(id)
    j.createXAxis()
    j.addClock()
    j.changeValue(70)
}