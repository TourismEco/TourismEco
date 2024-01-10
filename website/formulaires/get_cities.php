<?php
require('../functions.php');

if (isset($_POST['selectedCountry'])) {
    $selectedCountry = $_POST['selectedCountry'];
    $connexion = getDB();

    // Assurez-vous que $inputText est défini
    $inputText = isset($_POST['inputText']) ? $_POST['inputText'] : '';

    if ($selectedCountry === 'Tous les pays') {
        // Afficher toutes les villes dont le nom commence par la première lettre de l'inputText
        $stmt = $connexion->prepare("SELECT nom FROM villes WHERE nom LIKE ? ORDER BY nom");
        $stmt->execute(["{$inputText}%"]);
    } else {
        // Afficher les villes pour le pays sélectionné dont le nom commence par la première lettre de l'inputText
        $stmt = $connexion->prepare("SELECT nom FROM villes WHERE id_pays = (SELECT id FROM pays WHERE nom = ?) AND nom LIKE ? ORDER BY nom");
        $stmt->execute([$selectedCountry, "{$inputText}%"]);
    }

    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($options)) {
        foreach ($options as $option) {
            echo "<option value=\"{$option['nom']}\">{$option['nom']}</option>";
        }
    } else {
        echo "<option>Aucune ville trouvée</option>";
    }
}
?>
