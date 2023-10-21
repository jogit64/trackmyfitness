<?php
/*
Plugin Name: 0_LBU_plug_commentboxe
Description: Compare les deux dernières valeurs des champs `nombre_rounds_boxe` et `duree_boxe` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_boxe()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT nombre_rounds_boxe, duree_boxe FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $nombre_rounds = $results[0]['nombre_rounds_boxe'];
        $duree = $results[0]['duree_boxe'];

        // Récupération des valeurs précédentes
        $nombre_rounds_precedent = $results[1]['nombre_rounds_boxe'];
        $duree_precedente = $results[1]['duree_boxe'];

        // Calcul des évolutions de chaque champ
        $evolution_nombre_rounds = $nombre_rounds - $nombre_rounds_precedent;
        $evolution_duree = strtotime($duree) - strtotime($duree_precedente);

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction des évolutions
        $commentaire = ucfirst($user_login) . " ! ";
        if ($evolution_nombre_rounds > 0 && $evolution_duree > 0) {
            $commentaire .= "Bravo ! Tu as augmenté le nombre de rounds et la durée de tes séances de boxe ! Continue comme ça !";
        } elseif ($evolution_nombre_rounds < 0 && $evolution_duree < 0) {
            $commentaire .= "Oh non ! Le nombre de rounds et la durée de tes séances de boxe ont diminué ! Ne te décourage pas, tu vas y arriver !";
        } else {
            $commentaire .= "Le nombre de rounds et la durée de tes séances de boxe sont stables. Continue à t'entraîner pour atteindre tes objectifs !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une session de boxe enregistrée pour toi. C'est un excellent début ! Continue à te battre sur le ring et reviens voir ton évolution !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes rounds de boxe. Enfile tes gants, échauffe-toi bien et lance-toi dans une session dès aujourd'hui pour améliorer ta technique et ta condition physique !";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_boxe()
{
    $commentaire = get_user_performances_comment_boxe();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentboxe', 'comment_shortcode_boxe');
