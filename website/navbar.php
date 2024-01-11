<!-- Barre de navigation -->
    <nav class="navbar" hx-boost="true" hx-target="main">
        <div class="onglets">
        <a href=<?= SOURCE."Home.php"?>>ECOTOURISME</a>
        <a href="#news">Accueil</a>
        <a href="#news">Comparateur</a>
        <a href="#news">Statistiques</a>
    <!-- <a href="#news">Calculateur</a> -->
    <div class="search-container" style="float:right">
    <form action="/action_page.php">
    <!-- <input type="text" placeholder="Rechercher.." name="search"></form> -->

<?php
if (isset($_SESSION['client'])) {
    echo <<<HTML
        <a href="#news">Calculateur</a>
        <div id="client"><p>Bonjour $_SESSION[client][prenom] $_SESSION[client][nom]</p></div>
    HTML;
} else {
    echo <<<HTML
        <a href="#news" style="float:right">Sinscrire</a>
        <a href="#news"style="float:right">Se connecter</a>
    HTML;
}
?>

</div>
</nav>