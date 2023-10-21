<?php

/*
Plugin Name: 0_LBU_plug_commentpompes
Description: Compare les deux dernières valeurs des champs `series_pompes`, `quantite_pompes` et `total_pompes` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_pompes()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT series_pompes, quantite_pompes, total_pompes FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $series = $results[0]['series_pompes'];
        $quantite = $results[0]['quantite_pompes'];
        $total = $results[0]['total_pompes'];

        // Récupération des valeurs précédentes
        $series_precedent = $results[1]['series_pompes'];
        $quantite_precedent = $results[1]['quantite_pompes'];
        $total_precedent = $results[1]['total_pompes'];

        // Calcul des évolutions de chaque champ
        $evolution_series = $series - $series_precedent;
        $evolution_quantite = $quantite - $quantite_precedent;
        $evolution_total = $total - $total_precedent;

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction des évolutions
        $commentaire = ucfirst($user_login) . ", tu es un vrai champion(e) ! ";
        if ($evolution_series > 0 && $evolution_quantite > 0 && $evolution_total > 0) {
            $commentaire .= "Tu progresses à vitesse grand V ! Tes pompes sont de plus en plus belles !";
        } elseif ($evolution_series < 0 && $evolution_quantite < 0 && $evolution_total < 0) {
            $commentaire .= "Bon, il y a eu une petite baisse de régime, mais tu vas vite te reprendre et dépasser tes records !";
        } else {
            $commentaire .= "Tes performances en pompes sont stables, mais tu as le potentiel pour faire encore mieux. Ne lâche rien !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une série de pompes enregistrée pour toi. C'est un bon début ! Continue à pousser et reviens voir ton évolution !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes séries de pompes. Mets-toi en position et commence dès aujourd'hui !";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_pompes()
{
    $commentaire = get_user_performances_comment_pompes();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentpompes', 'comment_shortcode_pompes');
