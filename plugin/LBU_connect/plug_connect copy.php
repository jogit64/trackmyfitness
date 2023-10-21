<?php
/*
Plugin Name: 0_LBU_connect
Description: Ce plugin 
    1 - redirige les utilisateurs vers la page inscription(connexion) qui tentent d'accèder à une page nécessitant d'être connecté
    2 - ajoute un script de déconnexion dans le pied de page du site (.deconnect) directement vers accueil site
    3 - à la connexion, redirige vers une page spécifique (/nav)
    Version: 1.0
Author: LeBonUnivers
Author URI: https://www.lebonunivers.fr
*/


// * 1 
// Fonction pour vérifier si l'utilisateur est connecté
function check_user_login()
{
    if (!is_user_logged_in()) {
        // Redirection vers la page d'inscription si l'utilisateur n'est pas connecté
        wp_redirect(home_url('inscription'));
        exit;
    }
}

// Ajout des pages à vérifier
function add_private_pages()
{
    $private_pages = array('profil', 'performances', 'tableau-de-bord'); // Remplacez 'page1', 'page2' et 'page3' par les slugs des pages que vous souhaitez protéger
    foreach ($private_pages as $page) {
        add_action("template_redirect", function () use ($page) {
            if (is_page($page)) {
                check_user_login();
            }
        });
    }
}

add_action('init', 'add_private_pages');


// * 2
// Ajout d'un script de déconnexion dans le pied de page du site

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

add_action('wp_footer', 'add_logout_script');


// * 3
function login_redirect()
{
    wp_redirect(home_url('/nav'));
    exit();
}

add_action('wp_login', 'login_redirect');