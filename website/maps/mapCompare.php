<!-- Styles -->
<?php
require("../functions.php");
$cur = getDB();

$query = "SELECT * FROM pays ORDER BY nom ASC";
$sth = $cur -> prepare($query);
$sth -> execute();

if (isset($_GET["pays1"])) {
    $pays1 = $_GET["pays1"];
} else {
    $pays1 = "FR";
}

if (isset($_GET["pays2"])) {
    $pays2 = $_GET["pays2"];
} else {
    $pays2 = "JP";
}

echo <<<HTML
    <select name="pays1" id="pays1" onchange="changeComp1()">  
    HTML;

while ($rs = $sth->fetch()) {
    if ($rs["id"] == $pays1) {
        echo <<<HTML
        <option value=$rs[id] selected>$rs[nom]</option>
        HTML;
    } else {
        echo <<<HTML
        <option value=$rs[id]>$rs[nom]</option>
        HTML;
    }
    
}

echo <<<HTML
    </select>  
    HTML;

$sth -> execute();

echo <<<HTML
    <select name="pays2" id="pays2" onchange="changeComp2()">  
    HTML;

    while ($rs = $sth->fetch()) {
        if ($rs["id"] == $pays2) {
            echo <<<HTML
            <option value=$rs[id] selected>$rs[nom]</option>
            HTML;
        } else {
            echo <<<HTML
            <option value=$rs[id]>$rs[nom]</option>
            HTML;
        }
        
    }

echo <<<HTML
    </select>  
    HTML;
?>



<!-- Styles -->
<style>
    #map {
        width: 100%;
        height: 95%;
        background-color: #354F52;
    }
</style>
    
    <!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/lang/FR.js"></script>
    
<script src="../assets/js/map.js"></script>

<div id="map"></div>

<script>
    createMap(fun=compare,args=["FR","CA"])
</script>