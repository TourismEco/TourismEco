<nav class="navbar" hx-boost="true" hx-target="#zones" hx-select="#zones" hx-swap="outerHTML swap:0.5s">
    
    <div class="right-nav">
        <a href="monde.php">Monde</a>
        <a href="pays.php">Pays</a>
        <a href="continent.php">Continent</a>
        <a href="comparateur.php">Comparateur</a>
    </div>

    <div class="img-nav">
        <a href="index.php"><img src="assets/icons/eco.png"></a>
    </div>

    <div class="left-nav">
        <a href="calculateur.php">Calculateur</a>
        <a href="inscription.php" >S'inscrire</a>
        <a href="connexion.php">Se connecter</a>
    </div>

    <script>
        $("a").on("click",function() {
            $("#nav-bot").css("z-index","1")
            $("#nav-bot").css("transform","translateY(-70px)")
            // $("#nav-bot").removeClass("hide")
            // $("#nav-bot").addClass("shown")
            // $("#nav-bot").removeClass("hide")
        })
    </script>

</nav>