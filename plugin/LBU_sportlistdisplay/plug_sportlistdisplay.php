<?php
/*
Plugin Name: 0_LBU_plug_sportlistdisplay
Description: Affiche les champs de la table sportlist si leur valeur est true pour l'utilisateur connecté
Version: 1.0
Author: LeBonUnivers
*/


function liste_activites_sportives()
{
    // Récupération de l'ID de l'utilisateur courant
    $user_id = get_current_user_id();

    // Connexion à la base de données
    $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

    // Préparation de la requête SQL pour récupérer les activités de l'utilisateur courant
    $query = "SELECT * FROM sportlist WHERE user_id = :user_id";
    $statement = $bdd->prepare($query);
    $statement->bindParam(':user_id', $user_id);
    $statement->execute();

    // Récupération des résultats de la requête sous forme d'un tableau associatif
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Génération de la liste des activités sous forme de nuage de tags
    $liste_activites = '<div class="sport-list-tableaubord">';
    foreach ($result as $key => $value) {
        if (
            $key != 'id' && $key != 'user_id' && $value == 1
        ) {
            $liste_activites .= '<span class="sport-list-tag">' . ucfirst(str_replace('_', ' ', $key)) . '</span>';
        }
    }
    $liste_activites .= '</div>';


    return $liste_activites;
}

// Création du shortcode [liste_activites_sportives]
add_shortcode('sportlistdisplay', 'liste_activites_sportives');
