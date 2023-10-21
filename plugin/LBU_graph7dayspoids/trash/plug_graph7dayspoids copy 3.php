<?php

/*
Plugin Name: 0_LBU_graph7dayspoids
Description: Affiche les dernières mesures de poids de l'utilisateur sous forme de graphique.
Version: 1.0
Author: Le Bon Univers
*/

// Crée une fonction pour afficher les données de l'utilisateur connecté
function display_user_poids()
{
    global $wpdb;

    // Récupère l'ID de l'utilisateur connecté
    $user_id = get_current_user_id();

    // Récupère les 7 derniers enregistrements pour le champ "poids_kg", triés par ordre croissant de date
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT poids_kg, created_at FROM performances WHERE user_id = %d ORDER BY created_at DESC LIMIT 7",
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
                'backgroundColor' => 'rgba(200, 200, 200, 0.2)',
                'borderColor' => 'rgba(100, 100, 100, 1)',
                'borderWidth' => 2,
                'borderRadius' => 10
            )
        )
    );

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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

// Ajoute le code pour rafraîchir le graphique avec AJAX lorsque le formulaire est soumis
jQuery("#form_id").submit(function(e) {

    // Empêche le formulaire de se soumettre normalement
    e.preventDefault();

    // Envoie les données du formulaire au serveur avec AJAX
    jQuery.ajax({
        url: "<?php echo admin_url('admin-ajax.php'); ?>",
        type: "post",
        data: {
            action: "update_user_poids",
            poids: jQuery("#poids_input").val(),
        },
        success: function(response) {
            // Met à jour les données du graphique avec la réponse reçue
            chartData = JSON.parse(response);
            myChart.data = chartData;
            myChart.update();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Affiche une erreur en cas d'échec de la requête AJAX
            alert("Erreur : " + textStatus);
        }
    });
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

// Crée une fonction AJAX pour mettre à jour les données de poids de l'utilisateur
function update_user_poids()
{
    global $wpdb;

    // Récupère l'ID de l'utilisateur connecté
    $user_id = get_current_user_id();

    // Récupère les 7 derniers enregistrements pour le champ "poids_kg", triés par ordre croissant de date
    $data = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT poids_kg, created_at FROM performances WHERE user_id = %d ORDER BY created_at DESC LIMIT 7",
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
                'backgroundColor' => 'rgba(200, 200, 200, 0.2)',
                'borderColor' => 'rgba(100, 100, 100, 1)',
                'borderWidth' => 2,
                'borderRadius' => 10
            )
        )
    );

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

    // Retourne les données du graphique au format JSON
    echo json_encode($chart_data);

    // Arrête l'exécution du script après avoir envoyé la réponse
    wp_die();
}

// Ajoute l'action AJAX pour la fonction de mise à jour du poids utilisateur
add_action('wp_ajax_update_user_poids', 'update_user_poids');

// Ajoute le script pour définir les variables de la requête AJAX
function add_ajax_script()
{
?>
<script>
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
var chartData = <?php echo json_encode($chart_data); ?>;
</script>
<?php
}

// Ajoute l'action pour ajouter le script aux pages qui affichent le graphique
add_action('wp_head', 'add_ajax_script');