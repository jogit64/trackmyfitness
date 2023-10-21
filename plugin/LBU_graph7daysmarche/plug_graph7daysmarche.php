<?php
/*
Plugin Name: 0_LBU_graph7daysmarche
Description: Affiche les dernières mesures de marche de l'utilisateur sous forme de graphique.
Version: 1.0
Author: Le Bon Univers
*/

function graphique_marche_shortcode()
{
    // Définir les paramètres régionaux français
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    // Récupérer l'utilisateur connecté
    $user_id = get_current_user_id();

    // Se connecter à la base de données
    $bdd = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');

    // Récupérer les 7 derniers enregistrements de l'utilisateur connecté, triés par date de création
    $query = $bdd->prepare("SELECT nombre_pas, distance_marche, day FROM performances WHERE user_id = :user_id ORDER BY day DESC LIMIT 7");
    $query->bindParam(':user_id', $user_id);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Créer un tableau de données pour le graphique
    $labels = array();
    $data_pas = array();
    $data_distance = array();
    foreach ($results as $result) {
        $labels[] = ucfirst(strftime('%A %d %B', strtotime($result['day'])));
        $data_pas[] = $result['nombre_pas'];
        $data_distance[] = $result['distance_marche'];
    }

    // Inverser l'ordre des enregistrements sur l'axe X
    $labels = array_reverse($labels);
    $data_pas = array_reverse($data_pas);
    $data_distance = array_reverse($data_distance);

    $html = '<div class="chart-container">';
    $html .= '<canvas id="graphique-marche" class="graph"></canvas>';
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
    $html .= 'var ctx = document.getElementById("graphique-marche").getContext("2d");';
    $html .= 'var myChart = new Chart(ctx, {';
    $html .= '    type: "bar",';
    $html .= '    data: {';
    $html .= '        labels: ' . json_encode($labels) . ',';
    $html .= '        datasets: [';




    $html .= '            {';
    $html .= '                label: "Nombre de pas",';
    $html .= '                data: ' . json_encode($data_pas) . ',';
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
    $html .= '                label: "Distance de marche (km)",';
    $html .= '                data: ' . json_encode($data_distance) . ',';
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


    $html .= '    options: {';
    $html .= '        responsive: true,';
    $html .= '        maintainAspectRatio: false,';
    $html .= '        plugins: {';
    $html .= '            legend: {';
    $html .= '                display: true,';
    $html .= '                labels: {';
    $html .= '                    color: "#f0f0f0",';
    $html .= '                    fontStyle: "bold"';

    $html .= '                }';
    $html .= '            }';
    $html .= '        },';

    $html .= '     scales: {';
    $html .= '         x: {';
    $html .= '             ticks: {';
    $html .= '                 color: "#f0f0f0",';
    $html .= '                 fontStyle: "bold"';
    $html .= '             },';
    $html .= '             grid: {';
    $html .= '                 display: false,';
    $html .= '                 drawBorder: false';
    $html .= '             }';
    $html .= '         },';
    $html .= '         y: {';
    $html .= '             ticks: {';
    $html .= '                 color: "#f0f0f0",';
    $html .= '                 fontStyle: "bold"';
    $html .= '             },';
    $html .= '             grid: {';
    $html .= '                 color: "#ccc",';
    $html .= '                 borderDash: [2, 5],';
    $html .= '                 zeroLineColor: "#ccc",';
    $html .= '                 zeroLineBorderDash: [2, 5]';
    $html .= '             }';
    $html .= '         }';
    $html .= '     },';



    $html .= ' tooltips: {';
    $html .= ' backgroundColor: "#fff",';
    $html .= ' titleFontColor: "#333",';
    $html .= ' titleFontStyle: "bold",';
    $html .= ' bodyFontColor: "#333",';
    $html .= ' bodyFontStyle: "bold",';
    $html .= ' borderColor: "#ccc",';
    $html .= ' borderWidth: 1,';
    $html .= ' borderRadius: 5,';
    $html .= ' callbacks: {';
    $html .= ' title: function(tooltipItem, data) {';
    $html .= ' return data.labels[tooltipItem[0].index];';
    $html .= ' },';
    $html .= ' label: function(tooltipItem, data) {';
    $html .= ' var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];';
    $html .= ' var label = data.datasets[tooltipItem.datasetIndex].label;';
    $html .= ' if (label === "Nombre de pas") {';
    $html .= ' return label + ": " + value + " pas";';
    $html .= ' } else {';
    $html .= ' return label + ": " + value.toFixed(2) + " km";';
    $html .= ' }';
    $html .= ' }';
    $html .= ' }';
    $html .= ' }';
    $html .= ' }';
    $html .= '});';
    $html .= '</script>';




    // Retourner le code HTML du graphique
    return $html;
}

add_shortcode('plug_graph7daysmarche', 'graphique_marche_shortcode');
