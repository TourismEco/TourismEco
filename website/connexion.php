<?php require_once 'head.php' ?>
<?php
// Générer un nouveau token CSRF si la variable de session n'existe pas
if (isset($_SESSION['user'])) {
    require_once("Location: profil.php");
    exit();
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<body>

    <div class="flex">
        <div class="zone-totale column" id="zones">
            <h1 class="titre">Connexion</h1>
            <img class="logo" src="assets/icons/profil.png" alt="LogoProfil">

            <div class="connexion-container">
                <form id="loginForm" hx-post="scripts/login/connecter.php">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Saisissez votre nom d'utilisateur" required autocomplete="off">
                    <div id="errorUsername" class="error"></div>

                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Saisissez votre mot de passe" required autocomplete="off">
                    <div id="errorPassword" class="error"></div>

                    <!-- Ajouter un champ pour le token CSRF -->
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <input type="submit" value="Se connecter" class="submit">
                    <div id="errorMessages" class="error"></div>
                </form>
            </div>
        </div>

        <div class="zone mask"></div>

        <div id="nav-bot" hx-swap-oob="outerHTML"></div>

        <script id="scripting" hx-swap-oob="outerHTML"></script>
        <script id="orders" hx-swap-oob="outerHTML"></script>
        <script id="behave" hx-swap-oob="outerHTML"></script>
        <div id="htmxing" hx-swap-oob="outerHTML"></div>
    </div>

</body>

</html>