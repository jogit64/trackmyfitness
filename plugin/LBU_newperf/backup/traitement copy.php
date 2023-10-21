<?php



// Vérifier si une requête POST a été soumise
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier si le formulaire de mise à jour de performance pour le vélo elliptique a été soumis
        if (isset($_GET['form']) && $_GET['form'] === 'elliptique') {
            // Récupérer les données du formulaire
            $minutes_elliptique = $_POST['minutes_elliptique'];
            $distance_elliptique = $_POST['distance_elliptique'];
            $calories_elliptique = $_POST['calories_elliptique'];
            $user_id = get_current_user_id();

            // Connexion à la base de données
            $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

            // Récupérer le record à mettre à jour
            $query = $bdd->prepare('SELECT * FROM performances WHERE user_id = :user_id AND created_at = :created_at');
            $query->execute(array(
                'user_id' => $user_id,
                'created_at' => $_POST['created_at']
            ));
            $record = $query->fetch(PDO::FETCH_ASSOC);

            // Préparer la requête de mise à jour de performance pour le vélo elliptique
            $query = $bdd->prepare('UPDATE performances SET minutes_elliptique = :minutes_elliptique, distance_elliptique = :distance_elliptique, calories_elliptique = :calories_elliptique WHERE user_id = :user_id AND created_at = :created_at');

            // Exécuter la requête de mise à jour de performance pour le vélo elliptique
            $query->execute(array(
                'minutes_elliptique' => $minutes_elliptique,
                'distance_elliptique' => $distance_elliptique,
                'calories_elliptique' => $calories_elliptique,
                'user_id' => $user_id,
                'created_at' => $record['created_at']
            ));

            // Rediriger l'utilisateur vers la page d'accueil
            $url = home_url('/performances/');
            header('Location: ' . $url);
            exit();
        }
    }


    // Vérifier si le formulaire de mise à jour de performance pour les pompes a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'pompes') {
        // Récupérer les données du formulaire
        $series_pompes = $_POST['series_pompes'];
        $quantite_pompes = $_POST['quantite_pompes'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour les pompes
        $query = $bdd->prepare('UPDATE performances SET series_pompes = :series_pompes, quantite_pompes = :quantite_pompes WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour les pompes
        $query->execute(array(
            'series_pompes' => $series_pompes,
            'quantite_pompes' => $quantite_pompes,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }


    // Vérifier si le formulaire de mise à jour de performance pour les abdominaux a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'abdominaux') {

        // Récupérer les données du formulaire
        $series_abdominaux = $_POST['series_abdominaux'];
        $quantite_abdominaux = $_POST['quantite_abdominaux'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour les abdominaux
        $query = $bdd->prepare('UPDATE performances SET series_abdominaux = :series_abdominaux, quantite_abdominaux = :quantite_abdominaux WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour les abdominaux
        $query->execute(array(
            'series_abdominaux' => $series_abdominaux,
            'quantite_abdominaux' => $quantite_abdominaux,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }



    // Vérifier si le formulaire de mise à jour de performance pour les squats a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'squat') {
        // Récupérer les données du formulaire
        $series_squats = $_POST['series_squats'];
        $quantite_squats = $_POST['quantite_squats'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour les squats
        $query = $bdd->prepare('UPDATE performances SET series_squats = :series_squats, quantite_squats = :quantite_squats WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour les squats
        $query->execute(array(
            'series_squats' => $series_squats,
            'quantite_squats' => $quantite_squats,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }



    // Vérifier si le formulaire de mise à jour de performance pour le gainage a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'gainage') {
        // Récupérer les données du formulaire
        $duree_gainage = $_POST['duree_gainage'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour le gainage
        $query = $bdd->prepare('UPDATE performances SET duree_gainage = :duree_gainage WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour le gainage
        $query->execute(array(
            'duree_gainage' => $duree_gainage,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }


    // Vérifier si le formulaire de mise à jour de performance pour les tractions a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'traction') {
        // Récupérer les données du formulaire
        $series_tractions = $_POST['series_tractions'];
        $quantite_tractions = $_POST['quantite_tractions'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour les tractions
        $query = $bdd->prepare('UPDATE performances SET series_tractions = :series_tractions, quantite_tractions = :quantite_tractions WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour les tractions
        $query->execute(array(
            'series_tractions' => $series_tractions,
            'quantite_tractions' => $quantite_tractions,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }




    // Vérifier si le formulaire de mise à jour de performance pour la musculation a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'musculation') {
        // Récupérer les données du formulaire
        $duree_renforcement_musculaire = $_POST['duree_renforcement_musculaire'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour la musculation
        $query = $bdd->prepare('UPDATE performances SET duree_renforcement_musculaire = :duree_renforcement_musculaire WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour la musculation
        $query->execute(array(
            'duree_renforcement_musculaire' => $duree_renforcement_musculaire,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }



    // Vérifier si le formulaire de mise à jour de performance pour la boxe a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'boxe') {
        // Récupérer les données du formulaire
        $nombre_rounds_boxe = $_POST['nombre_rounds_boxe'];
        $duree_boxe = $_POST['duree_boxe'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour la boxe
        $query = $bdd->prepare('UPDATE performances SET nombre_rounds_boxe = :nombre_rounds_boxe, duree_boxe = :duree_boxe WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour la boxe
        $query->execute(array(
            'nombre_rounds_boxe' => $nombre_rounds_boxe,
            'duree_boxe' => $duree_boxe,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }



    // Vérifier si le formulaire de mise à jour de performance pour la marche a été soumis
    if (isset($_GET['form']) && $_GET['form'] === 'marche') {
        // Récupérer les données du formulaire
        $nombre_pas = $_POST['nombre_pas'];
        $distance_marche = $_POST['distance_marche'];
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

        // Préparer la requête de mise à jour de performance pour la marche
        $query = $bdd->prepare('UPDATE performances SET nombre_pas = :nombre_pas, distance_marche = :distance_marche WHERE user_id = :user_id AND created_at = :created_at');

        // Exécuter la requête de mise à jour de performance pour la marche
        $query->execute(array(
            'nombre_pas' => $nombre_pas,
            'distance_marche' => $distance_marche,
            'user_id' => $user_id,
            'created_at' => $record['created_at']
        ));

        // Rediriger l'utilisateur vers la page d'accueil
        header('Location: /performances/');
        exit();
    }
}
