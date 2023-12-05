<?php
    // Votre configuration PDO
    require '../functions.php';

    try {
        $conn = getDB();
        // Définissez le mode d'erreur PDO sur exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Erreur de connexion à la base de données: " . $e->getMessage();
    }

    $query = "
    SELECT ecologie.annee as annee,
    economie.pibParHab as pib,
    ecologie.elecRenew * 1000 as Enr,
    ecologie.co2 as co2, 
    tourisme.arriveesTotal as arrivees, 
    tourisme.departs as departs, 
    surete.gpi *10000 as gpi, 
    economie.cpi as cpi

    FROM ecologie, economie, tourisme, surete
    WHERE ecologie.id_pays = economie.id_pays
    AND economie.id_pays = tourisme.id_pays
    AND tourisme.id_pays = surete.id_pays
    AND surete.id_pays = 'FR'

    AND ecologie.annee = economie.annee
    AND economie.annee = tourisme.annee
    AND tourisme.annee = surete.annee  
    ORDER BY `ecologie`.`annee` DESC;
    ";

    $result = $conn->query($query);

    $data = array();
    while ($rs = $result->fetch(PDO::FETCH_ASSOC)) {
        if (!isset($rs["cpi"])){
            $rs["cpi"]=0;
        }
        $data[] = <<<END
            $rs[annee]:
                [{"var":"PIB","value":$rs[pib]},
                {"var":"% renew","value":$rs[Enr]},
                {"var":"CO2","value":$rs[co2]},
                {"var":"Arrivées","value":$rs[arrivees]},
                {"var":"Départs","value":$rs[departs]},
                {"var":"GPI","value":$rs[gpi]},
                {"var":"CPI","value":$rs[cpi]}]

        END;
    }

    $data = implode(",",$data)

    ?>

    <!-- Styles -->
    <style>
    #chartdiv {
    width: 40%;
    height: 90%;
    background-color: #2F3E46;
    }
    </style>

    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    <script src="https://www.mywebsite.com/amcharts5/index.js"></script>
    <script src="https://www.mywebsite.com/amcharts5/xy.js"></script>

    <!-- Chart code -->
    <script>
    
    g(<?=$data?>)
    </script>

    <!-- HTML -->
    <div id="chartdiv"></div>