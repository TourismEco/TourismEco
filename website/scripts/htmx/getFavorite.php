<?php
require("../../config.php");

$cur = getDB();
$id_client = $_SESSION['client']['username'];
$id_pays = $_SESSION["pays"][0];
$query = "SELECT * FROM favoris WHERE id_pays = :id_pays AND id_client = :id_client";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":id_client", $id_client, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();

if ($ligne) {
    $query = "DELETE FROM favoris WHERE id_pays = :id_pays AND id_client = :id_client";
    $sth = $cur->prepare($query);
    $sth->bindParam(":id_pays", $ligne['id_pays'], PDO::PARAM_STR);
    $sth->bindParam(":id_client", $ligne['id_client'], PDO::PARAM_STR);
    $sth->execute();

    echo <<<HTML
        <img id="favorite" src="assets/img/heart.png" hx-get="scripts/htmx/getFavorite.php" hx-trigger="click" hx-swap="outerHTML" hx-vals="js:{id_pays:'{$ligne['id_pays']}'}">
    HTML;
} else {
    // Assurez-vous que id_pays est défini avant d'insérer
    if (isset($id_pays) && !empty($id_pays)) {
        $query = "INSERT INTO `favoris` (`id_client`, `id_pays`) VALUES (:id_client, :id_pays);";
        $sth = $cur->prepare($query);
        $sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
        $sth->bindParam(":id_client", $id_client, PDO::PARAM_STR);
        $sth->execute();

        echo <<<HTML
            <img id="favorite" src="assets/img/heart_full.png" hx-get="scripts/htmx/getFavorite.php" hx-trigger="click" hx-swap="outerHTML" hx-vals="js:{id_pays:'$id_pays'}">
        HTML;
    } else {
        echo "Erreur : id_pays n'est pas défini.";
    }
}
?>
