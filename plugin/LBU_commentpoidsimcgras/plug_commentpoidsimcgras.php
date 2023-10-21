<?php

/*
Plugin Name: 0_LBU_plug_commentpoidsimcgras
Description: Compare les dernières mesures de poids, d'IMC et de % de gras de l'utilisateur et propose un commentaire encourageant.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT poids_kg, pourcentage_gras, nombre_imc FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $poids_kg = $results[0]['poids_kg'];
        $pourcentage_gras = $results[0]['pourcentage_gras'];
        $nombre_imc = $results[0]['nombre_imc'];

        // Récupération des valeurs précédentes
        $poids_kg_precedent = $results[1]['poids_kg'];
        $pourcentage_gras_precedent = $results[1]['pourcentage_gras'];
        $nombre_imc_precedent = $results[1]['nombre_imc'];

        // Calcul des évolutions de chaque champ
        $evolution_poids_kg = number_format($poids_kg - $poids_kg_precedent, 1);
        $evolution_pourcentage_gras = number_format($pourcentage_gras - $pourcentage_gras_precedent, 1);
        $evolution_nombre_imc = number_format($nombre_imc - $nombre_imc_precedent, 1);

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;




        // Commentaire encourageant en fonction des évolutions
        $commentaire = "Bonjour $user_login ! <br/>";
        if ($evolution_poids_kg < 0) {
            $commentaire .= "Tu as perdu " . abs($evolution_poids_kg) . " kg depuis la dernière fois ! ";
        } elseif ($evolution_poids_kg > 0) {
            $commentaire .= "Tu as pris " . abs($evolution_poids_kg) . " kg depuis la dernière fois, mais ne te décourage pas ! ";
        } else {
            $commentaire .= "Ton poids est stable, c'est bien ! ";
        }

        if ($evolution_pourcentage_gras < 0) {
            $commentaire .= "Ton pourcentage de graisse a diminué de " . abs($evolution_pourcentage_gras) . " %, c'est super ! ";
        } elseif (
            $evolution_pourcentage_gras > 0
        ) {
            $commentaire .= "Ton pourcentage de graisse a augmenté de " . abs($evolution_pourcentage_gras) . " %, mais tu vas y arriver ! ";
        } else {
            $commentaire .= "Ton pourcentage de graisse est stable, continue comme ça ! ";
        }
        if ($evolution_nombre_imc < 0) {
            $commentaire .= "Ton IMC a baissé de " . abs($evolution_nombre_imc) . ", c'est une belle réussite ! ";
        } elseif ($evolution_nombre_imc > 0) {
            $commentaire .= "Ton IMC a augmenté de " . abs($evolution_nombre_imc) . ", mais tu peux te reprendre en main ! ";
        } else {
            $commentaire .= "Ton IMC est stable, c'est bien ! ";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Bonjour $user_login ! Nous avons seulement une valeur enregistrée pour toi. Continue tes efforts et reviens bientôt pour voir ton évolution&nbsp!";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Bonjour $user_login ! Nous n'avons pas encore de données enregistrées pour toi. Commence dès aujourd'hui et reviens bientôt pour suivre ton évolution&nbsp;!";
    }
}

// Utilise wp_enqueue_style pour mettre en file d'attente votre feuille de style
function lbu_enqueue_styles()
{
    // Récupère l'URL du répertoire parent du plugin
    $plugin_url = plugin_dir_url(dirname(__FILE__));

    // Enqueue le fichier main.css
    wp_enqueue_style('lbu-plugin-styles', $plugin_url . 'css/main.css');
}



// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode()
{
    $commentaire = get_user_performances_comment();
    return "<p class='lbu-comment'>$commentaire</p>";
}


// Enregistrement du shortcode
add_shortcode('plug_commentpoidsimcgras', 'comment_shortcode');
