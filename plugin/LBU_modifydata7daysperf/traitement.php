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



// Vérifier si la requête provient du formulaire poids
if (isset($_POST['form']) && $_POST['form'] == 'poids') {

    // Récupérer les données du formulaire
    $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $poids_kg = $_POST['poids_kg'];
    $pourcentage_gras = $_POST['pourcentage_gras'];
    $nombre_imc = $_POST['nombre_imc'];

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET poids_kg = :poids_kg, pourcentage_gras = :pourcentage_gras, nombre_imc = :nombre_imc WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'poids_kg' => $poids_kg,
        'pourcentage_gras' => $pourcentage_gras,
        'nombre_imc' => $nombre_imc,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}




// Vérifier si la requête provient du formulaire elliptique
if (isset($_POST['form']) && $_POST['form'] == 'elliptique') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $minutes_elliptique = $_POST['minutes_elliptique'];
    $distance_elliptique = $_POST['distance_elliptique'];
    $calories_elliptique = $_POST['calories_elliptique'];

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET minutes_elliptique = :minutes_elliptique, distance_elliptique = :distance_elliptique, calories_elliptique = :calories_elliptique WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'minutes_elliptique' => $minutes_elliptique,
        'distance_elliptique' => $distance_elliptique,
        'calories_elliptique' => $calories_elliptique,
        'record_id' => $record_id

    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}



/// Vérifier si la requête provient du formulaire pompes
if (isset($_POST['form']) && $_POST['form'] == 'pompes') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $series_pompes = $_POST['series_pompes'];
    $quantite_pompes = $_POST['quantite_pompes'];

    // Calculer le champ total_pompes
    $total_pompes = $series_pompes * $quantite_pompes;

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET series_pompes = :series_pompes, quantite_pompes = :quantite_pompes, total_pompes = :total_pompes WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'series_pompes' => $series_pompes,
        'quantite_pompes' => $quantite_pompes,
        'total_pompes' => $total_pompes,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}



if (isset($_POST['form']) && $_POST['form'] == 'abdominaux') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $series_abdominaux = $_POST['series_abdominaux'];
    $quantite_abdominaux = $_POST['quantite_abdominaux'];

    // Calculer le total des abdominaux
    $total_abdominaux = $series_abdominaux * $quantite_abdominaux;

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET series_abdominaux = :series_abdominaux, quantite_abdominaux = :quantite_abdominaux, total_abdominaux = :total_abdominaux WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'series_abdominaux' => $series_abdominaux,
        'quantite_abdominaux' => $quantite_abdominaux,
        'total_abdominaux' => $total_abdominaux,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}



if (isset($_POST['form']) && $_POST['form'] == 'squat') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $series_squats = $_POST['series_squats'];
    $quantite_squats = $_POST['quantite_squats'];

    // Calculer la valeur du champ total_squats
    $total_squats = $series_squats * $quantite_squats;

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET series_squats = :series_squats, quantite_squats = :quantite_squats, total_squats = :total_squats WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'series_squats' => $series_squats,
        'quantite_squats' => $quantite_squats,
        'total_squats' => $total_squats,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}



if (isset($_POST['form']) && $_POST['form'] == 'gainage') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $duree_gainage = $_POST['duree_gainage'];

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET duree_gainage = :duree_gainage WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'duree_gainage' => $duree_gainage,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}


if (isset($_POST['form']) && $_POST['form'] == 'traction') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $series_tractions = $_POST['series_tractions'];
    $quantite_tractions = $_POST['quantite_tractions'];

    // Calculer le total
    $total_tractions = $series_tractions * $quantite_tractions;

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET series_tractions = :series_tractions, quantite_tractions = :quantite_tractions, total_tractions = :total_tractions WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'series_tractions' => $series_tractions,
        'quantite_tractions' => $quantite_tractions,
        'total_tractions' => $total_tractions,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}



if (isset($_POST['form']) && $_POST['form'] == 'musculation') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $duree_renforcement_musculaire = $_POST['duree_renforcement_musculaire'];

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET duree_renforcement_musculaire = :duree_renforcement_musculaire WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'duree_renforcement_musculaire' => $duree_renforcement_musculaire,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}


if (isset($_POST['form']) && $_POST['form'] == 'boxe') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $nombre_rounds_boxe = $_POST['nombre_rounds_boxe'];
    $duree_boxe = $_POST['duree_boxe'];

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET nombre_rounds_boxe = :nombre_rounds_boxe, duree_boxe = :duree_boxe WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'nombre_rounds_boxe' => $nombre_rounds_boxe,
        'duree_boxe' => $duree_boxe,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}


if (isset($_POST['form']) && $_POST['form'] == 'marche') {
    // Récupérer les données du formulaire
    // $category = $_POST['category'];
    $record_id = $_POST['record_id'];
    $nombre_pas = $_POST['nombre_pas'];
    $distance_marche = $_POST['distance_marche'];

    // Préparer la requête de mise à jour
    $query = $bdd->prepare("UPDATE performances SET nombre_pas = :nombre_pas, distance_marche = :distance_marche WHERE id = :record_id");

    // Exécuter la requête avec les données
    $query->execute(array(
        'nombre_pas' => $nombre_pas,
        'distance_marche' => $distance_marche,
        'record_id' => $record_id
    ));

    // Rediriger vers la page d'accueil ou une autre page si nécessaire
    $url = home_url('/modify/');
    header('Location: ' . $url);
}
