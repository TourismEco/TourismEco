<nav class="navbar" hx-boost="true" hx-target="#zones" hx-select="#zones" hx-swap="outerHTML swap:0.5s">
    
    <div class="right-nav">
        <a href="monde.php" aria-label="MONDE">Monde</a>
        <a href="pays.php" aria-label="Pays">Pays</a>
        <a href="continent.php" aria-label="Continent">Continent</a>
        <a href="comparateur.php" aria-label="Comparateur">Comparateur</a>
    </div>

    <div class="img-nav">
        <a href="index.php" aria-label="Accueil"><img src="assets/icons/eco.png" alt="Logo TourismEco"></a>
    </div>

    <div class="left-nav">
        <a href="calculateur.php" aria-label="Calculateur">Calculateur</a>
        <a href="inscription.php" aria-label="Inscription">S'inscrire</a>
        <a href="connexion.php" aria-label="Connexion">Se connecter</a>
    </div>

    <script>
        $("a").on("click",function() {
            $("#nav-bot").css("z-index","1")
            $("#nav-bot").css("transform","translateY(-70px)")
        })
    </script>

</nav>