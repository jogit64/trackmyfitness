<?php
/*
Plugin Name: 0_LBU_graph7daysvelo
Description: Affiche les dernières mesures de vélo de l'utilisateur sous forme de graphique.
Version: 1.0
Author: Le Bon Univers
*/


function graphique_velo_shortcode()
{
    // Définir les paramètres régionaux français
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    // Récupérer l'utilisateur connecté
    $user_id = get_current_user_id();

    // Se connecter à la base de données
    $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

    // Récupérer les 7 derniers enregistrements de l'utilisateur connecté, triés par date de création
    $query = $bdd->prepare("SELECT minutes_elliptique, distance_elliptique, calories_elliptique, day FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 7");
    $query->bindParam(':user_id', $user_id);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Créer un tableau de données pour le graphique
    $labels = array();
    $data_minutes = array();
    $data_distance = array();
    $data_calories = array();
    foreach ($results as $result) {
        $labels[] = ucfirst(strftime('%A %d %B', strtotime($result['day'])));
        $data_minutes[] = $result['minutes_elliptique'];
        $data_distance[] = $result['distance_elliptique'];
        $data_calories[] = $result['calories_elliptique'];
    }

    // Inverser l'ordre des enregistrements sur l'axe X
    $labels = array_reverse($labels);
    $data_minutes = array_reverse($data_minutes);
    $data_distance = array_reverse($data_distance);
    $data_calories = array_reverse($data_calories);

    $html = '<div class="chart-container">';
    $html .= '<canvas id="graphique-velo" class="graph"></canvas>';
    $html .= '</div>';
    $html .= '<style>';
    $html .= '.chart-container {';
    $html .= '    position: relative;';
    $html .= '    margin: auto;';
    $html .= '    height: 400px;';
    $html .= '    width: 80%;';
    $html .= '    border-radius: 10px;';
    $html .= '    box-shadow: 0 4px 10px rgba(0,0,0,.1);';
    $html .= '}';
    $html .= '</style>';
    $html .= '<script>';
    $html .= 'var ctx = document.getElementById("graphique-velo").getContext("2d");';
    $html .= 'var myChart = new Chart(ctx, {';
    $html .= '    type: "bar",';
    $html .= '    data: {';
    $html .= '        labels: ' . json_encode($labels) . ',';
    $html .= '        datasets: [';

    $html .= '            {';
    $html .= '                label: "Minutes elliptique",';
    $html .= '                data: ' . json_encode($data_minutes) . ',';
    $html .= '                backgroundColor: "#D0DA65",';
    $html .= '                borderColor: "#D0DA65",';
    $html .= '                borderWidth: 3,';
    $html .= '                borderRadius: 5,';
    $html .= '                pointBackgroundColor: "#D0DA65",';
    $html .= '                pointBorderColor: "#D0DA65",';
    $html .= '                pointBorderWidth: 1,';
    $html .= '                pointRadius: 3,';
    $html .= '                pointHitRadius: 10';
    $html .= '            },';
    $html .= '            {';
    $html .= '                label: "Distance elliptique (km)",';
    $html .= '                data: ' . json_encode($data_distance) . ',';
    $html .= '                backgroundColor: "#94B852",';
    $html .= '                borderColor: "#94B852",';
    $html .= '                borderWidth: 3,';
    $html .= '                borderRadius: 5,';
    $html .= '                pointBackgroundColor: "#94B852",';
    $html .= '                pointBorderColor: "#94B852",';
    $html .= '                pointBorderWidth: 1,';
    $html .= '                pointRadius: 3,';
    $html .= '                pointHitRadius: 10';
    $html .= '            },';
    $html .= '            {';
    $html .= '                label: "Calories elliptique",';
    $html .= '                data: ' . json_encode($data_calories) . ',';
    $html .= '                backgroundColor: "#D0DA65",';
    $html .= '                borderColor: "#D0DA65",';
    $html .= '                borderWidth: 3,';
    $html .= '                borderRadius: 5,';
    $html .= '                pointBackgroundColor: "#D0DA65",';
    $html .= '                pointBorderColor: "#D0DA65",';
    $html .= '                pointBorderWidth: 1,';
    $html .= '                pointRadius: 3,';
    $html .= '                pointHitRadius: 10';
    $html .= '            }';
    $html .= '        ]';
    $html .= '    },';

    $html .= ' options: {';
    $html .= '     responsive: true,';
    $html .= '     maintainAspectRatio: false,';

    $html .= '     plugins: {';
    $html .= '         legend: {';
    $html .= '             display: true,';
    $html .= '             labels: {';
    $html .= '                 color: "#FFF",';
    $html .= '                 font: {';
    $html .= ' color: "#ccc",';
    $html .= ' borderDash: [2, 5],';
    $html .= ' zeroLineColor: "#ccc",';
    $html .= ' zeroLineBorderDash: [2, 5]';
    $html .= ' }';
    $html .= ' }';
    $html .= ' }';
    $html .= ' },';


    $html .= '        plugins: {';
    $html .= '            legend: {';
    $html .= '                display: true,';
    $html .= '                labels: {';
    $html .= '                   color: "#f0f0f0",';
    $html .= '                    fontStyle: "bold"';
    $html .= '                }';
    $html .= '            },';
    $html .= '            tooltip: {';
    $html .= '                backgroundColor: "#000",';
    $html .= '                titleFontColor: "#333",';
    $html .= '                titleFontStyle: "bold",';
    $html .= '                bodyFontColor: "#333",';
    $html .= '                bodyFontStyle: "bold",';
    $html .= '                borderColor: "#ccc",';
    $html .= '                borderWidth: 1,';
    $html .= '                borderRadius: 5,';
    $html .= '                callbacks: {';
    $html .= '                    title: function(tooltipItem, data) {';
    $html .= '                        return data.labels[tooltipItem[0].dataIndex];';
    $html .= '                    },';
    $html .= '                    label: function(tooltipItem, data) {';
    $html .= '                        return data.datasets[tooltipItem.datasetIndex].label + ": " + tooltipItem.yLabel;';
    $html .= '                    }';
    $html .= '                }';
    $html .= '            }';
    $html .= '        },';

    $html .= '        scales: {';
    $html .= '            x: {';
    $html .= '                ticks: {';
    $html .= '                    color: "#f0f0f0",';
    $html .= '                    fontStyle: "bold"';
    $html .= '                },';
    $html .= '                grid: {';
    $html .= '                    display: false,';
    $html .= '                    drawBorder: false';
    $html .= '                }';
    $html .= '            },';
    $html .= '            y: {';
    $html .= '                ticks: {';
    $html .= '                    color: "#f0f0f0",';
    $html .= '                    fontStyle: "bold"';
    $html .= '                },';
    $html .= '                grid: {';
    $html .= '                    color: "#ccc",';
    $html .= '                    borderDash: [2, 5],';
    $html .= '                    zeroLineColor: "#ccc",';
    $html .= '                    zeroLineBorderDash: [2, 5]';
    $html .= '                }';
    $html .= '            }';
    $html .= '        }';

    $html .= '    }';
    $html .= '});';
    $html .= '</script>';

    return $html;
}

add_shortcode('plug_graph7daysvelo', 'graphique_velo_shortcode');
