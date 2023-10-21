<?php
/*
Plugin Name: 0_LBU_plug_showtrueblocks
Description: Affiche les blocks html des graphiques de sports de l'utilisateur.
Version: 1.0
Author: Le Bon Univers
*/

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once('/home/lebonub/www/tmf/wp-load.php');



function lbu_showtrueblocks_init()

{
    function sportlist_shortcode()
    {
        $user_id = get_current_user_id();
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Récupérer les champs à true pour l'utilisateur connecté
        $query = $bdd->prepare("SELECT pompes, abdominaux, squat, gainage, traction, musculation, velo_elliptique, boxe, marche FROM sportlist WHERE user_id = :user_id");
        $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query->execute();
        $champs = $query->fetch(PDO::FETCH_ASSOC);

        // Charger le fichier JavaScript pour gérer l'affichage des éléments du DOM
        wp_enqueue_script('sportlist-script', plugins_url('showtrueblocks/showtrueblocks.js', __FILE__), array('jquery'), false, true);

        // Transmettre les données des champs à JavaScript
        wp_localize_script('sportlist-script', 'sportlist_champs', $champs);

        // Retourner les éléments du DOM correspondants aux champs à true
        $output = '';

        // Vérifier si au moins un exercice sportif est activé
        $sport_exist = false;

        foreach ($champs as $nom_champ => $valeur_champ) {
            if ($valeur_champ == 1) {
                $output .= '<div id="' . $nom_champ . '"></div>';
                $sport_exist = true;
            }
        }

        // Si aucun sport n'a été sélectionné, afficher la section "default" et masquer "affTitre"
        if (!$sport_exist) {

            echo '<style>#affTitre { display: none; }</style>';
        } else {
            // Si au moins un sport est sélectionné, afficher la section "affTitre" et masquer "default"
            echo '<style>#default { display: none; }</style>';
        }



        return $output;
    }
    add_shortcode('showtrueblocks', 'sportlist_shortcode');
}

add_action('init', 'lbu_showtrueblocks_init');
