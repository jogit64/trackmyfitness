<?php

/*
Plugin Name: 0_LBU_plug_commentvelo
Description: Compare les deux dernières valeurs des champs `minutes_elliptique`, `distance_elliptique` et `calories_elliptique` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_velo()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT minutes_elliptique, distance_elliptique, calories_elliptique FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $minutes = $results[0]['minutes_elliptique'];
        $distance = $results[0]['distance_elliptique'];
        $calories = $results[0]['calories_elliptique'];

        // Récupération des valeurs précédentes
        $minutes_precedent = $results[1]['minutes_elliptique'];
        $distance_precedent = $results[1]['distance_elliptique'];
        $calories_precedent = $results[1]['calories_elliptique'];

        // Calcul des évolutions de chaque champ
        $evolution_minutes = $minutes - $minutes_precedent;
        $evolution_distance = $distance - $distance_precedent;
        $evolution_calories = $calories - $calories_precedent;

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction des évolutions
        $commentaire = ucfirst($user_login) . " ! ";
        if ($evolution_minutes > 0 && $evolution_distance > 0 && $evolution_calories > 0) {
            $commentaire .= "Bravo ! Tu as amélioré tes performances à l'elliptique ! Continue comme ça !";
        } elseif ($evolution_minutes < 0 && $evolution_distance < 0 && $evolution_calories < 0) {
            $commentaire .= "Oh non ! Tes performances à l'elliptique ont diminué ! Ne te décourage pas, tu vas y arriver !";
        } else {
            $commentaire .= "Tes performances à l'elliptique sont stables. Continue à t'entraîner pour atteindre tes objectifs !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une session d'elliptique enregistrée pour toi. C'est un bon début ! Pédale encore et reviens voir ton évolution !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes sessions d'elliptique. Enfile tes baskets et commence dès aujourd'hui !";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_velo()
{
    $commentaire = get_user_performances_comment_velo();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentvelo', 'comment_shortcode_velo');
