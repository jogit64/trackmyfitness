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



// Vérifier que le formulaire a été soumis et que le bouton Enregistrer a été cliqué
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enregistrer']) && $_POST['enregistrer'] == 'form-performances') {

    // Récupérer les valeurs de formulaire
    $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $user_id = get_current_user_id();
    $minutes = $_POST['minutes_elliptique']; // Remplacer avec le nom de champ approprié
    $distance = $_POST['distance_elliptique']; // Remplacer avec le nom de champ approprié
    $calories = $_POST['calories_elliptique']; // Remplacer avec le nom de champ approprié
    // Ajouter une nouvelle entrée dans la table performances
    $query = $bdd->prepare('INSERT INTO performances (user_id, category, record_id, minutes, distance, calories) VALUES (:user_id, :category, :record_id, :minutes, :distance, :calories)');
    $query->execute(array(
        'user_id' => $user_id,
        'category' => $category,
        'record_id' => $record_id,
        'minutes' => $minutes,
        'distance' => $distance,
        'calories' => $calories
    ));
}


// Rediriger vers la page d'accueil ou une autre page si nécessaire
$url = home_url('/performances/');
header('Location: ' . $url);
