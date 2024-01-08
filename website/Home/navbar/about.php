<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .section, .section2 {
            padding: 20px;
            height: auto;
            overflow: hidden;
            color: white;
        }
    
        .dark-grey {
            background-color: #183A37;
        }
        .grey {
            background-color: #2F3E46;
        }
        .light-green{
            background-color: #52796F;
        }
        img {
            max-width: 35%; 
            margin: 0px 30%;
        }

h1 {
        margin-top: 50px;
        text-align: center;
        }

.container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
padding-bottom: 50px;
}

.member {
    width: 200px;
}

.member img {
    border-radius: 50%;
    width: 50%;
    height: 50%;
    object-fit: cover;
   
        }

.member p {
    margin: 10px;
    padding: 0 10px;
    text-align: center;
        }

    </style>
</head>

<?php require_once 'navbar.html';?>

<body>

    <div class="section2 light-green">
        <img src="eco.png" alt="Logo 3">
        <h1>Notre projet </h1>
        <p> Votre plateforme tout-en-un pour des voyages économiques et respectueux de l'environnement. Accès simplifié à des informations sur l'économie et l'écologie des destinations, calcul de l'empreinte carbone, et coûts de voyage, pour des choix responsables.</p>
    </div>

    <div class="section2 dark-grey">
        <h1>L'équipe</h1>
        
        <div class="container">
            <div class="member">
                <img src="avatar/line.png" >
                <p>Line Bransolle</p>
                <p>L3 MIASHS</p>
            </div>
            <div class="member">
                <img src="avatar/hugo.png" >
                <p>Hugo Gonçalves </p>
                <p>L3 MIASHS</p>
            </div>
            <div class="member">
            <img src="avatar/cassy.png" >
                <p>Cassandra Sénécaille</p>
                <p>L3 MIASHS</p>
            </div>
            <div class="member">
            <img src="avatar/remy.png" >
                <p>Rémy Gilibert</p>
                <p>L3 MIASHS</p>
            </div>
            <div class="member">
            <img src="avatar/aya.png" >
                <p>Aya Mohamedatni</p>
                <p>L3 MIASHS</p>
            </div>
            <div class="member">
                <img src="avatar/lucas.png" >
                <p>Lucas Triozon</p>
                <p>L3 MIASHS</p>
            </div>
        </div>
        </div>

    <div class="section light-green">
        <h1>Remerciements</h1>
        <p>Cher visiteurs, 
Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Curabitur a metus pellentesque massa lacinia scelerisque et nec purus. 
Proin mattis elementum euismod. Curabitur et felis felis. 
Donec vel nulla malesuada, tempor nisi in, faucibus nulla. 
Cras at ipsum tempor, rutrum sapien ut, auctor sapien. 
Duis vestibulum dolor sit amet malesuada laoreet. 
Sed lobortis tellus ullamcorper quam faucibus, ut commodo lorem pellentesque. 
In hac habitasse platea dictumst. Nullam convallis elit et tellus pharetra, vel aliquam metus pharetra. 
Duis fringilla suscipit enim, et maximus nunc scelerisque ac. 
Suspendisse at nisi bibendum purus mollis varius eget id lorem. Curabitur nec luctus magna. 
Aenean.Duis vestibulum dolor sit amet malesuada laoreet. 
Sed lobortis tellus ullamcorper quam faucibus, ut commodo lorem pellentesque. In hac habitasse platea dictumst.
Nullam convallis elit et tellus pharetra. 
</p>
    </div>
   
</body>
</html>



