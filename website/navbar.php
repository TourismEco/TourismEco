<?php
// Barre de navigation
echo <<<HTML
    <div class="navbar">
        <div class="onglets">
        <a href="#home">ECOTOURISME</a>
        <a href="#news">Accueil</a>
        <a href="#news">Comparateur</a>
        <a href="#news">Statistiques</a>
HTML;

echo <<<HTML
<!-- <a href="#news">Calculateur</a> -->
<div class="search-container" style="float:right">
<form action="/action_page.php">
<!-- <input type="text" placeholder="Rechercher.." name="search"></form> -->
HTML;

if (isset($_SESSION['client'])) {
    echo '<a href="#news">Calculateur</a>';

    echo '<div id="client"><p>Bonjour ' . $_SESSION['client']['prenom'] . ' ' . $_SESSION['client']['nom'] . '</p></div>';
      

} else {
    
    echo '<a href="#news" style="float:right">Sinscrire</a>';
    echo '<a href="#news"style="float:right">Se connecter</a>';
}
echo '</div>';
echo '</nav>';
?>

