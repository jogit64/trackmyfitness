<?php

/*
Plugin Name: 0_LBU_graph7dayspoids.php
Description: Affiche les dernières performances de l'utilisateur sous forme de graphique.
Version: 1.0
Author: Le Bon Univers
*/

// Crée une fonction pour afficher les données de l'utilisateur connecté
function display_user_data()
{
    // Récupère l'ID de l'utilisateur connecté
    $user_id = get_current_user_id();

    // Récupère les 7 derniers enregistrements pour les champs demandés, triés par ordre croissant de date
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT poids_kg, pourcentage_gras, nombre_imc, created_at FROM performances WHERE user_id = %d ORDER BY created_at ASC LIMIT 7",
            $user_id
        )
    );

    // Transforme les données en format compréhensible pour le graphique
    $chart_data = array(
        'labels' => array(),
        'datasets' => array(
            array(
                'label' => 'Poids (kg)',
                'data' => array(),
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1
            ),
            array(
                'label' => 'Pourcentage de graisse',
                'data' => array(),
                'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                'borderColor' => 'rgba(54, 162, 235, 1)',
                'borderWidth' => 1
            ),
            array(
                'label' => 'IMC',
                'data' => array(),
                'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                'borderColor' => 'rgba(255, 206, 86, 1)',
                'borderWidth' => 1
            )
        )
    );

    foreach ($data as $row) {
        array_unshift($chart_data['labels'], date('Y-m-d', strtotime($row->created_at)));
        array_unshift(
            $chart_data['datasets'][0]['data'],
            $row->poids_kg
        );
        array_unshift(
            $chart_data['datasets'][1]['data'],
            $row->pourcentage_gras
        );
        array_unshift(
            $chart_data['datasets'][2]['data'],
            $row->nombre_imc
        );
    }


    // Affiche le graphique
    echo '<canvas id="user-data-chart"></canvas>';

    // Ajoute le script pour afficher le graphique
?>
    <script>
        var chartData = <?php echo json_encode($chart_data); ?>;
        var ctx = document.getElementById("user-data-chart");
        var myChart = new Chart(ctx, {
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
function display_user_data_shortcode()
{
    ob_start();
    display_user_data();
    return ob_get_clean();
}

add_shortcode('graph7dayspoids', 'display_user_data_shortcode');
