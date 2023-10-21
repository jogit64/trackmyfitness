<?php

/*
Plugin Name: 0_LBU_plug_recordpoidsandreloadpage
Description: Enregistre les données de poids du formulaire, et utilise la technique de redirection "Post/Redirect/Get" (ou PRG) pour reload de la page avec graphiques mis à jour.
Version: 1.0
Author: Le Bon Univers
*/

function actualiser_graphique_apres_soumission_form()
{
?>
<script>
jQuery(document).ready(function() {
    jQuery('form#record_poids_form').submit(function() {
        //  alert('Formulaire soumis ffccc chouette');
        var form_data = jQuery(this).serialize(); // Récupérer les données du formulaire
        jQuery.post(
            '<?php echo admin_url('admin-ajax.php'); ?>', // URL pour le traitement AJAX
            {
                'action': 'enregistrer_donnees_form',
                'form_data': form_data
            },
            function(response) {
                if (response.success) {
                    // Supprimer la redirection par défaut
                    jQuery('form#record_poids_form').off('submit');
                    // Exécuter la redirection après l'enregistrement des données
                    window.location.href =
                        "http://www.trackmyfitness.fr/wp-content/plugins/LBU_recordpoidsandreloadpage/plug_recordpoidsandreloadpage_c.php";
                }
            }
        );
        return false; // Empêche la soumission normale du formulaire
    });
});
</script>
<?php
}

add_action('wp_footer', 'actualiser_graphique_apres_soumission_form');

// Fonction pour enregistrer les données du formulaire
function enregistrer_donnees_form()
{
    if (isset($_POST['form_data']) && is_user_logged_in()) {
        // Récupération de l'ID de l'utilisateur connecté à partir du champ caché user_id
        $user_id = get_current_user_id();

        // Connexion à la base de données
        $pdo = new PDO('mysql:host=lebonubjo.mysql.db;dbname=lebonubjo;charset=utf8', 'lebonubjo', 'Baltimore69');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupération des données du formulaire
        parse_str($_POST['form_data'], $form_data);
        $poids_kg = isset($form_data['poids_kg']) ? sanitize_text_field($form_data['poids_kg']) : '';
        $pourcentage_gras = isset($form_data['pourcentage_gras']) ? sanitize_text_field($form_data['pourcentage_gras']) : '';
        $nombre_imc = isset($form_data['nombre_imc']) ? sanitize_text_field($form_data['nombre_imc']) : '';
        $date = isset($form_data['date']) ? sanitize_text_field($form_data['date']) : '';


        // Préparation de la requête SQL pour enregistrer le poids de l'utilisateur
        $stmt = $pdo->prepare("INSERT INTO performances (user_id, poids_kg, pourcentage_gras, nombre_imc, day) VALUES (:user_id, :poids_kg, :pourcentage_gras, :nombre_imc, :date)");

        // Exécution de la requête SQL en passant les valeurs des champs du formulaire en paramètres
        $stmt->execute(array(
            ':user_id' => $user_id,
            ':poids_kg' => $poids_kg,
            ':pourcentage_gras' => $pourcentage_gras,
            ':nombre_imc' => $nombre_imc,
            ':date' => $date
        ));


        // Fermeture de la connexion à la base de données
        $pdo = null;

        // Renvoyer une réponse JSON pour confirmer l'enregistrement des données
        wp_send_json_success();
    }
}

add_action('wp_ajax_enregistrer_donnees_form', 'enregistrer_donnees_form');
add_action('wp_ajax_nopriv_enregistrer_donnees_form', 'enregistrer_donnees_form');

// Supprimer la redirection par défaut lors de la soumission du formulaire
function supprimer_redirection_form()
{
    if (isset($_POST['enregistrer_performances']) && is_user_logged_in()) {
        remove_action('wp_footer', 'actualiser_graphique_apres_soumission_form');
    }
}

add_action('init', 'supprimer_redirection_form');