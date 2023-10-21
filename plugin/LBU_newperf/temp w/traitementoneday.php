<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo ('hello world!');

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
$bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');



// Rediriger vers la page d'accueil ou une autre page si nécessaire
$url = home_url('/performances/');
header('Location: ' . $url);
