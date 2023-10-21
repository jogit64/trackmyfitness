<?php
/*
Plugin Name: 0_LBU_httpsconnect
Description: Ce plugin redirige les utilisateurs qui se connectent/déconectent vers une page spécifique
Version: 1.0
Author: LeBonUnivers
Author URI: https://www.lebonunivers.fr
*/

// * Ajout d'un script de déconnexion dans le pied de page du site
add_action('wp_footer', 'add_logout_script');
function add_logout_script()
{
?>
<script>
// Lorsque l'utilisateur clique sur le lien de déconnexion, il est redirigé vers la page de déconnexion
jQuery(function($) {
    $('.deconnect').click(function(e) {
        e.preventDefault();
        <?php wp_logout(); ?>
        var homeUrl = '<?php echo home_url(); ?>';
        window.location.href = homeUrl;
    });
});
</script>
<?php
}

add_action('wp_login', 'login_redirect');

function login_redirect()
{
    wp_redirect(home_url('/nav'));
    exit();
}