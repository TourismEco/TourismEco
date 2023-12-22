<DOCTYPE html>
  <head>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/hierarchy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
  </head>

  <?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Votre connexion MySQLi
$conn = new mysqli('127.0.0.1', 'root', '', 'ecotourisme', 3306);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$query = "
SELECT 
    continents.nom as continent, 
    pays.nom AS pays, 
    SUM(tourisme.arriveesTotal) as arrivees_par_continent 
FROM 
    tourisme, continents, pays 
WHERE 
    tourisme.id_pays = pays.id AND 
    pays.id_continent = continents.id 
GROUP BY 
    continents.nom
ORDER BY 
    continents.nom, arrivees_par_continent DESC;
";

$result = $conn->query($query);

$chart_data = array();

while ($rs = $result->fetch_assoc()) {
    $continent = $rs['continent'];
    $arrivees = intval($rs['arrivees_par_continent']);
    $pays = $rs['pays'];

    if (!isset($chart_data[$continent])) {
        $chart_data[$continent] = array(
            "value" => 0,
            "children" => array(),
            "top_countries_details" => array(),
        );
    }
    
    $chart_data[$continent]["children"][] = array(
        "name" => $pays,
        "value" => $arrivees,
    );

    $chart_data[$continent]["top_countries_details"][] = array(
      "name" => $pays,
      "arrivees" => $arrivees,
  );

  $chart_data[$continent]["value"] += $arrivees * 1000;
}

// Tableau simple 
$final_data = array();
foreach ($chart_data as $continent => $data) {
    $final_data[] = array(
        "name" => $continent,
        "value" => $data["value"],
        "children" => $data["children"],
        "top_countries_details" => $data["top_countries_details"],
    );
}

// Fermez la connexion à la base de données
$conn->close();
?>


<!-- Styles -->
<style>
#chartdiv {
  width: 100%;
  height: 500px;
}
</style>


<!-- Chart code -->
<script>
am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create wrapper container
var container = root.container.children.push(
  am5.Container.new(root, {
    width: am5.percent(100),
    height: am5.percent(100),
    layout: root.verticalLayout
  })
);

// Create series
// https://www.amcharts.com/docs/v5/charts/hierarchy/#Adding
var series = container.children.push(
  am5hierarchy.Treemap.new(root, {
    singleBranchOnly: false,
    downDepth: 1,
    upDepth: -1,
    initialDepth: 2,
    valueField: "value",
    categoryField: "name",
    childDataField: "children",
    nodePaddingOuter: 0,
    nodePaddingInner: 0
  })
);

series.rectangles.template.setAll({
  strokeWidth: 2
});

// Generate and set data
// https://www.amcharts.com/docs/v5/charts/hierarchy/#Setting_data
var data = {
    name: "Root",
    children: <?php echo json_encode($final_data); ?>
};

series.data.setAll([data]);
series.set("selectedDataItem", series.dataItems[0]);

// Make stuff animate on load
series.appear(1000, 100);

}); // end am5.ready()
</script>

<!-- HTML -->
<div id="chartdiv"></div>

</html>