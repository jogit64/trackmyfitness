<?php

/*
Plugin Name: 0_LBU_plug_commentpoidsimcgras
Description: Compare les dernières mesures de poids, d'IMC et de % de gras de l'utilisateur et propose un commentaire encourageant.
Version: 1.0
Author: Le Bon Univers
*/


// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances()
{
    global $wpdb;
    $user_id = get_current_user_id();

    // Requête pour récupérer les dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT poids_kg, pourcentage_gras, nombre_imc FROM {$wpdb->prefix}performances WHERE user_id = $user_id ORDER BY date DESC LIMIT 1";
    $result = $wpdb->get_row($query, ARRAY_A);

    if ($result) {
        // Récupération des valeurs
        $poids_kg = $result['poids_kg'];
        $pourcentage_gras = $result['pourcentage_gras'];
        $nombre_imc = $result['nombre_imc'];

        // Requête pour récupérer les valeurs précédentes
        $query = "SELECT poids_kg, pourcentage_gras, nombre_imc FROM {$wpdb->prefix}performances WHERE user_id = $user_id ORDER BY date DESC LIMIT 1,1";
        $result = $wpdb->get_row($query, ARRAY_A);

        if ($result) {
            // Récupération des valeurs précédentes
            $poids_kg_precedent = $result['poids_kg'];
            $pourcentage_gras_precedent = $result['pourcentage_gras'];
            $nombre_imc_precedent = $result['nombre_imc'];

            // Calcul des évolutions de chaque champ
            $evolution_poids_kg = $poids_kg - $poids_kg_precedent;
            $evolution_pourcentage_gras = $pourcentage_gras - $pourcentage_gras_precedent;
            $evolution_nombre_imc = $nombre_imc - $nombre_imc_precedent;

            // Commentaire encourageant en fonction des évolutions
            $commentaire = "Bravo ! ";
            if ($evolution_poids_kg < 0) {
                $commentaire .= "Vous avez perdu " . abs($evolution_poids_kg) . " kg depuis la dernière fois ! ";
            } elseif ($evolution_poids_kg > 0) {
                $commentaire .= "Vous avez pris " . abs($evolution_poids_kg) . " kg depuis la dernière fois, mais ne vous découragez pas ! ";
            } else {
                $commentaire .= "Votre poids est stable, c'est bien ! ";
            }

            if ($evolution_pourcentage_gras < 0) {
                $commentaire .= "Votre pourcentage de graisse a diminué de " . abs($evolution_pourcentage_gras) . " %, c'est super ! ";
            } elseif ($evolution_pourcentage_gras > 0) {
                $commentaire .= "Votre pourcentage de graisse a augmenté de " . abs($evolution_pourcentage_gras) . " %, mais vous allez y arriver ! ";
            } else {
                $commentaire .= "Votre pourcentage de graisse est stable, continuez comme ça ! ";
            }

            if ($evolution_nombre_imc < 0) {
                $commentaire .= "Votre IMC a baissé de " . abs($evolution_nombre_imc) . ", c'est une belle réussite ! ";
            } elseif ($evolution_nombre_imc > 0) {
                $commentaire .= "Votre IMC a augmenté de " . abs($evolution_nombre_imc) . ", mais vous pouvez vous reprendre en main ! ";
            } else {
                $commentaire .= "Votre IMC est stable, c'est bien ! ";
            }

            // Retourne le commentaire
            return $commentaire;
        } else {
            return "Impossible de récupérer les valeurs précédentes.";
        }
    } else {
        return "Impossible de récupérer les dernières valeurs.";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode()
{
    $commentaire = get_user_performances();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentpoidsimcgras', 'comment_shortcode');