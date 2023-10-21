<?php
/*
Plugin Name: 0_LBU_plug_commentmuscu
Description: Compare les deux dernières valeurs des champs `duree_renforcement_musculaire` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_muscu()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs du champ `duree_renforcement_musculaire` de l'utilisateur connecté
    $query = "SELECT duree_renforcement_musculaire FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $duree = $results[0]['duree_renforcement_musculaire'];
        $duree_precedent = $results[1]['duree_renforcement_musculaire'];

        // Conversion des chaînes de caractères au format `time` en timestamps
        $duree_timestamp = strtotime($duree) - strtotime('TODAY');
        $duree_precedent_timestamp = strtotime($duree_precedent) - strtotime('TODAY');

        // Calcul de l'évolution de la durée (en secondes)
        $evolution_duree = $duree_timestamp - $duree_precedent_timestamp;


        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction de l'évolution
        $commentaire = ucfirst($user_login) . " ! ";
        if ($evolution_duree > 0) {
            $commentaire .= "Bravo ! Tu as augmenté ta durée d'entraînement en musculation ! Continue comme ça !";
        } elseif ($evolution_duree < 0) {
            $commentaire .= "Oh non ! Ta durée d'entraînement en musculation a diminué ! Ne te décourage pas, tu vas y arriver !";
        } else {
            $commentaire .= "Ta durée d'entraînement en musculation est stable. Continue à t'entraîner pour atteindre tes objectifs !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une session de renforcement musculaire enregistrée pour toi. C'est un super début ! Continue à te muscler et à te renforcer, et reviens voir ton évolution !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé ton renforcement musculaire. Prépare-toi, échauffe-toi bien et lance-toi dans une session dès aujourd'hui pour un corps plus fort et tonique !";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_muscu()
{
    $commentaire = get_user_performances_comment_muscu();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentmuscu', 'comment_shortcode_muscu');
