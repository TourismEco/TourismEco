<?php

// Barre de navigation
echo '<div class="navbar">';
echo '<div class="onglets">';
echo ' <a href="#home">ECOTOURISME</a>';
echo '<a href="#news">Accueil</a>';
echo '<a href="#news">Comparateur</a>';
echo '<a href="#news">Statistiques</a>';

// echo '<a href="#news">Calculateur</a>';

echo '<div class="search-container" style="float:right">';
echo '<form action="/action_page.php">';
echo '<input type="text" placeholder="Rechercher.." name="search"></form>';

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

