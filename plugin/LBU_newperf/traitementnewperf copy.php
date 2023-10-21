<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Charger l'environnement WordPress
$wp_load_path = dirname(__FILE__);

while (!file_exists($wp_load_path . '/wp-load.php')) {
    $wp_load_path = dirname($wp_load_path);
    if (empty($wp_load_path)) {
        exit("Erreur: Impossible de trouver wp-load.php");
    }
}



require_once($wp_load_path . '/wp-load.php');



$current_user = wp_get_current_user();
$user_id = $current_user->ID;


// Connexion à la base de données
$bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

// Récupérez les données soumises à partir des champs de formulaire (remplacez les noms de champs par les vôtres)
$date = $_POST['day'];
$poids_kg = $_POST['poids_kg'];
$pourcentage_gras = $_POST['pourcentage_gras'];
$nombre_imc = $_POST['nombre_imc'];
$minutes_elliptique = $_POST['minutes_elliptique'];
$distance_elliptique = $_POST['distance_elliptique'];
$calories_elliptique = $_POST['calories_elliptique'];
$series_abdominaux = $_POST['series_abdominaux'];
$quantite_abdominaux = $_POST['quantite_abdominaux'];
$total_abdominaux = $_POST['total_abdominaux'];
$duree_gainage = $_POST['duree_gainage'];
$series_pompes = $_POST['series_pompes'];
$quantite_pompes = $_POST['quantite_pompes'];
$total_pompes = $_POST['total_pompes'];
$series_tractions = $_POST['series_tractions'];
$quantite_tractions = $_POST['quantite_tractions'];
$total_tractions = $_POST['total_tractions'];
$series_squats = $_POST['series_squats'];
$quantite_squats = $_POST['quantite_squats'];
$total_squats = $_POST['total_squats'];
$nombre_pas = $_POST['nombre_pas'];
$distance_marche = $_POST['distance_marche'];
$nombre_rounds_boxe = $_POST['nombre_rounds_boxe'];
$duree_boxe = $_POST['duree_boxe'];
$duree_renforcement_musculaire = $_POST['duree_renforcement_musculaire'];


// // Affichez les données du formulaire
// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";

// // Arrêtez l'exécution du script pour le test
// exit;

// Vérifiez si l'enregistrement pour cette date existe déjà
$query = $bdd->prepare("SELECT * FROM performances WHERE user_id = :user_id AND day = :day");
$query->execute(array(
    'user_id' => $user_id,
    'day' => $date
));
$existing_record = $query->fetch();

if ($existing_record) {
    // Si l'enregistrement existe, affichez un message à l'utilisateur
    echo "Il existe déjà un enregistrement pour cette date. Veuillez vous rendre sur la page de modification pour mettre à jour les données.";
} else {
    // Sinon, insérez un nouvel enregistrement
    $query = $bdd->prepare(
        "INSERT INTO performances (user_id, day, poids_kg, pourcentage_gras, nombre_imc, minutes_elliptique, distance_elliptique, calories_elliptique, series_abdominaux, quantite_abdominaux, total_abdominaux, duree_gainage, series_pompes, quantite_pompes, total_pompes, series_tractions, quantite_tractions, total_tractions, series_squats, quantite_squats, total_squats, nombre_pas, distance_marche, nombre_rounds_boxe, duree_boxe, duree_renforcement_musculaire) 
    VALUES (:user_id, :day, :poids_kg, :pourcentage_gras, :nombre_imc, :minutes_elliptique, :distance_elliptique, :calories_elliptique, :series_abdominaux, :quantite_abdominaux, :total_abdominaux, :duree_gainage, :series_pompes, :quantite_pompes, :total_pompes, :series_tractions, :quantite_tractions, :total_tractions, :series_squats, :quantite_squats, :total_squats, :nombre_pas, :distance_marche, :nombre_rounds_boxe, :duree_boxe, :duree_renforcement_musculaire)"
    );


    $query->execute(array(
        'user_id' => $user_id,
        'day' => $date,
        'poids_kg' => $poids_kg,
        'pourcentage_gras' => $pourcentage_gras,
        'nombre_imc' => $nombre_imc,
        'minutes_elliptique' => $minutes_elliptique,
        'distance_elliptique' => $distance_elliptique,
        'calories_elliptique' => $calories_elliptique,
        'series_abdominaux' => $series_abdominaux,
        'quantite_abdominaux' => $quantite_abdominaux,
        'total_abdominaux' => $total_abdominaux,
        'duree_gainage' => $duree_gainage,
        'series_pompes' => $series_pompes,
        'quantite_pompes' => $quantite_pompes,
        'total_pompes' => $total_pompes,
        'series_tractions' => $series_tractions,
        'quantite_tractions' => $quantite_tractions,
        'total_tractions' => $total_tractions,
        'series_squats' => $series_squats,
        'quantite_squats' => $quantite_squats,
        'total_squats' => $total_squats,
        'nombre_pas' => $nombre_pas,
        'distance_marche' => $distance_marche,
        'nombre_rounds_boxe' => $nombre_rounds_boxe,
        'duree_boxe' => $duree_boxe,
        'duree_renforcement_musculaire' => $duree_renforcement_musculaire
    ));
    // echo "Les données ont été enregistrées avec succès.";
}



// Rediriger vers la page d'accueil ou une autre page si nécessaire
$url = home_url('/tableau-de-bord/');
header('Location: ' . $url);