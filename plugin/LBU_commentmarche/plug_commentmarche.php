<?php
/*
Plugin Name: 0_LBU_plug_commentmarche
Description: Compare les deux dernières valeurs des champs `nombre_pas` et `distance_marche` de l'utilisateur et propose un commentaire encourageant ou sarcastique en fonction de l'évolution des données.
Version: 1.0
Author: Le Bon Univers
*/

// Inclusion du fichier de connexion sécurisée à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');

// Fonction pour récupérer les données de l'utilisateur connecté avec son ID
function get_user_performances_comment_marche()
{
    $user_id = get_current_user_id();

    // Connexion sécurisée à la base de données
    $bdd = getDatabaseConnection();

    if (!$bdd) {
        return "Erreur lors de la connexion à la base de données.";
    }

    // Requête préparée pour récupérer les deux dernières valeurs de chaque champ de l'utilisateur connecté
    $query = "SELECT nombre_pas, distance_marche FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 2";
    $stmt = $bdd->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($results) == 2) {
        // Récupération des valeurs
        $pas = $results[0]['nombre_pas'];
        $distance = $results[0]['distance_marche'];

        // Récupération des valeurs précédentes
        $pas_precedent = $results[1]['nombre_pas'];
        $distance_precedente = $results[1]['distance_marche'];

        // Calcul des évolutions de chaque champ
        $evolution_pas = $pas - $pas_precedent;
        $evolution_distance = $distance - $distance_precedente;

        // Récupération du prénom de l'utilisateur connecté
        $user_login = wp_get_current_user()->user_login;

        // Commentaire encourageant en fonction des évolutions
        $commentaire = ucfirst($user_login) . " ! ";
        if ($evolution_pas > 0 && $evolution_distance > 0) {
            $commentaire .= "Bravo ! Tu as fait plus de pas et parcouru plus de distance à la marche ! Continue comme ça !";
        } elseif ($evolution_pas < 0 && $evolution_distance < 0) {
            $commentaire .= "Oh non ! Tu as fait moins de pas et parcouru moins de distance à la marche ! Ne te décourage pas, tu vas y arriver !";
        } else {
            $commentaire .= "Tes performances à la marche sont stables. Continue à t'entraîner pour atteindre tes objectifs !";
        }
        // Retourne le commentaire
        return $commentaire;
    } elseif (count($results) == 1) {
        // Cas où il n'y a qu'une seule valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Salut $user_login ! Nous avons seulement une session de marche enregistrée pour toi. C'est un excellent début ! Continue à mettre un pied devant l'autre et reviens voir ton évolution !";
    } else {
        // Cas où il n'y a aucune valeur enregistrée
        $user_login = wp_get_current_user()->user_login;
        return "Hey $user_login ! On dirait que tu n'as pas encore commencé tes sessions de marche. Chausse tes baskets, respire un bon coup et lance-toi dans une belle balade dès aujourd'hui pour profiter des bienfaits de la marche !";
    }
}

// Fonction pour afficher le commentaire encourageant sous forme de shortcode
function comment_shortcode_marche()
{
    $commentaire = get_user_performances_comment_marche();
    return "<p>$commentaire</p>";
}

// Enregistrement du shortcode
add_shortcode('plug_commentmarche', 'comment_shortcode_marche');
