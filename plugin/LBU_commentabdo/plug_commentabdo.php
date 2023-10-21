<?php
/*
Plugin Name: 0_LBU_plug_commentabdos
Description: Compare les deux dernières valeurs des champs `series_abdominaux`, `quantite_abdominaux` et `total_abdominaux` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_abdos()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT series_abdominaux, quantite_abdominaux, total_abdominaux FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $series = $results[0]['series_abdominaux'];
        $quantite = $results[0]['quantite_abdominaux'];
        $total = $results[0]['total_abdominaux'];

        // Récupération des valeurs précédentes
        $series_precedent = $results[1]['series_abdominaux'];
        $quantite_precedent = $results[1]['quantite_abdominaux'];
        $total_precedent = $results[1]['total_abdominaux'];

        // Calcul des évolutions de chaque champ
        $evolution_series = $series - $series_precedent;
        $evolution_quantite = $quantite - $quantite_precedent;
        $evolution_total = $total - $total_precedent;

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction des évolutions
        $commentaire = ucfirst($user_login) . ", tu envoies du lourd sur tes abdos ! ";
        if ($evolution_series > 0 && $evolution_quantite > 0 && $evolution_total > 0) {
            $commentaire .= "Tu es sur la bonne voie pour obtenir un ventre sculpté ! Continue comme ça !";
        } elseif ($evolution_series < 0 && $evolution_quantite < 0 && $evolution_total < 0) {
            $commentaire .= "On a tous des jours sans, mais il ne faut pas se décourager ! Reprends ton souffle et continue de t'entraîner !";
        } else {
            $commentaire .= "Tes performances en abdos sont stables, c'est bien ! Mais n'oublie pas que pour obtenir de réels résultats, il faut être régulier dans ton entraînement. Allez, on se motive et on continue à travailler dur pour atteindre nos objectifs !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une série d'abdominaux enregistrée pour toi. C'est un bon début ! Continue à te contracter et reviens voir ton évolution !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes séries d'abdominaux. Prépare ton tapis et commence dès aujourd'hui pour un ventre plus tonique !";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_abdos()
{
    $commentaire = get_user_performances_comment_abdos();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentabdos', 'comment_shortcode_abdos');
