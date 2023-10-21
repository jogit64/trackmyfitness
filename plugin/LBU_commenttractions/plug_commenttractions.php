<?php

/*
Plugin Name: 0_LBU_plug_commenttraction
Description: Compare les deux dernières valeurs des champs `series_tractions`, `quantite_tractions` et `total_tractions` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_traction()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT series_tractions, quantite_tractions, total_tractions FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $series = $results[0]['series_tractions'];
        $quantite = $results[0]['quantite_tractions'];
        $total = $results[0]['total_tractions'];

        // Récupération des valeurs précédentes
        $series_precedent = $results[1]['series_tractions'];
        $quantite_precedent = $results[1]['quantite_tractions'];
        $total_precedent = $results[1]['total_tractions'];

        // Calcul des évolutions de chaque champ
        $evolution_series = $series - $series_precedent;
        $evolution_quantite = $quantite - $quantite_precedent;
        $evolution_total = $total - $total_precedent;

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction des évolutions
        $commentaire = ucfirst($user_login) . " ! ";
        if ($evolution_series > 0 && $evolution_quantite > 0 && $evolution_total > 0) {
            $commentaire .= "Waouh, tu es en feu ! Tes performances en tractions ne cessent de s'améliorer !";
        } elseif ($evolution_series < 0 && $evolution_quantite < 0 && $evolution_total < 0) {
            $commentaire .= "Tu as connu une petite baisse de régime en tractions, mais ce n'est qu'une étape. Tu vas très vite rebondir !";
        } else {
            $commentaire .= "Continue à travailler dur, les résultats en tractions finiront par se voir et t'encourageront à persévérer !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une série de tractions enregistrée pour toi. C'est un bon début ! Les tractions sont un excellent moyen de renforcer le haut du corps. Continue à te défier et à augmenter ton nombre de tractions à chaque séance !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes séances de tractions. Les tractions sont un excellent exercice pour renforcer ton dos, tes épaules et tes bras. Pourquoi ne pas commencer dès aujourd'hui ?";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_traction()
{
    $commentaire = get_user_performances_comment_traction();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commenttraction', 'comment_shortcode_traction');
