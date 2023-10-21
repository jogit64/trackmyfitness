<?php
/*
Plugin Name: 0_LBU_plug_graph7daysimcgras
Description: Affiche les dernières mesures d'IMC et de % de gras de l'utilisateur sous forme de graphique.
Version: 1.1
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

    // Générer le code HTML et JavaScript du graphique avec un style personnalisé
    $html = '<canvas id="user-data-chart-2" class="heightgraph"></canvas>';
    $html .= '<style>';
    $html .= '.chart-container {';
    $html .= ' position: relative;';
    $html .= ' margin: auto;';
    $html .= ' height: 100%;';
    $html .= ' width: 80%;';
    $html .= '}';
    $html .= '</style>';

    $html .= '<script>';
    $html .= 'var chartData = ' . json_encode($chart_data) . ';';
    $html .= 'var ctx = document.getElementById("user-data-chart-2").getContext("2d");';
    $html .= 'var myChart = new Chart(ctx, {';
    $html .= ' type: "bar",';
    $html .= ' data: chartData,';
    $html .= ' options: {';
    $html .= ' responsive: true,';
    $html .= ' scales: {';
    $html .= ' xAxes: [{';
    $html .= ' ticks: {';
    $html .= ' beginAtZero: true';
    $html .= ' },';
    $html .= ' gridLines: {';
    $html .= ' display: false';
    $html .= ' }';
    $html .= ' }],';
    $html .= ' yAxes: [{';
    $html .= ' ticks: {';
    $html .= ' beginAtZero: true';
    $html .= ' },';
    $html .= ' gridLines: {';
    $html .= ' borderDash: [3, 3],';
    $html .= ' drawBorder: false,';
    $html .= ' zeroLineColor: "#F5F9FC",';
    $html .= ' zeroLineWidth: 1,';
    $html .= ' color: "#F5F9FC",';
    $html .= ' }';
    $html .= ' }]';
    $html .= ' },';
    $html .= ' legend: {';
    $html .= ' display: true,';
    $html .= ' position: "bottom",';
    $html .= ' labels: {';
    $html .= ' fontColor: "#F5F9FC",';
    $html .= ' usePointStyle: true,';
    $html .= ' padding: 20,';
    $html .= ' boxWidth: 12,';
    $html .= ' }';
    $html .= ' },';
    $html .= ' layout: {';
    $html .= ' padding: {';
    $html .= ' top: 30,';
    $html .= ' left: 20,';
    $html .= ' right: 20,';
    $html .= ' bottom: 20,';
    $html .= ' }';
    $html .= ' },';
    $html .= ' plugins: {';
    $html .= ' roundedBars: true';
    $html .= ' }';
    $html .= ' }';
    $html .= '});';
    $html .= '</script>';

    // Retourner le code HTML du graphique
    return $html;
}

add_shortcode('plug_graph7daysimcgras', 'display_user_imc_gras');