<?php

// Charger l'environnement WordPress
$wp_load_path = dirname(__FILE__);

while (!file_exists($wp_load_path . '/wp-load.php')) {
    $wp_load_path = dirname($wp_load_path);
    if (empty($wp_load_path)) {
        exit("Erreur: Impossible de trouver wp-load.php");
    }
}



require_once($wp_load_path . '/wp-load.php');


// Connexion à la base de données
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');
$bdd = getDatabaseConnection();


// Récupération de l'ID de l'enregistrement à supprimer
$record_id = $_POST['record_id'];

// Débogage
//echo "Enregistrement à supprimer : " . $record_id;

// Arrêt d'exécution pour débogage
//exit;

// Requête SQL pour supprimer l'enregistrement correspondant
$query = $bdd->prepare('DELETE FROM performances WHERE id = :record_id');
$query->execute(array('record_id' => $record_id));

// Rediriger vers la page d'accueil ou une autre page si nécessaire
$url = home_url('/modify/');
header('Location: ' . $url);
