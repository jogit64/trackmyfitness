<?php

// Connexion à la base de données
$bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

// Récupération de l'identifiant de l'utilisateur connecté
$user_id = get_current_user_id();

// Récupération des 7 derniers enregistrements
$query = $bdd->prepare('SELECT * FROM performances WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 7');
$query->execute(array(
    'user_id' => $user_id
));
$last_records = $query->fetchAll();

// Configuration locale pour les dates en français
setlocale(LC_TIME, 'fr_FR.utf8');



// Affichage des 7 derniers enregistrements
foreach ($last_records as $record) {
    // Formatage de la date
    $date = strftime('%A %e %B', strtotime($record['created_at']));

    // Affichage du record avec le style personnalisé
    echo '<div class="record">';
    echo '<h3>' . $date . '</h3>';
    echo '<p>Poids ' . $record['poids_kg'] . ' kg</p>';
    echo '<p>Pourcentage de graisse ' . $record['pourcentage_gras'] . ' %</p>';
    echo '<p>Nombre IMC ' . $record['nombre_imc'] . '</p>';
    echo '<ul>';
    // Affichage du vélo elliptique
    echo '<li><span class = "bld">Vélo elliptique</span></li>';
    echo '<ul>';
    echo '<li><span>Minutes</span> ' . $record['minutes_elliptique'] . '</li>';
    echo '<li><span>Distance</span> ' . $record['distance_elliptique'] . ' km</li>';
    echo '<li><span>Calories</span> ' . $record['calories_elliptique'] . ' kcal</li>';
    echo '</ul>';
    // Affichage des pompes
    echo '<li><span class = "bld">Pompes</span></li>';
    echo '<ul>';
    echo '<li><span>Séries</span> ' . $record['series_pompes'] . '</li>';
    echo '<li><span>Quantité</span> ' . $record['quantite_pompes'] . '</li>';
    echo '<li><span>Total</span> ' . $record['total_pompes'] . '</li>';
    echo '</ul>';
    // Affichage des abdominaux
    echo '<li><span class = "bld">Abdominaux</span></li>';
    echo '<ul>';
    echo '<li><span>Séries</span> ' . $record['series_abdominaux'] . '</li>';
    echo '<li><span>Quantité</span> ' . $record['quantite_abdominaux'] . '</li>';
    echo '<li><span>Total</span> ' . $record['total_abdominaux'] . '</li>';
    echo '</ul>';
    // Affichage des squats
    echo '<li><span class = "bld">Squats</span></li>';
    echo '<ul>';
    echo '<li><span>Séries</span> ' . $record['series_squats'] . '</li>';
    echo '<li><span>Quantité</span> ' . $record['quantite_squats'] . '</li>';
    echo '<li><span>Total</span> ' . $record['total_squats'] . '</li>';
    echo '</ul>';
    // Affichage du gainage
    echo '<li><span class = "bld">Gainage</span></li>';
    echo '<ul>';
    echo '<li><span>Durée</span> ' . $record['duree_gainage'] . '</li>';
    echo '</ul>';
    // Affichage des tractions
    echo '<li><span class = "bld">Tractions</span></li>';
    echo '<ul>';
    echo '<li><span>Séries</span> ' . $record['series_tractions'] . '</li>';
    echo '<li><span>Quantité</span> ' . $record['quantite_tractions'] . '</li>';
    echo '<li><span>Total</span> ' . $record['total_tractions'] . '</li>';
    echo '</ul>';
    // Affichage du renforcement musculaire
    echo '<li><span class = "bld">Renforcement musculaire</span></li>';
    echo '<ul>';
    echo '<li><span>Durée</span> ' . $record['duree_renforcement_musculaire'] . '</li>';
    echo '</ul>';
    // Affichage de la boxe
    echo '<li><span class = "bld">Boxe</span></li>';
    echo '<ul>';
    echo '<li><span>Nombre de rounds</span> ' . $record['nombre_rounds_boxe'] . '</li>';
    echo '<li><span>Durée</span> ' . $record['duree_boxe'] . '</li>';
    echo '</ul>';
    // Affichage de la marche
    echo '<li><span class = "bld">Marche</span></li>';
    echo '<ul>';
    echo '<li><span>Nombre de pas</span> ' . $record['nombre_pas'] . '</li>';
    echo '<li><span>Distance</span> ' . $record['distance_marche'] . ' km</li>';
    echo '</ul>';
    echo '</ul>';
    echo '<div class="vegan"></div>';
    echo '</div>';
}