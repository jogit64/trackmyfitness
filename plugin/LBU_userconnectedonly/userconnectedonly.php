<?php
/*
Plugin Name: 0_LBU_userconnectedonly
Description: Ce plugin redirige les utilisateurs non connectés vers une page d'inscription lorsqu'ils tentent d'accéder à certaines pages.
Version: 1.0
Author: LeBonUnivers
Author URI: https://www.lebonunivers.fr
*/

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
