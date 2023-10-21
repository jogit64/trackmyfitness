<?php

session_start();

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
require_once($_SERVER['DOCUMENT_ROOT'] . '/private/db.php');
$bdd = getDatabaseConnection();



// Récupérez les données soumises à partir des champs de formulaire (remplacez les noms de champs par les vôtres)
$date = !empty($_POST['day']) ? $_POST['day'] : null;
$poids_kg = !empty($_POST['poids_kg']) ? $_POST['poids_kg'] : null;
$pourcentage_gras = !empty($_POST['pourcentage_gras']) ? $_POST['pourcentage_gras'] : null;
$nombre_imc = !empty($_POST['nombre_imc']) ? $_POST['nombre_imc'] : null;
$minutes_elliptique = !empty($_POST['minutes_elliptique']) ? $_POST['minutes_elliptique'] : null;
$distance_elliptique = !empty($_POST['distance_elliptique']) ? $_POST['distance_elliptique'] : null;
$calories_elliptique = !empty($_POST['calories_elliptique']) ? $_POST['calories_elliptique'] : null;
$series_abdominaux = !empty($_POST['series_abdominaux']) ? $_POST['series_abdominaux'] : null;
$quantite_abdominaux = !empty($_POST['quantite_abdominaux']) ? $_POST['quantite_abdominaux'] : null;
$duree_gainage = !empty($_POST['duree_gainage']) ? $_POST['duree_gainage'] : null;
$series_pompes = !empty($_POST['series_pompes']) ? $_POST['series_pompes'] : null;
$quantite_pompes = !empty($_POST['quantite_pompes']) ? $_POST['quantite_pompes'] : null;
$series_tractions = !empty($_POST['series_tractions']) ? $_POST['series_tractions'] : null;
$quantite_tractions = !empty($_POST['quantite_tractions']) ? $_POST['quantite_tractions'] : null;
$series_squats = !empty($_POST['series_squats']) ? $_POST['series_squats'] : null;
$quantite_squats = !empty($_POST['quantite_squats']) ? $_POST['quantite_squats'] : null;
$nombre_pas = !empty($_POST['nombre_pas']) ? $_POST['nombre_pas'] : null;
$distance_marche = !empty($_POST['distance_marche']) ? $_POST['distance_marche'] : null;
$nombre_rounds_boxe = !empty($_POST['nombre_rounds_boxe']) ? $_POST['nombre_rounds_boxe'] : null;
$duree_boxe = !empty($_POST['duree_boxe']) ? $_POST['duree_boxe'] : null;
$duree_renforcement_musculaire = !empty($_POST['duree_renforcement_musculaire']) ? $_POST['duree_renforcement_musculaire'] : null;


$total_abdominaux = $series_abdominaux * $quantite_abdominaux;
$total_pompes = $series_pompes * $quantite_pompes;
$total_tractions = $series_tractions * $quantite_tractions;
$total_squats = $series_squats * $quantite_squats;


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
    $_SESSION['error_message'] = "Il existe déjà un enregistrement pour cette date.<br/>Veuillez vous rendre sur la page de modification pour mettre à jour les données.<br/><a href='https://www.trackmyfitness.fr/modify/' class='error-btn'>Aller à la page de modification</a>";
    header('Location: https://www.trackmyfitness.fr/newperf/');
    exit;
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
$url = home_url('/modify/');
header('Location: ' . $url);
exit;
