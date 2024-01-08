<?php
require('../functions.php');

if (isset($_POST['inputText'])) {
    $inputText = $_POST['inputText'];
    $connexion = getDB();

    if ($inputText === 'Tous les pays') {
        $stmt = $connexion->prepare("SELECT nom, id FROM pays ORDER BY nom");
    } else {
        $stmt = $connexion->prepare("SELECT nom, id FROM pays WHERE nom LIKE ?");
        $stmt->execute(["$inputText%"]);
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($options)) {
        foreach ($options as $option) {
            echo "<option value=\"{$option['nom']}\">{$option['nom']}</option>";
        }
    } else {
        echo "<option value=\"\">Aucun résultat</option>";
    }
}
?>
