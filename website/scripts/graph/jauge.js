function addJauge(id) {
    j = new Jauge20(id)
    j.createXAxis()
    j.addClock()
    j.changeValue(70)
}