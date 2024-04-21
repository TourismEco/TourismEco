<?php require_once "head.php"; ?>

<nav id="nav" class="navbar" hx-boost="true" hx-target="#zones" hx-select="#zones" hx-swap="outerHTML swap:0.5s show:window:top" hx-swap-oob="outerHTML">
    <a href="index.php" aria-label="Accueil"><img class="img-nav" src="assets/icons/ecomini.png" alt="Logo TourismEco"></a>
    <div class="nav-links">
        <a href="explorer.php" aria-label="MONDE">Explorer</a>
        <a href="pays.php" aria-label="Pays">Pays</a>
        <a href="comparateur.php" aria-label="Comparateur">Comparateur</a>
        <a href="continent.php" aria-label="Continent">Continent</a>
        <a href="analyse.php" aria-label="MONDE">Analyse</a>
        <a href="calculateur.php" aria-label="Calculateur">Calculateur</a>
    </div>
    <div id="log-in">
        <?php if (isset($_SESSION["user"])) {
            echo '<a href="profil.php" aria-label="Profil" id="n1">Profil</a>';
            echo '<a href="deconnexion.php" aria-label="Déconnexion" id="n2">Déconnexion</a>';
        } else {
            echo '<a href="inscription.php" aria-label="Inscription" id="n1">S\'inscrire</a>';
            echo '<a href="connexion.php" aria-label="Connexion" id="n2">Se connecter</a>';
        } ?>
    </div>

    <script>
        $("a").on("click",function() {
            $("#nav-bot").css("z-index","1")
            $("#nav-bot").css("transform","translateX(-3000px)")
        })
    </script>
</nav>
