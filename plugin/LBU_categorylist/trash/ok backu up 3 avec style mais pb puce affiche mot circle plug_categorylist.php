<?php

/**
 * Plugin Name: LBU_plug_categorylist
 * Description: Un plugin Elementor pour afficher la liste des catégories d'articles.
 * Version: 1.2
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
        echo '<a href="' . $lien . '"><span class="icon ' . $category->slug . '"></span>' . $nom . '</a>';
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
                'icon_type',
                [
                    'label' => __('Type d\'élément', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        'none' => __('Aucun', 'elementor-custom-widget'),
                        'icon' => __('Icône', 'elementor-custom-widget'),
                        'bullet' => __('Puce', 'elementor-custom-widget'),
                    ],
                    'default' => 'icon',
                ]
            );


            $this->add_control(
                'icon',
                [
                    'label' => __(
                        'Icône',
                        'elementor-custom-widget'
                    ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'condition' => [
                        'icon_type' => 'icon',
                    ],
                ]
            );


            $this->add_control(
                'icon_fontawesome',
                [
                    'label' => __('Icône FontAwesome', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::ICON,
                    'condition' => [
                        'icon_type' => 'fontawesome',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a .icon' => 'font-family: FontAwesome; content: \'{{VALUE}}\';',
                    ],
                ]
            );

            $this->add_control(
                'bullet',
                [
                    'label' => __('Puce', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '•',
                    'condition' => [
                        'icon_type' => 'bullet',
                    ],
                ]
            );


            $this->add_control(
                'icon_wordpress',
                [
                    'label' => __('Icône WordPress', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::ICON,
                    'condition' => [
                        'icon_type' => 'wordpress',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a .icon' => 'font-family: WordPressDashicons; content: \'{{VALUE}}\';',
                    ],
                ]
            );

            $this->add_control(
                'font_size',
                [
                    'label' => __('Taille de police', 'elementor-custom-widget'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
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
                'icon_spacing',
                [
                    'label' => __(
                        'Espace entre l\'icône et le texte',
                        'elementor-custom-widget'
                    ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .ma-classe-css-personnalisable li a .icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                    'default' => [
                        'unit' => 'px',
                        'size' => 10,
                    ],
                    'condition' => [
                        'icon_type!' => 'none',
                    ],
                ]
            );


            $this->end_controls_section();
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            echo '<ul class="ma-classe-css-personnalisable">';

            foreach (get_categories() as $category) {
                $link = get_category_link($category->term_id);
                $name = $category->name;
                $style = 'color: ' . $settings['text_color'] . '; font-size: ' . $settings['font_size']['size'] . $settings['font_size']['unit'] . ';';
                $margin = $settings['margin'];

                echo '<li class="ma-classe-css-personnalisable">';
                echo '<a href="' . $link . '">';

                if ($settings['icon_type'] === 'icon' && !empty($settings['icon']['value'])) {
                    echo '<span class="' . $settings['icon']['value'] . '" style="margin-right: ' . $settings['icon_spacing']['size'] . $settings['icon_spacing']['unit'] . ';"></span>';
                } elseif ($settings['icon_type'] === 'bullet' && !empty($settings['bullet'])) {
                    echo '<span class="bullet" style="margin-right: ' . $settings['icon_spacing']['size'] . $settings['icon_spacing']['unit'] . ';">' . $settings['bullet'] . '</span>';
                }

                echo $name;
                echo '</a>';
                echo '</li>';
            }

            echo '</ul>';
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
