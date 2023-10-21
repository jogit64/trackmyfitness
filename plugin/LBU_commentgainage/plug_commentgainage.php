<?php
/*
Plugin Name: 0_LBU_plug_commentgainage
Description: Compare les deux dernières valeurs du champ `duree_gainage` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_gainage()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs du champ `duree_gainage` de l'utilisateur connecté
    $query = "SELECT duree_gainage FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $duree_gainage = $results[0]['duree_gainage'];

        // Récupération des valeurs précédentes
        $duree_gainage_precedent = $results[1]['duree_gainage'];

        // Conversion des chaînes de caractères au format `time` en timestamps
        $duree_timestamp = strtotime($duree_gainage) - strtotime('TODAY');
        $duree_precedent_timestamp = strtotime($duree_gainage_precedent) - strtotime('TODAY');

        // Calcul de l'évolution de la durée (en secondes)
        $evolution_gainage = $duree_timestamp - $duree_precedent_timestamp;


        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction de l'évolution
        $commentaire = ucfirst($user_login) . " ! ";
        if ($evolution_gainage > 0) {
            $commentaire .= "Bravo ! Tu as progressé en gainage ! Continue comme ça !";
        } elseif ($evolution_gainage < 0) {
            $commentaire .= "Oh non ! Ta performance en gainage a diminué ! Ne te décourage pas, tu vas y arriver !";
        } else {
            $commentaire .= "Ta performance en gainage est stable. Continue à t'entraîner pour atteindre tes objectifs !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une durée de gainage enregistrée pour toi. C'est un bon début ! Le gainage est excellent pour renforcer le tronc. Continue à te défier et à augmenter ton temps de gainage à chaque séance !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes séances de gainage. Le gainage est un excellent exercice pour renforcer ton tronc et améliorer ta posture. Pourquoi ne pas commencer dès aujourd'hui ?";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_gainage()
{
    $commentaire = get_user_performances_comment_gainage();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentgainage', 'comment_shortcode_gainage');
