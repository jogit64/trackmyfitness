<?php

/*
Plugin Name: 0_LBU_graph7dayspoids
Description: Affiche les dernières mesures de poids de l'utilisateur sous forme de graphique.
Version: 2.0
Author: Le Bon Univers
*/

// Fonction pour afficher les données de l'utilisateur connecté
function display_user_poids()
{
    global $wpdb;
    $user_id = get_current_user_id();

    // Transforme les données en format compréhensible pour le graphique
    $chart_data = array(
        'labels' => array(),
        'datasets' => array(
            array(
                'label' => 'Poids (kg)',
                'data' => array(),
                'backgroundColor' => 'rgba(200, 200, 200, 0.2)',
                'borderColor' => 'rgba(100, 100, 100, 1)',
                'borderWidth' => 2,
                'borderRadius' => 10
            )
        )
    );

    // Récupère les données via Ajax
    $data = json_decode(wp_remote_get(admin_url('admin-ajax.php?action=get_user_poids&user_id=' . $user_id))['body']);

    foreach (array_reverse($data) as $row) {
        $date = new DateTime($row->created_at);
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra'); // Définit la locale pour le français
        $day_name = strftime('%A', $date->getTimestamp());
        $day_name = ucfirst($day_name);
        $month_name = strftime('%B', $date->getTimestamp());
        $month_name = ucfirst($month_name);
        array_push($chart_data['labels'], $day_name . ' ' . $date->format('d ') . $month_name);
        array_push($chart_data['datasets'][0]['data'], $row->poids_kg);
    }

    // Affiche le graphique
    echo '<canvas id="user-data-chart-1"></canvas>';

    // Ajoute le script pour afficher le graphique
?>
<script>
jQuery(document).ready(function($) {
    var chartData = <?php echo json_encode($chart_data); ?>;
    var ctx = document.getElementById("user-data-chart-1");
    var myChart = new Chart(ctx, {
        type: "line",
        data: chartData,
        options: {
            responsive: true,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#999'
                    },
                    gridLines: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#999'
                    },
                    gridLines: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    }
                }]
            },
            legend: {
                labels: {
                    fontColor: '#999'
                }
            }
        }
    });

    // Fonction pour mettre à jour les données du graphique via Ajax
    function updateChart() {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'post',
            data: {
                action: 'update_user_poids'
            },
            success: function(response) {
                chartData = JSON.parse(response);
                myChart.data.labels = chartData.labels;
                myChart.data.datasets[0].data = chartData.datasets[0].data;
                myChart.update();
            }
        });
    }
    // Appelle la fonction updateChart toutes les 1 seconde pour mettre à jour les données du graphique
    setInterval(updateChart, 1000);
});
</script>

<?php
}

// Ajoute le shortcode pour insérer le graphique dans une page ou un article
function display_user_poids_shortcode()
{
    ob_start();
    display_user_poids();
    return ob_get_clean();
}
add_shortcode('plug_graph7dayspoids', 'display_user_poids_shortcode');

// Fonction pour mettre à jour les données de l'utilisateur dans la base de données
function update_user_poids()
{
    global $wpdb;
    $user_id = get_current_user_id();
    $poids_kg = $_POST['poids_kg'];
    $date = date('Y-m-d H:i:s');
    $wpdb->insert('performances', array(
        'user_id' => $user_id,
        'poids_kg' => $poids_kg,
        'created_at' => $date
    ));
    // Récupère les données mises à jour et les renvoie au format JSON
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT poids_kg, created_at FROM performances WHERE user_id = %d ORDER BY created_at DESC LIMIT 7",
            $user_id
        )
    );
    $chart_data = array(
        'labels' => array(),
        'datasets' => array(
            array(
                'label' => 'Poids (kg)',
                'data' => array(),
                'backgroundColor' => 'rgba(200, 200, 200, 0.2)',
                'borderColor' => 'rgba(100, 100, 100, 1)',
                'borderWidth' => 2,
                'borderRadius' => 10
            )
        )
    );
    foreach (array_reverse($data) as $row) {
        $date = new DateTime($row->created_at);
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $day_name = strftime('%A', $date->getTimestamp());
        $day_name = ucfirst($day_name);
        $month_name = strftime('%B', $date->getTimestamp());
        $month_name = ucfirst($month_name);
        array_push($chart_data['labels'], $day_name . ' ' . $date->format('d ') . $month_name);
        array_push($chart_data['datasets'][0]['data'], $row->poids_kg);
    }
    echo json_encode($chart_data);
    die();
}
add_action('wp_ajax_update_user_poids', 'update_user_poids');

// Fonction pour récupérer les données de l'utilisateur depuis la base de données
function get_user_poids()
{
    global $wpdb;
    $user_id = $_GET['user_id'];
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT poids_kg, created_at FROM performances WHERE user_id = %d ORDER BY created_at DESC LIMIT 7",
            $user_id
        )
    );
    echo json_encode($data);
    die();
}
add_action('wp_ajax_get_user_poids', 'get_user_poids');