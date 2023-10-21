<?php

/*
Plugin Name: 0_LBU_plug_graph7daysimcgras
Description: Affiche les dernières mesures d'IMC et de % de gras de l'utilisateur sous forme de graphique.
Version: 1.0
Author: Le Bon Univers
*/

// Crée une fonction pour afficher les données de l'utilisateur connecté
function display_user_imc_gras()
{
    global $wpdb;

    // Récupère l'ID de l'utilisateur connecté
    $user_id = get_current_user_id();

    // Récupère les 7 derniers enregistrements pour les champs "pourcentage_gras" et "nombre_imc", triés par ordre croissant de date
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT pourcentage_gras, nombre_imc, day FROM performances WHERE user_id = %d ORDER BY day DESC LIMIT 7",
            $user_id
        )
    );


    // Transforme les données en format compréhensible pour le graphique
    $chart_data = array(
        'labels' => array(),
        'datasets' => array(
            array(
                'label' => 'Pourcentage de gras (%)',
                'data' => array(),
                'backgroundColor' => '#94B852',
                'borderColor' => '#D0DA65',
                'borderWidth' => 1,
                'barPercentage' => 0.7,
                'borderRadius' => 5,
                'categoryPercentage' => 0.8
            ),
            array(
                'label' => 'Indice de masse corporelle (IMC)',
                'data' => array(),
                'backgroundColor' => '#D0DA65',
                'borderColor' => '#94B852',
                'borderWidth' => 1,
                'barPercentage' => 0.7,
                'borderRadius' => 5,
                'categoryPercentage' => 0.8
            )
        )
    );

    foreach (array_reverse($data) as $row) {
        $date = new DateTime($row->day);
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra'); // Définit la locale pour le français
        $day_name = strftime('%A', $date->getTimestamp());
        $day_name = ucfirst($day_name);
        $month_name = strftime('%B', $date->getTimestamp());
        $month_name = ucfirst($month_name);
        array_push($chart_data['labels'], $day_name . ' ' . $date->format('d ') . $month_name);
        array_push($chart_data['datasets'][0]['data'], $row->pourcentage_gras);
        array_push($chart_data['datasets'][1]['data'], $row->nombre_imc);
    }

    // Affiche le graphique
    echo '<canvas id="user-data-chart-2"></canvas>';

    // Ajoute le script pour afficher le graphique
?>
    <script>
        var chartData = <?php echo json_encode($chart_data); ?>;
        var ctx = document.getElementById("user-data-chart-2");
        var myChart = new Chart(ctx, {
            type: "bar",
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            borderDash: [3, 3],
                            drawBorder: false,
                            zeroLineColor: "#ccc",
                            zeroLineWidth: 1,
                        }
                    }]
                },
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        fontColor: "#333",
                        usePointStyle: true,
                        padding: 20,
                        boxWidth: 12,
                    }
                },
                layout: {
                    padding: {
                        top: 30,
                        left: 20,
                        right: 20,
                        bottom: 20,
                    }
                },
                plugins: {
                    roundedBars: true
                }
            }
        });
    </script>


<?php
}




// Ajoute le shortcode pour insérer le graphique dans une page ou un article
function display_user_imc_gras_shortcode()
{
    ob_start();
    display_user_imc_gras();
    return ob_get_clean();
}

add_shortcode('plug_graph7daysimcgras', 'display_user_imc_gras_shortcode');
