<?php
/*
Plugin Name: 0_LBU_plug_sportlistmajdanddisplay
Description: Modifie et a ffiche les champs de la table sportlist si leur valeur est true pour l'utilisateur connecté
Version: 1.0
Author: LeBonUnivers
*/


function record_et_liste_activites_sportives()
{

    // Connexion à la base de données
    $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

    // Variable pour la redirection
    // $redirect_url = 'https://www.trackmyfitness.fr/wp-content/plugins/LBU_sportlistmajanddisplay/plug_sportlistmajanddisplay_c.php';


    // Vérifier que l'utilisateur est connecté à WordPress
    if (is_user_logged_in()) {
        // Récupérer l'ID de l'utilisateur connecté
        $user_id = get_current_user_id();

        // Si le formulaire est soumis, enregistrer les choix de sport dans la table "sportlist"
        if (isset($_POST['submit'])) {
            // Préparer les données pour l'insertion dans la base de données
            $pompes = isset($_POST['pompes']) ? 1 : 0;
            $abdominaux = isset($_POST['abdominaux']) ? 1 : 0;
            $squat = isset($_POST['squat']) ? 1 : 0;
            $gainage = isset($_POST['gainage']) ? 1 : 0;
            $traction = isset($_POST['traction']) ? 1 : 0;
            $musculation = isset($_POST['musculation']) ? 1 : 0;
            $velo_elliptique = isset($_POST['velo_elliptique']) ? 1 : 0;
            $boxe = isset($_POST['boxe']) ? 1 : 0;
            $marche = isset($_POST['marche']) ? 1 : 0;

            // Supprimer les choix précédents de l'utilisateur
            $stmt = $bdd->prepare("DELETE FROM sportlist WHERE user_id = :user_id");
            $stmt->execute(array(
                'user_id' => $user_id
            ));

            // Enregistrer les nouveaux sports cochés dans la base de données
            $stmt = $bdd->prepare("INSERT INTO sportlist (user_id, pompes, abdominaux, squat, gainage, traction, musculation, velo_elliptique, boxe, marche) VALUES (:user_id, :pompes, :abdominaux, :squat, :gainage, :traction, :musculation, :velo_elliptique, :boxe, :marche)");
            $stmt->execute(array(
                'user_id' => $user_id,
                'pompes' => $pompes,
                'abdominaux' => $abdominaux,
                'squat' => $squat,
                'gainage' => $gainage,
                'traction' => $traction,
                'musculation' => $musculation,
                'velo_elliptique' => $velo_elliptique,
                'boxe' => $boxe,
                'marche' => $marche
            ));

            // Rediriger vers la page intermédiaire
            //  echo "<script>window.location.href = '$redirect_url';</script>";


            echo '<p class="sport-list-profil">Les choix de sport ont été enregistrés.</p>';
        } // Pas besoin d'ajouter un else

    }


    // Récupérer les sports enregistrés par l'utilisateur connecté
    $stmt = $bdd->prepare("SELECT * FROM sportlist WHERE user_id = :user_id");
    $stmt->execute(array(
        'user_id' => $user_id
    ));
    $sports = $stmt->fetchAll();

    $output = '';


    if (!empty($sports)) {
        $output .= '<ul class="sport-list-profil">';
        foreach ($sports as $sport) {
            if ($sport['pompes']) {
                $output .= '<li class="sport-list-item">Pompes</li>';
            }
            if ($sport['abdominaux']) {
                $output .= '<li class="sport-list-item">Abdominaux</li>';
            }
            if ($sport['squat']) {
                $output .= '<li class="sport-list-item">Squat</li>';
            }
            if ($sport['gainage']) {
                $output .= '<li class="sport-list-item">Gainage</li>';
            }
            if ($sport['traction']) {
                $output .= '<li class="sport-list-item">Traction</li>';
            }
            if ($sport['musculation']) {
                $output .= '<li class="sport-list-item">Musculation</li>';
            }
            if ($sport['velo_elliptique']) {
                $output .= '<li class="sport-list-item">Vélo elliptique</li>';
            }
            if ($sport['boxe']) {
                $output .= '<li class="sport-list-item">Boxe</li>';
            }
            if ($sport['marche']) {
                $output .= '<li class="sport-list-item">Marche</li>';
            }
        }
        $output .= '</ul>';
    } else {
        $output .= '<p class="sport-list-profil">Aucun sport enregistré.</p>';
    }


    return $output;
}
add_shortcode('sportlistmajanddisplay', 'record_et_liste_activites_sportives');

// fonction pour protéger contre l'injection SQL
function secure_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
