<?php
    session_start();
    session_destroy();
?>

<div id="zones">

    <div id="htmxing" hx-swap-oob="true">
        <div hx-get="index.php" hx-target="#zones" hx-select="#zones" hx-swap="outerHTML swap:0.5s show:window:top" hx-push-url="true" hx-trigger="load"></div>
    </div>
    <a href="inscription.php" aria-label="Inscription" id="n1" hx-swap-oob="true">S'inscrire</a>
    <a href="connexion.php" aria-label="Connexion" id="n2" hx-swap-oob="true">Se connecter</a>

</div>
