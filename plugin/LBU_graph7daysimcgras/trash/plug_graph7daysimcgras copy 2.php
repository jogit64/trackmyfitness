<?php

/*
Plugin Name: 0_LBU_plug_graph7daysimcgras.php
Description: Affiche les dernières mesures d'IMC et de % de gras de l'utilisateur sous forme de graphique.
Version: 1.0
Author: Le Bon Univers
*/

// Alias pour éviter les conflits avec Chart.js
if (!function_exists('myplugin_chart')) {
    function myplugin_chart()
    {
        return call_user_func_array(array('Chart', '__callStatic'), func_get_args());
    }
}

// Alias pour éviter les conflits avec DateTime de PHP
use DateTime as MyPluginDateTime;

// Crée une fonction pour afficher les données de l'utilisateur connecté
function display_user_imc_gras()
{
    global $wpdb;

    // Récupère l'ID de l'utilisateur connecté
    $user_id = get_current_user_id();

    // Récupère les 7 derniers enregistrements pour les champs "pourcentage_gras" et "nombre_imc", triés par ordre croissant de date
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT pourcentage_gras, nombre_imc, created_at FROM performances WHERE user_id = %d ORDER BY created_at DESC LIMIT 7",
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
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1
            ),
            array(
                'label' => 'Indice de masse corporelle (IMC)',
                'data' => array(),
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            )
        )
    );

    foreach (array_reverse($data) as $row) {
        $date = new MyPluginDateTime($row->created_at);
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
    echo '<canvas id="user-data-chart"></canvas>';

    // Ajoute le script pour afficher le graphique
?>
    <script>
        var chartData = <?php echo json_encode($chart_data); ?>;
        var ctx = document.getElementById("user-data-chart");
        var myChart = new myplugin_chart(ctx, {
            type: "line",
            data: chartData,
            options: {
                responsive: true,
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
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
