<?php
session_start(); // Démarrer la session

// Détruire la session
session_destroy();

// Rediriger vers index.php
header("Location: ../Home/home.php");
?>
