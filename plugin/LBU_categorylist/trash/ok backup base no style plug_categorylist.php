<?php

/**
 * Plugin Name: LBU_plug_categorylist
 * Description: Un plugin elementor pour afficher la liste des catégories d'articles.
 * Version: 1.0
 * Author: Le Bon Univers
 * License: GPL2
 */



// Création de la fonction pour afficher la liste des catégories d'articles
function afficher_categories_articles()
{
    $categories = get_categories(); // Récupération de la liste des catégories

    // Affichage de la liste des catégories
    echo '<ul>';
    foreach ($categories as $category) {
        echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
    }
    echo '</ul>';
}

// Ajout d'un shortcode pour afficher la liste des catégories
function shortcode_categories_articles()
{
    ob_start();
    afficher_categories_articles();
    return ob_get_clean();
}
add_shortcode('categories_articles', 'shortcode_categories_articles');

// Ajout d'un widget pour afficher la liste des catégories d'articles dans Elementor
add_action('elementor/widgets/widgets_registered', function () {
    class Elementor_Categories_Widget extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'categories_articles_widget';
        }

        public function get_title()
        {
            return __('Liste des catégories d\'articles', 'elementor-custom-widget');
        }

        public function get_icon()
        {
            return 'fa fa-list';
        }

        public function get_categories()
        {
            return ['le-bon-univers'];
        }

        protected function _register_controls()
        {
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content', 'elementor-custom-widget'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->end_controls_section();
        }

        protected function render()
        {
            afficher_categories_articles();
        }
    }

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Elementor_Categories_Widget());
});

// Ajout de la catégorie "Le Bon Univers" dans Elementor
add_action('elementor/elements/categories_registered', function ($elements_manager) {
    $elements_manager->add_category(
        'le-bon-univers',
        [
            'title' => __('Le Bon Univers', 'mon-plugin'),
            'icon' => 'fa fa-globe',
        ]
    );
});