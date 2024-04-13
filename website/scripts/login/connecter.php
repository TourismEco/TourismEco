<?php

require('../../functions.php');

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo <<<HTML
        <div id="error" class="form-warning" hx-swap-oob="outerHTML">Formulaire incomplet.</div>
    HTML;
    exit;
}

if (!isset($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    echo "Token CSRF invalide.";
    exit;
}

$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

$connexion = getDB();

$stmt = $connexion->prepare("SELECT * FROM client WHERE nom = ?");
$stmt->bindParam(1, $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['mdp'])) {

    $_SESSION['user']['username'] = $user['nom'];
    $_SESSION['user']['country'] = $user['pays'];
    $_SESSION['user']['city'] = $user['ville'];
    echo <<<HTML
        <div id="htmxing" hx-swap-oob="true">
            <div hx-get="profil.php" hx-target="#zones" hx-select="#zones" hx-swap="outerHTML swap:0.5s show:window:top" hx-push-url="true" hx-trigger="load"></div>
        </div>
        <a href="profil.php" aria-label="Profil" id="n1" hx-swap-oob="outerHTML">Profil</a>
        <a href="deconnexion.php" aria-label="Déconnexion" id="n2" hx-swap-oob="outerHTML">Déconnexion</a>
    HTML;
} else {
    echo <<<HTML
        <div id="error" class="form-warning" hx-swap-oob="outerHTML">Nom d'utilisateur ou mot de passe incorrect.</div>
    HTML;
}
