<?php
/*
Plugin Name: 0_LBU_plug_newperf
Description: Permet l'enregistrement des performances pour un nouveau jour.
Version: 1.0
Author: Le Bon Univers
*/


session_start();


function get_user_performances_for_record()
{
    // Connexion à la base de données
    require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');
    $bdd = getDatabaseConnection();


    // Récupération de l'identifiant de l'utilisateur connecté
    $user_id = get_current_user_id();

    // définition du chemin de l'URL pour les form
    $url = plugins_url('/LBU_newperf');




    // Récupération des sports sélectionnés (avec une valeur true) pour l'utilisateur connecté depuis la table sportlist
    $query2 = $bdd->prepare('SELECT * FROM sportlist WHERE user_id = :user_id AND (velo_elliptique = 1 OR pompes = 1 OR abdominaux = 1 OR squat = 1 OR gainage = 1 OR traction = 1 OR musculation = 1 OR  boxe = 1 OR marche = 1)');
    $query2->execute(array(
        'user_id' => $user_id
    ));
    $sportlist = $query2->fetchAll();

    // Création d'un tableau $sportlist_true pour stocker les sports sélectionnés par l'utilisateur
    $sportlist_true = [];

    // Vérifier si les champs sont à true et les stocker dans le tableau
    foreach ($sportlist[0] as $sport => $value) {
        if ($value == 1 && $sport != 'user_id' && $sport != 'id') {
            $sportlist_true[] = $sport;
        }
    }




    // Création d'un formulaire d'entête "Date" avec la possibilité de choisir le jour
    // Début du conteneur flexbox
    echo '<form class="edit-form-perf" method="POST" action="' . $url . '/traitementnewperf.php">';
    if (isset($_SESSION['error_message'])) {
        echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);  // Supprimez le message de la session après l'avoir affiché
    }
    // Bloc "Date" à l'intérieur du formulaire mais en dehors du flux flexbox
    echo '<div class="date-container">';


    echo '<div class="date-inner-container required-field">';
    echo '<input type="date" name="day" id="day-top" required>';
    echo '<input type="submit" value="Enregistrer">';
    echo '</div>';
    echo '</div>';



    // Création d'un formulaire nommé Poids avec les inputs pour renseigner les informations
    echo '<div class="category-perf" id="poids">';
    echo '<h3>Poids</h3>';
    echo '<label for="poids_kg">Poids (kg) :</label>';
    echo '<input type="number" step="0.01" name="poids_kg" id="poids_kg">';
    echo '<label for="pourcentage_gras">Pourcentage de graisse (%) :</label>';
    echo '<input type="number" step="0.01" name="pourcentage_gras" id="pourcentage_gras">';
    echo '<label for="nombre_imc">Nombre IMC :</label>';
    echo '<input type="number" step="0.01" name="nombre_imc" id="nombre_imc">';
    echo '</div>';


    // Création et affichage des formulaires d'enregistrement pour chaque sport
    foreach ($sportlist_true as $id) {
        switch ($id) {
            case 'velo_elliptique':
                echo '<div class="category-perf" id="velo_elliptique">';
                echo '<h3>Vélo elliptique</h3>';
                echo '<label for="minutes_elliptique">Minutes :</label>';
                echo '<input type="number" name="minutes_elliptique" id="minutes_elliptique">';
                echo '<label for="distance_elliptique">Distance :</label>';
                echo '<input type="number" step="0.01" name="distance_elliptique" id="distance_elliptique">';
                echo '<label for="calories_elliptique">Calories :</label>';
                echo '<input type="number" name="calories_elliptique" id="calories_elliptique">';
                echo '</div>';
                break;

            case 'pompes':
                echo '<div class="category-perf" id="pompes">';
                echo '<h3>Pompes</h3>';
                echo '<label for="series_pompes">Séries :</label>';
                echo '<input type="number" name="series_pompes" id="series_pompes">';
                echo '<label for="quantite_pompes">Quantité :</label>';
                echo '<input type="number" name="quantite_pompes" id="quantite_pompes">';
                echo '</div>';
                break;

            case 'abdominaux':
                echo '<div class="category-perf" id="abdominaux">';
                echo '<h3>Abdominaux</h3>';
                echo '<label for="series_abdominaux">Séries :</label>';
                echo '<input type="number" name="series_abdominaux" id="series_abdominaux">';
                echo '<label for="quantite_abdominaux">Quantité :</label>';
                echo '<input type="number" name="quantite_abdominaux" id="quantite_abdominaux">';
                echo '</div>';
                break;

            case 'squat':
                echo '<div class="category-perf" id="squat">';
                echo '<h3>Squats</h3>';
                echo '<label for="series_squats">Séries :</label>';
                echo '<input type="number" name="series_squats" id="series_squats">';
                echo '<label for="quantite_squats">Quantité :</label>';
                echo '<input type="number" name="quantite_squats" id="quantite_squats">';
                echo '</div>';
                break;

            case 'gainage':
                echo '<div class="category-perf" id="gainage">';
                echo '<h3>Gainage</h3>';
                echo '<label for="duree_gainage">Durée :</label>';
                echo '<input type="time" name="duree_gainage" id="duree_gainage">';
                echo '</div>';
                break;

            case 'traction':
                echo '<div class="category-perf" id="traction">';
                echo '<h3>Tractions</h3>';
                echo '<label for="series_tractions">Séries :</label>';
                echo '<input type="number" name="series_tractions" id="series_tractions">';
                echo '<label for="quantite_tractions">Quantité :</label>';
                echo '<input type="number" name="quantite_tractions" id="quantite_tractions">';
                echo '</div>';
                break;

            case 'musculation':
                echo '<div class="category-perf" id="musculation">';
                echo '<h3>Renforcement musculaire</h3>';
                echo '<label for="duree_renforcement_musculaire">Durée :</label>';
                echo '<input type="time" name="duree_renforcement_musculaire" id="duree_renforcement_musculaire">';
                echo '</div>';
                break;

            case 'boxe':
                echo '<div class="category-perf" id="boxe">';
                echo '<h3>Boxe</h3>';
                echo '<label for="nombre_rounds_boxe">Nombre de rounds :</label>';
                echo '<input type="number" name="nombre_rounds_boxe" id="nombre_rounds_boxe">';
                echo '<label for="duree_boxe">Durée :</label>';
                echo '<input type="time" name="duree_boxe" id="duree_boxe">';
                echo '</div>';
                break;

            case 'marche':
                echo '<div class="category-perf" id="marche">';
                echo '<h3>Marche</h3>';
                echo '<label for="nombre_pas">Nombre de pas :</label>';
                echo '<input type="number" name="nombre_pas" id="nombre_pas">';
                echo '<label for="distance_marche">Distance (km) :</label>';
                echo '<input type="number" name="distance_marche" id="distance_marche" step="0.01">';
                echo '</div>';
                break;
        }
    }

    echo '</form>';
}




// Enregistrement du shortcode
add_shortcode('plug_newperf', 'get_user_performances_for_record');
