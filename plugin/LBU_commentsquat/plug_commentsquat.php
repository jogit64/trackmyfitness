<?php

/*
Plugin Name: 0_LBU_plug_commentsquats
Description: Compare les deux dernières valeurs des champs `series_squats`, `quantite_squats` et `total_squats` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_squats()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT series_squats, quantite_squats, total_squats FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $series = $results[0]['series_squats'];
        $quantite = $results[0]['quantite_squats'];
        $total = $results[0]['total_squats'];

        // Récupération des valeurs précédentes
        $series_precedent = $results[1]['series_squats'];
        $quantite_precedent = $results[1]['quantite_squats'];
        $total_precedent = $results[1]['total_squats'];

        // Calcul des évolutions de chaque champ
        $evolution_series = $series - $series_precedent;
        $evolution_quantite = $quantite - $quantite_precedent;
        $evolution_total = $total - $total_precedent;

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction des évolutions
        $commentaire = ucfirst($user_login) . " ! ";
        if ($evolution_series > 0 && $evolution_quantite > 0 && $evolution_total > 0) {
            $commentaire .= "Bravo ! Tu as amélioré tes performances aux squats ! Continue comme ça, tu progresses à chaque entraînement !";
        } elseif ($evolution_series < 0 && $evolution_quantite < 0 && $evolution_total < 0) {
            $commentaire .= "Oh non ! Tes performances aux squats ont diminué ! Ne te décourage pas, tu vas y arriver, continue à t'entraîner dur !";
        } else {
            $commentaire .= "Tes performances aux squats sont stables. Continue à t'entraîner pour atteindre tes objectifs, tu construis une base solide pour tes futurs progrès !";
        }




        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une série de squats enregistrée pour toi. C'est un excellent début ! Continue à te baisser et à te relever, et reviens voir ton évolution !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes séries de squats. Prépare-toi, échauffe-toi bien et commence dès aujourd'hui pour des jambes et des fessiers toniques !";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_squats()
{
    $commentaire = get_user_performances_comment_squats();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentsquats', 'comment_shortcode_squats');
