<?php

/*
Plugin Name: 0_LBU_plug_modifydata7daysperf
Description: Affiche, permet de modifier les valeurs des performances des 7 derniers enregistrements des activités de l'utilisateur.
Version: 1.0
Author: Le Bon Univers
*/

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_for_modify()
{
    // Connexion à la base de données
    $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

    // Récupération de l'identifiant de l'utilisateur connecté
    $user_id = get_current_user_id();

    // définition du chemin de l'URL pour les form
    $url = plugins_url('/LBU_perfreadandrecord');


    // Récupération des 7 derniers enregistrements
    $query = $bdd->prepare('SELECT * FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 7');
    $query->execute(array(
        'user_id' => $user_id
    ));
    $last_records = $query->fetchAll();

    // Récupération des champs de sportlist à true pour l'utilisateur connecté
    $query2 = $bdd->prepare('SELECT * FROM sportlist WHERE user_id = :user_id');
    $query2->execute(array(
        'user_id' => $user_id
    ));
    $sportlist = $query2->fetch();

    // Configuration locale pour les dates en français
    setlocale(LC_TIME, 'fr_FR.utf8');

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

    // Affichage des 7 derniers enregistrements
    foreach ($last_records as $record) {
        // Formatage de la date
        $date = strftime('%A %e %B', strtotime($record['day']));

        // Affichage du record avec le style personnalisé
        echo '<div class="record">';
        echo '<h3>' . ucfirst($date) . '</h3>';
        echo '<p class="record-item"><span class="label">Poids :  ' . $record['poids_kg'] . ' kg</span></p>';
        echo '<p class="record-item"><span class="label">Pourcentage de graisse : ' . $record['pourcentage_gras'] . ' %</span></p>';
        echo '<p class="record-item"><span class="label">Nombre IMC : ' . $record['nombre_imc'] . '</span></p>';
        echo '<ul>';



        // Affichage des divs pour les champs à true dans la table sportlist
        echo '<div class="container">';
        foreach ($sportlist_true as $id) {
            switch ($id) {
                case 'velo_elliptique':
                    echo '<div class="category" id="velo_elliptique">';
                    echo '<li><span class="category-title">Vélo elliptique</span></li>';
                    echo '<ul>';
                    echo '<li><span>Minutes</span> <span data-field="minutes_elliptique">' . $record['minutes_elliptique'] . '</span></li>';
                    echo '<li><span>Distance</span> <span data-field="distance_elliptique">' . $record['distance_elliptique'] . ' km</span></li>';
                    echo '<li><span>Calories</span> <span data-field="calories_elliptique">' . $record['calories_elliptique'] . ' kcal</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="velo_elliptique">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=elliptique">';
                    echo '    <input type="hidden" name="category" value="velo_elliptique">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="minutes_elliptique">Minutes</label>';
                    echo '    <input type="number" name="minutes_elliptique" id="minutes_elliptique" value="' . $record['minutes_elliptique'] . '">';
                    echo '    <label for="distance_elliptique">Distance</label>';
                    echo '<input type="number" name="distance_elliptique" id="distance_elliptique" step="0.01" value="' . $record['distance_elliptique'] . '">';
                    echo '    <label for="calories_elliptique">Calories</label>';
                    echo '    <input type="number" name="calories_elliptique" id="calories_elliptique" value="' . $record['calories_elliptique'] . '">';
                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'pompes':
                    echo '<div class="category" id="pompes">';
                    echo '<li><span class="category-title">Pompes</span></li>';
                    echo '<ul>';
                    echo '<li><span>Séries</span> <span data-field="series_pompes">' . $record['series_pompes'] . '</span></li>';
                    echo '<li><span>Quantité</span> <span data-field="quantite_pompes">' . $record['quantite_pompes'] . '</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="pompes">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=pompes">';
                    echo '    <input type="hidden" name="category" value="pompes">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="series_pompes">Séries</label>';
                    echo '    <input type="number" name="series_pompes" id="series_pompes" value="' . $record['series_pompes'] . '">';
                    echo '    <label for="quantite_pompes">Quantité</label>';
                    echo '    <input type="number" name="quantite_pompes" id="quantite_pompes" value="' . $record['quantite_pompes'] . '">';
                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'abdominaux':
                    echo '<div class="category" id="abdominaux">';
                    echo '<li><span class="category-title">Abdominaux</span></li>';
                    echo '<ul>';
                    echo '<li><span>Séries</span> <span data-field="series_abdominaux">' . $record['series_abdominaux'] . '</span></li>';
                    echo '<li><span>Quantité</span> <span data-field="quantite_abdominaux">' . $record['quantite_abdominaux'] . '</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="abdominaux">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=abdominaux">';
                    echo '    <input type="hidden" name="category" value="abdominaux">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="series_abdominaux">Séries</label>';
                    echo '    <input type="number" name="series_abdominaux" id="series_abdominaux" value="' . $record['series_abdominaux'] . '">';
                    echo '    <label for="quantite_abdominaux">Quantité</label>';
                    echo '    <input type="number" name="quantite_abdominaux" id="quantite_abdominaux" value="' . $record['quantite_abdominaux'] . '">';
                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'squat':
                    echo '<div class="category" id="squat">';
                    echo '<li><span class="category-title">Squats</span></li>';
                    echo '<ul>';
                    echo '<li><span>Séries</span> <span data-field="series_squats">' . $record['series_squats'] . '</span></li>';
                    echo '<li><span>Quantité</span> <span data-field="quantite_squats">' . $record['quantite_squats'] . '</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="squat">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=squat">';
                    echo '    <input type="hidden" name="category" value="squat">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="series_squats">Séries</label>';
                    echo '    <input type="number" name="series_squats" id="series_squats" value="' . $record['series_squats'] . '">';
                    echo '    <label for="quantite_squats">Quantité</label>';
                    echo '    <input type="number" name="quantite_squats" id="quantite_squats" value="' . $record['quantite_squats'] . '">';

                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'gainage':
                    echo '<div class="category" id="gainage">';
                    echo '<li><span class="category-title">Gainage</span></li>';
                    echo '<ul>';
                    echo '<li><span>Durée</span> <span data-field="duree_gainage">' . $record['duree_gainage'] . '</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="gainage">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=gainage">';
                    echo '    <input type="hidden" name="category" value="gainage">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="duree_gainage">Durée</label>';

                    echo '    <input type="time" name="duree_gainage" id="duree_gainage" value="' . $record['duree_gainage'] . '">';

                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'traction':
                    echo '<div class="category" id="traction">';
                    echo '<li><span class="category-title">Tractions</span></li>';
                    echo '<ul>';
                    echo '<li><span>Séries</span> <span data-field="series_tractions">' . $record['series_tractions'] . '</span></li>';
                    echo '<li><span>Quantité</span> <span data-field="quantite_tractions">' . $record['quantite_tractions'] . '</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="traction">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=traction">';
                    echo '    <input type="hidden" name="category" value="traction">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="series_tractions">Séries</label>';
                    echo '    <input type="number" name="series_tractions" id="series_tractions" value="' . $record['series_tractions'] . '">';
                    echo '    <label for="quantite_tractions">Quantité</label>';
                    echo '    <input type="number" name="quantite_tractions" id="quantite_tractions" value="' . $record['quantite_tractions'] . '">';

                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'musculation':
                    echo '<div  class="category"id="musculation">';
                    echo '<li><span class="category-title">Renforcement musculaire</span></li>';
                    echo '<ul>';
                    echo '<li><span>Durée</span> <span data-field="duree_renforcement_musculaire">' . $record['duree_renforcement_musculaire'] . '</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="musculation">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=musculation">';
                    echo '    <input type="hidden" name="category" value="musculation">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="duree_renforcement_musculaire">Durée</label>';
                    echo '    <input type="time" name="duree_renforcement_musculaire" id="duree_renforcement_musculaire" value="' . $record['duree_renforcement_musculaire'] . '">';

                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'boxe':
                    echo '<div class="category" id="boxe">';
                    echo '<li><span class="category-title">Boxe</span></li>';
                    echo '<ul>';
                    echo '<li><span>Nombre de rounds</span> <span data-field="nombre_rounds_boxe">' . $record['nombre_rounds_boxe'] . '</span></li>';
                    echo '<li><span>Durée</span> <span data-field="duree_boxe">' . $record['duree_boxe'] . '</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="boxe">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=boxe">';
                    echo '    <input type="hidden" name="category" value="boxe">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="nombre_rounds_boxe">Nombre de rounds</label>';
                    echo '    <input type="number" name="nombre_rounds_boxe" id="nombre_rounds_boxe" value="' . $record['nombre_rounds_boxe'] . '">';

                    echo '    <label for="duree_boxe">Durée</label>';
                    echo '    <input type="time" name="duree_boxe" id="duree_boxe" value="' . $record['duree_boxe'] . '">';

                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;



                case 'marche':
                    echo '<div class="category" id="marche">';
                    echo '<li><span class="category-title">Marche</span></li>';
                    echo '<ul>';
                    echo '<li><span>Nombre de pas</span> <span data-field="nombre_pas">' . $record['nombre_pas'] . '</span></li>';
                    echo '<li><span>Distance</span> <span data-field="distance_marche">' . $record['distance_marche'] . ' km</span></li>';
                    echo '</ul>';
                    echo '<button class="edit-button" data-category="marche">Modifier</button>';

                    echo '<form class="edit-form" style="display: none;" method="POST" action="' . $url . '/traitement.php?form=marche">';
                    echo '    <input type="hidden" name="category" value="marche">';
                    echo '    <input type="hidden" name="record_id" value="' . $record['id'] . '">';
                    echo '    <label for="nombre_pas">Nombre de pas</label>';
                    echo '    <input type="number" name="nombre_pas" id="nombre_pas" value="' . $record['nombre_pas'] . '">';
                    echo '    <label for="distance_marche">Distance</label>';
                    echo '<input type="number" name="distance_marche" id="distance_marche" step="0.01" value="' . $record['distance_marche'] . '">';


                    echo '    <button type="submit">Enregistrer</button>';
                    echo '</form>';

                    echo '</div>';
                    break;
            }
        }
        echo '</ul>';
        echo '</div>';



        //echo '<div class="vegan"></div>';
        //echo '</div>';
    }
}



// Enregistrement du shortcode
add_shortcode('plug_modifydata7daysperf', 'get_user_performances_for_modify');
