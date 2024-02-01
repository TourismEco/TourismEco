<?php


// if (!isset($_SESSION['client'])) {
//     header("Location: ../Home/home.php");
//     exit();   
// }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style_profil.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> <!-- Ajout de jQuery -->
    <title>Ma Page Web</title>
    
</head>
<body>
    <?php require_once '../navbar.php'?>


    <div class="color1">
    <?php
        if (isset($_SESSION['client']['username'])) {

            $prenom = htmlspecialchars($_SESSION['client']['username']);
            echo "<h1> Bonjour $prenom <br></h1>";
            }else{
                echo "<h1>BONJOUR</h1>";
            }
        ?>
        <div class="grille">
            <div class="container_fav">
                <h2>VOS FAVORIS</h2>
                <?php
                    require("../functions.php");

                    $cur = getDB();
                    $queryPays = "SELECT pays.score, pays.nom, pays.id AS id 
                    FROM favoris 
                    JOIN pays ON favoris.id_pays = pays.id 
                    WHERE favoris.id_client = ?";
                    $stmt = $cur->prepare($queryPays);
                    $stmt->bindParam(1, $prenom);
                    $stmt->execute();
                               
                    echo <<<HTML
                    <div id= fav>
                    HTML;

                    while ($rsPays = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $letter = getLetter($rsPays["score"]);
                        echo addCardCountry($rsPays["id"],$rsPays["nom"],$letter,"Pays");
                    }
                    echo <<<HTML
                    </div>
                    HTML;    
                                           
                ?> 
            </div>
            <div class="container_last_search">
                <h2>VOS DERNIERE<br>RECHERCHE</h2>
                <?php

                    $in  = str_repeat('?,', count($_SESSION["historique"]) - 1) . '?';
                    $queryPaysHistorique = "SELECT score, nom, id 
                    FROM pays 
                    WHERE id IN ($in)";
                    $stmtHistorique = $cur->prepare($queryPaysHistorique);
                    $stmtHistorique->execute($_SESSION["historique"]);
                               
                    echo <<<HTML
                    <div id= fav>
                    HTML;

                    while ($rsPaysHistorique = $stmtHistorique->fetch(PDO::FETCH_ASSOC)) {
                        $letter = getLetter($rsPaysHistorique["score"]);
                        echo addCardCountry($rsPaysHistorique["id"],$rsPaysHistorique["nom"],$letter,"Pays");
                    }
                    echo <<<HTML
                    </div>
                    HTML;    
                                           
                ?> 
            </div>   
        </div>
        <div class="color2">
            <h2>MODIFIER VOTRE PROFIL</h2>
            <form action="modif.php"  method="post" onsubmit="return validateForm()" autocomplete="off">
                <label for = "country" >Pays actuelle<br></label>
                <input type="text" name="country" id="country" placeholder=<?php echo $_SESSION['client']['country'] ?> >
                <div id="countryOptions" class="country-options"></div> 
                <div id="errorCountry" class="error"></div>
                <br>
                <label for="cityInput">Ville actuelle<br></label>
                <input type="text" name="cityInput" id="cityInput" placeholder=<?php echo $_SESSION['client']['city'] ?>>
                <div id="cityOptions" class="options-container"></div>
                    <div id="errorCity" class="error"></div>
                <br>
                <button type="submit" class="btn">Modifier</button>
            </form>
        </div>
        <div>
            <a class="deco" href="deconnexion.php">SE DECONNECTER</a>
        </div>
        
    </div>
    
    <?php require_once '../footer.html'?>
    
    <script>
        $(document).ready(function () {

            function validateForm() {
                var countryInput = $("#country");

                // Vérifier que le pays est sélectionné
                if (countryInput.val() === "") {
                    $("#errorCountry").html("Veuillez sélectionner un pays.");
                    return false;
                } else {
                    $("#errorCountry").html("");
                }

                // Vérifier que la ville est sélectionnée ou renseignée
                var cityInput = $("#city");
                var selectedCity = cityInput.val();
                var selectedCitySelect = $("#citySelect").val();

                if (selectedCity === "" && selectedCitySelect === null) {
                    $("#errorCity").html("Veuillez sélectionner ou saisir une ville.");
                    return false;
                } else {
                    $("#errorCity").html("");
                }


                // Si toutes les validations passent, retourner true
                return true;
            }

            // AJAX pour charger dynamiquement les options des pays
            $("#country").on("input", function () {
                var inputText = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "../formulaires/get_countries.php",
                    data: { inputText: inputText },
                    success: function (response) {
                        $("#countryOptions").html(response);
                    }
                });
            });

            // Gestionnaire de clic sur les options de la liste des pays
            $("#countryOptions").on("click", "option", function () {
                var selectedCountry = $(this).text();
                $("#country").val(selectedCountry);
                $("#countryOptions").html("");

                // Charger les options de la ville en fonction du pays sélectionné
                $.ajax({
                    type: "POST",
                    url: "../formulaires/get_cities.php",
                    data: { selectedCountry: selectedCountry },
                    success: function (response) {
                        $("#cityOptions").html(response);
                        $("#citySelect").show();
                        $("#city").hide();
                    }
                });
            });
            
            ///Ajoute ville en liste dynamique 
            $("#cityInput").on("input", function () {
                var selectedCountry = $("#country").val();
                var selectedCity = $(this).val();

                // Charger les options de la ville en fonction du pays sélectionné et de l'input actuelle
                $.ajax({
                    type: "POST",
                    url: "../formulaires/get_cities.php",
                    data: { selectedCountry: selectedCountry, inputText: selectedCity },
                    success: function (response) {
                        $("#cityOptions").html(response);
                        $("#citySelect").show();
                        $("#city").hide();
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });




           

            // Gestionnaire de clic sur les options de la liste des villes
            $("#cityOptions").on("click", "option", function () {
                var selectedCity = $(this).text();
                $("#cityInput").val(selectedCity);
                $("#selectedCity").val(selectedCity); // Stockez la ville sélectionnée dans le champ caché
                $("#cityOptions").html(""); // Masquer les options après la sélection
                
            });

        
            // Gestionnaire d'événements dblclick pour le champ d'entrée du pays
            $("#country").on("dblclick", function () {
                $("#countryOptions").html("");
            });

            // Gestionnaire d'événements dblclick pour le champ d'entrée de la ville
            $("#cityInput").on("dblclick", function () {
                $("#cityOptions").html("");
            });

            //  // Restaurer les valeurs des champs après le chargement de la page
            //  if (sessionStorage.getItem('selectedCountry')) {
            //             $('#country').val(sessionStorage.getItem('selectedCountry'));
            //             $.ajax({
            //                 type: "POST",
            //                 url: "../formulaires/get_cities.php",
            //                 data: { selectedCountry: $('#country').val() },
            //                 success: function (response) {
            //                     $("#cityOptions").html(response);
            //                     $("#citySelect").show();
            //                     $("#city").hide();
            //                 }
            //             });
            //         }

            // if (sessionStorage.getItem('selectedCity')) {
            //     $('#cityInput').val(sessionStorage.getItem('selectedCity'));
            // }
            $('form').submit(function () {
                // Stocker les valeurs des champs dans sessionStorage avant la soumission du formulaire
                sessionStorage.setItem('selectedCountry', $('#country').val());
                sessionStorage.setItem('selectedCity', $('#cityInput').val());

                return validateForm();
            });
        })
    </script>
    <!-- Ajoute ici d'autres scripts si nécessaire -->

</body>
</html>
