<?php

// Connexion à la base de données
$bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

// Récupération de l'identifiant de l'utilisateur connecté
$user_id = get_current_user_id();

// définition du chemin de l'URL pour les form
$url = plugins_url('/LBU_recorddata1dayperf');

// Récupération des champs de sportlist à true pour l'utilisateur connecté
$query2 = $bdd->prepare('SELECT * FROM sportlist WHERE user_id = :user_id');
$query2->execute(array(
    'user_id' => $user_id
));
$sportlist = $query2->fetch();

// Tableau pour stocker les champs à true dans la table sportlist
$sportlist_true = [];

// Vérifier si les champs sont à true et les stocker dans le tableau
if ($sportlist['velo_elliptique'] == true) {
    $sportlist_true[] = 'velo_elliptique';
}
if ($sportlist['pompes'] == true) {
    $sportlist_true[] = 'pompes';
}
if ($sportlist['abdominaux'] == true) {
    $sportlist_true[] = 'abdominaux';
}
if ($sportlist['squat'] == true) {
    $sportlist_true[] = 'squat';
}
if ($sportlist['gainage'] == true) {
    $sportlist_true[] = 'gainage';
}
if ($sportlist['traction'] == true) {
    $sportlist_true[] = 'traction';
}
if ($sportlist['musculation'] == true) {
    $sportlist_true[] = 'musculation';
}
if ($sportlist['boxe'] == true) {
    $sportlist_true[] = 'boxe';
}
if ($sportlist['marche'] == true) {
    $sportlist_true[] = 'marche';
}




echo '<button type="submit" name="submit" id="recordoneday-btn" >Enregistrer</button>';

// Affichage des divs pour les champs à true dans la table sportlist
foreach ($sportlist_true as $id) {
    switch ($id) {
        case 'velo_elliptique':
            echo '<div class="category" id="velo_elliptique">';
            echo '<li><span class="category-title">Vélo elliptique</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo ' <input type="hidden" name="category" value="velo_elliptique">';
            echo ' <input type="hidden" name="record_id" value="">';
            echo ' <label for="minutes_elliptique">Minutes</label>';
            echo ' <input type="number" name="minutes_elliptique" id="minutes_elliptique" value="">';
            echo '    <label for="distance_elliptique">Distance</label>';
            echo '<input type="number" name="distance_elliptique" id="distance_elliptique" step="0.01" value="">';
            echo '    <label for="calories_elliptique">Calories</label>';
            echo '    <input type="number" name="calories_elliptique" id="calories_elliptique" value="">';
            echo '</form>';
            echo '</div>';
            break;

        case 'pompes':
            echo '<div class="category" id="pompes">';
            echo '<li><span class="category-title">Pompes</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="pompes">';
            echo '    <input type="hidden" name="record_id" value="">';
            echo '    <label for="series_pompes">Séries</label>';
            echo '    <input type="number" name="series_pompes" id="series_pompes" value="">';
            echo '    <label for="quantite_pompes">Quantité</label>';
            echo '    <input type="number" name="quantite_pompes" id="quantite_pompes" value="">';
            echo '</form>';
            echo '</div>';
            break;


        case 'abdominaux':
            echo '<div class="category" id="abdominaux">';
            echo '<li><span class="category-title">Abdominaux</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="abdominaux">';
            echo '    <input type="hidden" name="record_id" value="">';
            echo '    <label for="series_abdominaux">Séries</label>';
            echo '    <input type="number" name="series_abdominaux" id="series_abdominaux" value="">';
            echo '    <label for="quantite_abdominaux">Quantité</label>';
            echo '    <input type="number" name="quantite_abdominaux" id="quantite_abdominaux" value="">';
            echo '</form>';
            echo '</div>';
            break;



        case 'squat':
            echo '<div class="category" id="squat">';
            echo '<li><span class="category-title">Squats</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="squat">';
            echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
            echo '    <label for="series_squats">Séries</label>';
            echo '    <input type="number" name="series_squats" id="series_squats" value="' . $record['series_squats'] . '">';
            echo '    <label for="quantite_squats">Quantité</label>';
            echo '    <input type="number" name="quantite_squats" id="quantite_squats" value="' . $record['quantite_squats'] . '">';
            echo '</form>';
            echo '</div>';
            break;


        case 'gainage':
            echo '<div class="category" id="gainage">';
            echo '<li><span class="category-title">Gainage</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="gainage">';
            echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
            echo '    <label for="duree_gainage">Durée</label>';
            echo '    <input type="time" name="duree_gainage" id="duree_gainage" value="' . $record['duree_gainage'] . '">';
            echo '</form>';
            echo '</div>';
            break;



        case 'traction':
            echo '<div class="category" id="traction">';
            echo '<li><span class="category-title">Tractions</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="traction">';
            echo '    <input type="hidden" name="record_id" value="">';
            echo '    <label for="series_tractions">Séries</label>';
            echo '    <input type="number" name="series_tractions" id="series_tractions" value="">';
            echo '    <label for="quantite_tractions">Quantité</label>';
            echo '    <input type="number" name="quantite_tractions" id="quantite_tractions" value="">';
            echo '</form>';
            echo '</div>';
            break;


        case 'musculation':
            echo '<div class="category" id="musculation">';
            echo '<li><span class="category-title">Renforcement musculaire</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="musculation">';
            echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
            echo '    <label for="duree_renforcement_musculaire">Durée</label>';
            echo '    <input type="time" name="duree_renforcement_musculaire" id="duree_renforcement_musculaire" value="' . $record['duree_renforcement_musculaire'] . '">';
            echo '</form>';
            echo '</div>';
            break;



        case 'boxe':
            echo '<div class="category" id="boxe">';
            echo '<li><span class="category-title">Boxe</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="boxe">';
            echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
            echo '    <label for="nombre_rounds_boxe">Nombre de rounds</label>';
            echo '    <input type="number" name="nombre_rounds_boxe" id="nombre_rounds_boxe" value="' . $record['nombre_rounds_boxe'] . '">';
            echo '    <label for="duree_boxe">Durée</label>';
            echo '    <input type="time" name="duree_boxe" id="duree_boxe" value="' . $record['duree_boxe'] . '">';
            echo '</form>';
            echo '</div>';
            break;


        case 'marche':
            echo '<div class="category" id="marche">';
            echo '<li><span class="category-title">Marche</span></li>';
            echo '<form class="oneday-form" method="POST" action="' . $url . '/traitementoneday.php">';
            echo '    <input type="hidden" name="category" value="marche">';
            echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
            echo '    <label for="nombre_pas">Nombre de pas</label>';
            echo '    <input type="number" name="nombre_pas" id="nombre_pas" value="' . $record['nombre_pas'] . '">';
            echo '    <label for="distance_marche">Distance (km)</label>';
            echo '    <input type="number" step="0.01" name="distance_marche" id="distance_marche" value="' . $record['distance_marche'] . '">';
            echo '</form>';
            echo '</div>';
            break;
    }
}