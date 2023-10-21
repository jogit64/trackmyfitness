<?php

/**
 * Plugin Name: LBU_plug_categorylist
 * Description: Un plugin Elementor pour afficher la liste des catégories d'articles.
 * Version: 1.1
 * Author: Le Bon Univers
 * License: GPL2
 */

// Création de la fonction pour afficher la liste des catégories d'articles
function afficher_categories_articles()
{
    $categories = get_categories(); // Récupération de la liste des catégories

    // Début de la liste des catégories avec une classe CSS personnalisable
    echo '<ul class="ma-classe-css-personnalisable">';

    foreach ($categories as $category) {

        // Définition de variables pour la personnalisation de l'affichage
        $lien = get_category_link($category->term_id);
        $nom = $category->name;
        $style = 'color: #000; font-size: 16px;'; // Style CSS personnalisable
        $marge = '0 0 10px 0'; // Marges CSS personnalisables

        // Début de la ligne de catégorie avec des classes CSS personnalisables
        echo '<li class="ma-classe-css-personnalisable">';
        echo '<a href="' . $lien . '">' . $nom . '</a>';
        echo '</li>';
    }

    // Fin de la liste des catégories
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
            // Ajout de sections pour personnaliser l'affichage du widget
            $this->start_controls_section(
                'content_section',
                [
                    'label' => __('Content', 'elementor-custom-widget'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'font_family',
                [
                    'label' => __('Police', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::FONT,
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a' => 'font-family: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'font_size',
                [
                    'label' => __('Taille de police', 'elementor-custom-widget'),
                    'type' =>
                    \Elementor\Controls_Manager::SLIDER,
                    'default' => [
                        'unit' => 'px',
                        'size' => 16,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'text_color',
                [
                    'label' => __('Couleur du texte', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'link_color',
                [
                    'label' => __('Couleur des liens', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#000000',
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'background_color',
                [
                    'label' => __('Couleur de fond', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'padding',
                [
                    'label' => __('Marge intérieure', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'margin',
                [
                    'label' => __('Marge extérieure', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );


            $this->add_control(
                'line_height',
                [
                    'label' => __('Hauteur de ligne', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'default' => [
                        'unit' => 'px',
                        'size' => 24,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a' => 'line-height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'bullet_type',
                [
                    'label' => __('Type de puce', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'none' => __('Aucune', 'elementor-custom-widget'),
                        'disc' => __('Disque', 'elementor-custom-widget'),
                        'circle' => __('Cercle', 'elementor-custom-widget'),
                        'square' => __('Carré', 'elementor-custom-widget'),
                    ],
                    'default' => 'disc',
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li' => 'list-style-type: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_section();
        }



        protected function render()
        {
            $settings = $this->get_settings_for_display(); // Récupération des paramètres de personnalisation

            // Début de l'affichage du widget avec une classe CSS personnalisable
            echo '<div class="ma-classe-css-personnalisable">';

            // Appel de la fonction pour afficher la liste des catégories
            afficher_categories_articles();

            // Fin de l'affichage du widget
            echo '</div>';
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
