<?php

/*
Plugin Name: 0_LBU_graph7dayspoids.php
Description: Affiche les dernières performances de l'utilisateur sous forme de graphique.
Version: 1.0
Author: Le Bon Univers
*/


function afficher_donnees_utilisateur_connecte()
{
    $dsn = 'mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8';
    $username = 'lebonubjo';
    $password = 'Baltimore69';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    try {
        $dbh = new PDO($dsn, $username, $password, $options);
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
        exit;
    }

    $user_id = get_current_user_id();

    $stmt = $dbh->prepare("SELECT poids_kg, pourcentage_gras, nombre_imc FROM performances WHERE user_id = :user_id ORDER BY
created_at DESC LIMIT 7");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $resultats = $stmt->fetchAll(PDO::FETCH_OBJ);
    $poids_kg = array();
    $pourcentage_gras = array();
    $nombre_imc = array();
    foreach ($resultats as $resultat) {
        array_push($poids_kg, $resultat->poids_kg);
        array_push($pourcentage_gras, $resultat->pourcentage_gras);
        array_push($nombre_imc, $resultat->nombre_imc);
    }
    echo "Vous êtes utilisateur_" . get_current_user_id();
?>
    <div>
        <canvas id="graphique_donnees_utilisateur_connecte"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var donnees_utilisateur_connecte = {
            poids_kg: <?php echo json_encode($poids_kg); ?>,
            pourcentage_gras: <?php echo json_encode($pourcentage_gras); ?>,
            nombre_imc: <?php echo json_encode($nombre_imc); ?>
        };

        var ctx = document.getElementById('graphique_donnees_utilisateur_connecte').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Enregistrement 1', 'Enregistrement 2', 'Enregistrement 3', 'Enregistrement 4',
                    'Enregistrement 5', 'Enregistrement 6', 'Enregistrement 7'
                ],
                datasets: [{
                        label: 'Poids (kg)',
                        data: donnees_utilisateur_connecte.poids_kg,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: 'Pourcentage de gras',
                        data: donnees_utilisateur_connecte.pourcentage_gras,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: 'IMC',
                        data: donnees_utilisateur_connecte.nombre_imc,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script><?php
            }

            add_shortcode('graph7dayspoids', 'afficher_donnees_utilisateur_connecte');
