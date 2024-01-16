<?php
require_once "config.php"
?>


<!-- Barre de navigation -->
<nav class="navbar" hx-boost="true" hx-target="#grille" hx-select="#grille" hx-swap="outerHTML">
    <div class="onglets">
    <a href="index.php">ECOTOURISME</a>
    <a href="index.php">Accueil</a>
    <a href="monde.php">Monde</a>
    <a href="pays.php">Pays</a>
    <a href="continent.php">Continent</a>
    <a href="comparateur.php">Comparateur</a>
    <!-- <a href="#news">Statistiques</a> -->
    <!-- <a href="#news">Calculateur</a> -->
    <div class="search-container" style="float:right">
    <!-- <form action="/action_page.php"> -->
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
                <a href="#news" style="float:right">Se connecter</a>
            HTML;
        }
        ?>

    </div>
</nav>