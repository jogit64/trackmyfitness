<?php
/*
Plugin Name: 0_LBU_graph7dayspoids
Description: Affiche les dernières mesures de poids de l'utilisateur sous forme de graphique.
Version: 2.0
Author: Le Bon Univers
*/


function graphique_performances_shortcode()
{
    // Définir les paramètres régionaux français
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');


    // Déclarer une variable globale pour le graphique
    $html = '<script>var myChart;</script>';

    // Attendre que le document soit prêt
    $html .= '<script>document.addEventListener("DOMContentLoaded", function() {';




    // Récupérer l'utilisateur connecté
    $user_id = get_current_user_id();

    // Se connecter à la base de données
    $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

    // Récupérer les 7 derniers enregistrements de l'utilisateur connecté, triés par date de création
    $query = $bdd->prepare("SELECT poids_kg, created_at FROM performances WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 7");
    $query->bindParam(':user_id', $user_id);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Créer un tableau de données pour le graphique
    $labels = array();
    $data = array();
    foreach ($results as $result) {
        $labels[] = ucfirst(strftime('%A %d %B', strtotime($result['created_at'])));
        $data[] = $result['poids_kg'];
    }

    // Inverser l'ordre des enregistrements sur l'axe X
    $labels = array_reverse($labels);
    $data = array_reverse($data);


    // Créer un tableau associatif pour la réponse JSON
    $response = array(
        'labels' => $labels,
        'data' => $data
    );

    // Renvoyer la réponse JSON
    header('Content-Type: application/json');
    echo json_encode($response);



    // Générer le code HTML du graphique avec un style personnalisé
    $html = '<canvas id="graphique-performances"></canvas>';
    $html .= '<style>';
    $html .= '.chart-container {';
    $html .= '    position: relative;';
    $html .= '    margin: auto;';
    $html .= '    height: 100%;';
    $html .= '    width: 80%;';
    $html .= '}';
    $html .= '</style>';
    $html .= '<script>';
    $html .= 'var ctx = document.getElementById("graphique-performances").getContext("2d");';
    $html .= 'var myChart = new Chart(ctx, {';
    $html .= '    type: "line",';
    $html .= '    data: {';
    $html .= '        labels: ' . json_encode($labels) . ',';
    $html .= '        datasets: [{';
    $html .= '            label: "Poids (kg)",';
    $html .= '            data: ' . json_encode($data) . ',';
    $html .= '            backgroundColor: "rgba(208, 218, 101, 0.2)",';
    $html .= '            borderColor: "rgba(148, 184, 82, 1)",';
    $html .= '            borderColor: "rgba(148, 184, 82, 1)",';
    $html .= '            borderWidth: 3,';
    $html .= '            pointBackgroundColor: "rgba(208, 218, 101, 1)",';
    $html .= '            pointBorderColor: "rgba(148, 184, 82, 1)",';
    $html .= '            pointBorderWidth: 1,';
    $html .= '            pointRadius: 3,';
    $html .= '            pointHitRadius: 10';
    $html .= '        }]';
    $html .= '    },';
    $html .= '    options: {';
    $html .= '        responsive: true,';
    $html .= '        maintainAspectRatio: false,';
    $html .= '        legend: {';
    $html .= '            display: false';
    $html .= '        },';
    $html .= '        scales: {';
    $html .= '            xAxes: [{';
    $html .= '                ticks: {';
    $html .= '                    fontColor: "#333",';
    $html .= '                    fontStyle: "bold"';
    $html .= '                },';
    $html .= '                gridLines: {';
    $html .= '                    display: false,';
    $html .= '                    drawBorder: false';
    $html .= '                }';
    $html .= '            }],';
    $html .= '            yAxes: [{';
    $html .= '                type: "logarithmic",';
    $html .= '                ticks: {';
    $html .= '                    fontColor: "#333",';
    $html .= '                    fontStyle: "bold",';
    $html .= '                    min: 40,';
    $html .= '                    max: 100,';
    $html .= '                    callback: function(value, index, values) {';
    $html .= '                        return Number(value.toString());';
    $html .= '                    }';
    $html .= '                },';
    $html .= '                gridLines: {';
    $html .= '                    color: "#ccc",';
    $html .= '                    borderDash: [2, 5],';
    $html .= '                    zeroLineColor: "#ccc",';
    $html .= '                    zeroLineBorderDash: [2, 5]';
    $html .= '                }';
    $html .= '            }]';
    $html .= '        },';
    $html .= '        tooltips: {';
    $html .= '            backgroundColor: "#fff",';
    $html .= '            titleFontColor: "#333",';
    $html .= '            titleFontStyle: "bold",';
    $html .= '            bodyFontColor: "#333",';
    $html .= '            bodyFontStyle: "bold",';
    $html .= '            borderColor: "#ccc",';
    $html .= '            borderWidth: 1,';
    $html .= '            callbacks: {';
    $html .= '                title: function(tooltipItem, data) {';
    $html .= '                    return data.labels[tooltipItem[0].index];';
    $html .= '                },';
    $html .= '                label: function(tooltipItem, data) {';
    $html .= '                    return "Poids: " + tooltipItem.yLabel + " kg";';
    $html .= '                }';
    $html .= '            }';
    $html .= '        }';
    $html .= '    }';


    // Sélectionner le formulaire et ajouter un écouteur d'événements pour la soumission
    $html .= '    var form = document.getElementById("independent-form");';
    $html .= '    form.addEventListener("submit", function(event) {';
    $html .= '        // Empêcher la soumission du formulaire normalement';
    $html .= '        event.preventDefault();';

    // Exécuter une requête AJAX pour récupérer les nouvelles données
    $html .= '        var xhr = new XMLHttpRequest();';
    $html .= '        xhr.open("GET", "https://monsport.lebonunivers.fr/wp-content/plugins/LBU_recordpoids/plug_recordpoids.php", true);';
    $html .= '        xhr.onload = function() {';
    $html .= '            // Extraire les données JSON de la réponse';
    $html .= '            var response = JSON.parse(xhr.responseText);';
    $html .= '            var labels = response.labels;';
    $html .= '            var data = response.data;';

    // Mettre à jour le graphique avec les nouvelles données
    $html .= '            myChart.data.labels = labels;';
    $html .= '            myChart.data.datasets[0].data = data;';
    $html .= '            myChart.update();';
    $html .= '        };';
    $html .= '        xhr.send();';
    $html .= '    });';





    // $html .= '});';
    $html .= '</script>';


    // Retourner le code HTML du graphique
    return $html;
}
add_shortcode('plug_graph7dayspoids', 'graphique_performances_shortcode');
