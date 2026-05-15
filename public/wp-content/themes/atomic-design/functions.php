<?php

/**
 * Atomic Design Theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function atomic_design_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style']);

    register_nav_menus(
        [
            'primary' => 'Primary Menu',
            'footer'  => 'Footer Menu',
        ]
    );
}
add_action('after_setup_theme', 'atomic_design_setup');

/**
 * Enqueue scripts and styles
 */
function atomic_design_assets()
{
    $theme_version = wp_get_theme()->get('Version');
    $theme_uri     = get_template_directory_uri();

    // Google Fonts — loaded via PHP so WordPress can manage them properly.
    // To change fonts: update the URL here AND update --font-display / --font-body in variables.css.
    wp_enqueue_style(
        'atomic-design-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=Rajdhani:wght@400;500;600&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'atomic-design-main',
        $theme_uri . '/assets/css/main.css',
        ['atomic-design-fonts'],
        $theme_version
    );

    wp_enqueue_script(
        'atomic-design-main',
        $theme_uri . '/assets/js/main.js',
        [],
        $theme_version,
        true
    );

    // Shared FAQ accordion behavior reused across pages and post type templates.
    wp_enqueue_script(
        'atomic-design-faq-accordion',
        $theme_uri . '/assets/js/faq-accordion.js',
        [],
        $theme_version,
        true
    );

    // Testimonials slider: arrows, dots, responsive 1/2/3 cards.
    wp_enqueue_script(
        'atomic-design-testimonials-slider',
        $theme_uri . '/assets/js/testimonials-slider.js',
        [],
        $theme_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'atomic_design_assets');

/**
 * Editor styles
 */
function atomic_design_editor_assets()
{
    $theme_version = wp_get_theme()->get('Version');
    $theme_uri     = get_template_directory_uri();

    add_editor_style('assets/css/editor.css');

    wp_enqueue_style(
        'atomic-design-editor',
        $theme_uri . '/assets/css/editor.css',
        [],
        $theme_version
    );
}
add_action('enqueue_block_editor_assets', 'atomic_design_editor_assets');

/**
 * Admin styles for ACF field editing screens.
 */
function atomic_design_admin_assets($hook_suffix)
{
    if (!is_admin() || !function_exists('acf_add_options_page')) {
        return;
    }

    $theme_version = wp_get_theme()->get('Version');
    $theme_uri     = get_template_directory_uri();

    wp_enqueue_style(
        'atomic-design-admin-acf',
        $theme_uri . '/assets/css/admin-acf.css',
        [],
        $theme_version
    );
}
add_action('admin_enqueue_scripts', 'atomic_design_admin_assets');

/**
 * Ensure common layout blocks expose Gutenberg's custom class field.
 *
 * Some environments hide the "Additional CSS class(es)" control unless the
 * block explicitly supports customClassName. We force-enable it for the
 * section-like core blocks editors use as wrappers in page layouts.
 */
function atomic_design_enable_custom_block_classes($args, $block_type)
{
    $allowed_blocks = [
        'core/group',
        'core/cover',
        'core/columns',
        'core/column',
        'core/media-text',
    ];

    if (in_array($block_type, $allowed_blocks, true)) {
        if (!isset($args['supports']) || !is_array($args['supports'])) {
            $args['supports'] = [];
        }

        $args['supports']['customClassName'] = true;
    }

    return $args;
}
add_filter('register_block_type_args', 'atomic_design_enable_custom_block_classes', 10, 2);

/**
 * ACF integration
 * - JSON save/load paths
 * - Placeholder for ACF block registration
 */
function atomic_design_register_acf_options_pages()
{
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    // Parent menu: keep synced reusable content grouped in one place.
    acf_add_options_page(
        [
            'page_title' => 'Synced Components',
            'menu_title' => 'Synced Components',
            'menu_slug'  => 'atomic-design-synced-components',
            'capability' => 'manage_options',
            'redirect'   => true,
        ]
    );

    // Child page for global contact values used across templates.
    acf_add_options_sub_page(
        [
            'page_title'  => 'Contact Details',
            'menu_title'  => 'Contact Details',
            'menu_slug'   => 'atomic-design-contact-details',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );

    // Hero Settings — shared CTA and trust markers used by hero sections site-wide.
    acf_add_options_sub_page(
        [
            'page_title'  => 'Hero Settings',
            'menu_title'  => 'Hero Settings',
            'menu_slug'   => 'atomic-design-hero-settings',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );

    // Central testimonials store — one set of reviews reused across all CPT templates.
    acf_add_options_sub_page(
        [
            'page_title'  => 'Testimonials',
            'menu_title'  => 'Testimonials',
            'menu_slug'   => 'atomic-design-testimonials',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );

    // Trust Bar — key facts / selling points (icon + two-line text). Same on template and static pages.
    acf_add_options_sub_page(
        [
            'page_title'  => 'Trust Bar',
            'menu_title'  => 'Trust Bar',
            'menu_slug'   => 'atomic-design-trust-bar',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );

    // Partners & Affiliations — global logo grids reusable via Gutenberg block.
    acf_add_options_sub_page(
        [
            'page_title'  => 'Partners & Affiliations',
            'menu_title'  => 'Partners & Affiliations',
            'menu_slug'   => 'atomic-design-partners-affiliations',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );

    // Why Choose Light TN — global icon-card section reusable via Gutenberg block.
    acf_add_options_sub_page(
        [
            'page_title'  => 'Why Choose Light TN',
            'menu_title'  => 'Why Choose Light TN',
            'menu_slug'   => 'atomic-design-why-choose-light-tn',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );

    // Lighting & Audio Services — global service card grid reusable via Gutenberg block.
    acf_add_options_sub_page(
        [
            'page_title'  => 'Lighting & Audio Services',
            'menu_title'  => 'Lighting & Audio Services',
            'menu_slug'   => 'atomic-design-lighting-audio-services',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );

    // About Light TN — global intro section reusable via Gutenberg block.
    acf_add_options_sub_page(
        [
            'page_title'  => 'About Light TN',
            'menu_title'  => 'About Light TN',
            'menu_slug'   => 'atomic-design-about-light-tn',
            'parent_slug' => 'atomic-design-synced-components',
            'capability'  => 'manage_options',
        ]
    );
}
add_action('acf/init', 'atomic_design_register_acf_options_pages');

/**
 * Register ACF field groups in code (no manual UI setup needed).
 * Fields are defined here so they are version-controlled with the theme.
 * ACF will still show them in the admin UI for editing values.
 */
function atomic_design_register_acf_fields()
{
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // ----------------------------------------------------------------
    // Field group: Synced Components — Contact Details
    // Location: Options sub page (Contact Details)
    // ----------------------------------------------------------------
    acf_add_local_field_group([
        'key'    => 'group_site_settings_contact',
        'title'  => 'Contact Details',
        'fields' => [
            [
                'key'               => 'field_phone_number',
                'label'             => 'Phone Number',
                'name'              => 'phone_number',
                'type'              => 'text',
                'instructions'      => 'Displayed in the site header and footer. Change here to update the entire site.',
                'required'          => 0,
                'placeholder'       => '(000) 000-0000',
                'prepend'           => '',
                'append'            => '',
                'maxlength'         => '',
            ],
            [
                'key'               => 'field_email_address',
                'label'             => 'Email Address',
                'name'              => 'email_address',
                'type'              => 'email',
                'instructions'      => 'General contact email shown in footer and contact page.',
                'required'          => 0,
                'placeholder'       => 'info@example.com',
            ],
            [
                'key'               => 'field_business_address',
                'label'             => 'Business Address',
                'name'              => 'business_address',
                'type'              => 'textarea',
                'instructions'      => 'Full mailing address for footer / contact page.',
                'required'          => 0,
                'rows'              => 3,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'atomic-design-contact-details',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 1,
    ]);

    acf_add_local_field_group([
        'key'    => 'group_atomic_hero_settings',
        'title'  => 'Hero Settings',
        'fields' => [
            [
                'key'           => 'field_atomic_hero_global_primary_link',
                'label'         => 'CTA Link',
                'name'          => 'hero_global_primary_link',
                'type'          => 'link',
                'instructions'  => 'Global hero button shown on all hero sections.',
                'return_format' => 'array',
            ],
            [
                'key'           => 'field_atomic_hero_global_cta_icon',
                'label'         => 'CTA Icon',
                'name'          => 'hero_global_cta_icon',
                'type'          => 'image',
                'instructions'  => 'Optional icon inside the CTA button. Leave empty to use the default arrow.',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'library'       => 'all',
            ],
            [
                'key'           => 'field_atomic_hero_certification_icon',
                'label'         => 'Certification Icon',
                'name'          => 'hero_certification_icon',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'library'       => 'all',
                'wrapper'       => ['width' => '35'],
            ],
            [
                'key'           => 'field_atomic_hero_certification_text',
                'label'         => 'Certification Text',
                'name'          => 'hero_certification_text',
                'type'          => 'textarea',
                'default_value' => "Certified\nProfessional\nInstaller",
                'rows'          => 3,
                'wrapper'       => ['width' => '65'],
            ],
            [
                'key'          => 'field_atomic_hero_review_initials',
                'label'        => 'Review Initials',
                'name'         => 'hero_review_initials',
                'type'         => 'repeater',
                'layout'       => 'table',
                'button_label' => 'Add initial',
                'sub_fields'   => [
                    [
                        'key'     => 'field_atomic_hero_review_initial',
                        'label'   => 'Initial',
                        'name'    => 'initial',
                        'type'    => 'text',
                        'wrapper' => ['width' => '35'],
                    ],
                    [
                        'key'           => 'field_atomic_hero_review_initial_color',
                        'label'         => 'Color',
                        'name'          => 'color',
                        'type'          => 'text',
                        'default_value' => '#6ba8df',
                        'wrapper'       => ['width' => '65'],
                    ],
                ],
            ],
            [
                'key'           => 'field_atomic_hero_review_label',
                'label'         => 'Review Label',
                'name'          => 'hero_review_label',
                'type'          => 'text',
                'default_value' => '100+ Glowing Reviews',
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_atomic_hero_review_rating',
                'label'         => 'Star Rating',
                'name'          => 'hero_review_rating',
                'type'          => 'number',
                'default_value' => 5,
                'min'           => 0,
                'max'           => 5,
                'step'          => 1,
                'wrapper'       => ['width' => '50'],
            ],
            [
                'key'           => 'field_atomic_hero_bbb_logo',
                'label'         => 'BBB Logo',
                'name'          => 'hero_bbb_logo',
                'type'          => 'image',
                'return_format' => 'array',
                'preview_size'  => 'thumbnail',
                'library'       => 'all',
                'wrapper'       => ['width' => '35'],
            ],
            [
                'key'           => 'field_atomic_hero_bbb_text',
                'label'         => 'BBB Text Fallback',
                'name'          => 'hero_bbb_text',
                'type'          => 'text',
                'default_value' => 'A+ Rating',
                'wrapper'       => ['width' => '65'],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'atomic-design-hero-settings',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 1,
    ]);

    acf_add_local_field_group([
        'key'    => 'group_atomic_partners_affiliations',
        'title'  => 'Partners & Affiliations',
        'fields' => [
            [
                'key'           => 'field_atomic_partners_heading',
                'label'         => 'Partners Heading',
                'name'          => 'partners_heading',
                'type'          => 'text',
                'default_value' => 'Partners',
                'placeholder'   => 'Partners',
            ],
            [
                'key'          => 'field_atomic_partners_items',
                'label'        => 'Partner Logos',
                'name'         => 'partners_items',
                'type'         => 'repeater',
                'instructions' => 'Add one partner logo per card.',
                'layout'       => 'block',
                'button_label' => 'Add partner',
                'sub_fields'   => [
                    [
                        'key'     => 'field_atomic_partner_name',
                        'label'   => 'Name',
                        'name'    => 'name',
                        'type'    => 'text',
                        'wrapper' => ['width' => '35'],
                    ],
                    [
                        'key'           => 'field_atomic_partner_logo',
                        'label'         => 'Logo',
                        'name'          => 'logo',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                        'library'       => 'all',
                        'wrapper'       => ['width' => '35'],
                    ],
                    [
                        'key'     => 'field_atomic_partner_link',
                        'label'   => 'Link',
                        'name'    => 'link',
                        'type'    => 'url',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],
            [
                'key'           => 'field_atomic_affiliations_heading',
                'label'         => 'Affiliations Heading',
                'name'          => 'affiliations_heading',
                'type'          => 'text',
                'default_value' => 'Affiliations',
                'placeholder'   => 'Affiliations',
            ],
            [
                'key'          => 'field_atomic_affiliations_items',
                'label'        => 'Affiliation Logos',
                'name'         => 'affiliations_items',
                'type'         => 'repeater',
                'instructions' => 'Add one affiliation logo per card.',
                'layout'       => 'block',
                'button_label' => 'Add affiliation',
                'sub_fields'   => [
                    [
                        'key'     => 'field_atomic_affiliation_name',
                        'label'   => 'Name',
                        'name'    => 'name',
                        'type'    => 'text',
                        'wrapper' => ['width' => '35'],
                    ],
                    [
                        'key'           => 'field_atomic_affiliation_logo',
                        'label'         => 'Logo',
                        'name'          => 'logo',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                        'library'       => 'all',
                        'wrapper'       => ['width' => '35'],
                    ],
                    [
                        'key'     => 'field_atomic_affiliation_link',
                        'label'   => 'Link',
                        'name'    => 'link',
                        'type'    => 'url',
                        'wrapper' => ['width' => '30'],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'atomic-design-partners-affiliations',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 1,
    ]);

    acf_add_local_field_group([
        'key'    => 'group_atomic_why_choose_light_tn',
        'title'  => 'Why Choose Light TN',
        'fields' => [
            [
                'key'           => 'field_atomic_why_choose_light_tn_heading',
                'label'         => 'Heading',
                'name'          => 'why_choose_light_tn_heading',
                'type'          => 'text',
                'default_value' => 'Why Choose Light TN',
                'placeholder'   => 'Why Choose Light TN',
            ],
            [
                'key'          => 'field_atomic_why_choose_light_tn_description',
                'label'        => 'Intro Copy',
                'name'         => 'why_choose_light_tn_description',
                'type'         => 'wysiwyg',
                'instructions' => 'Short paragraph shown below the heading.',
                'tabs'         => 'visual',
                'toolbar'      => 'basic',
                'media_upload' => 0,
            ],
            [
                'key'          => 'field_atomic_why_choose_light_tn_items',
                'label'        => 'Reason Cards',
                'name'         => 'why_choose_light_tn_items',
                'type'         => 'repeater',
                'instructions' => 'Add the icon, title, and body copy for each reason card.',
                'layout'       => 'block',
                'button_label' => 'Add reason card',
                'sub_fields'   => [
                    [
                        'key'           => 'field_atomic_why_choose_light_tn_item_icon',
                        'label'         => 'Icon',
                        'name'          => 'icon',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'thumbnail',
                        'library'       => 'all',
                        'wrapper'       => ['width' => '25'],
                    ],
                    [
                        'key'     => 'field_atomic_why_choose_light_tn_item_title',
                        'label'   => 'Title',
                        'name'    => 'title',
                        'type'    => 'text',
                        'wrapper' => ['width' => '35'],
                    ],
                    [
                        'key'     => 'field_atomic_why_choose_light_tn_item_description',
                        'label'   => 'Description',
                        'name'    => 'description',
                        'type'    => 'textarea',
                        'rows'    => 4,
                        'wrapper' => ['width' => '40'],
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'atomic-design-why-choose-light-tn',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 1,
    ]);

    acf_add_local_field_group([
        'key'    => 'group_atomic_lighting_audio_services',
        'title'  => 'Lighting & Audio Services',
        'fields' => [
            [
                'key'           => 'field_atomic_lighting_audio_services_heading',
                'label'         => 'Heading',
                'name'          => 'lighting_audio_services_heading',
                'type'          => 'text',
                'default_value' => 'Outdoor Lighting & Audio Services',
                'placeholder'   => 'Outdoor Lighting & Audio Services',
            ],
            [
                'key'          => 'field_atomic_lighting_audio_services_items',
                'label'        => 'Service Cards',
                'name'         => 'lighting_audio_services_items',
                'type'         => 'repeater',
                'instructions' => 'Add one service card per row.',
                'layout'       => 'block',
                'button_label' => 'Add service card',
                'sub_fields'   => [
                    [
                        'key'           => 'field_atomic_lighting_audio_services_item_image',
                        'label'         => 'Image',
                        'name'          => 'image',
                        'type'          => 'image',
                        'return_format' => 'array',
                        'preview_size'  => 'medium',
                        'library'       => 'all',
                        'wrapper'       => ['width' => '32'],
                    ],
                    [
                        'key'     => 'field_atomic_lighting_audio_services_item_title',
                        'label'   => 'Title',
                        'name'    => 'title',
                        'type'    => 'text',
                        'wrapper' => ['width' => '28'],
                    ],
                    [
                        'key'           => 'field_atomic_lighting_audio_services_item_link',
                        'label'         => 'Link',
                        'name'          => 'link',
                        'type'          => 'link',
                        'return_format' => 'array',
                        'wrapper'       => ['width' => '40'],
                    ],
                    [
                        'key'   => 'field_atomic_lighting_audio_services_item_description',
                        'label' => 'Description',
                        'name'  => 'description',
                        'type'  => 'textarea',
                        'rows'  => 6,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'atomic-design-lighting-audio-services',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 1,
    ]);

    acf_add_local_field_group([
        'key'    => 'group_atomic_about_light_tn',
        'title'  => 'About Light TN',
        'fields' => [
            [
                'key'           => 'field_atomic_about_light_tn_heading',
                'label'         => 'Heading',
                'name'          => 'about_light_tn_heading',
                'type'          => 'text',
                'default_value' => 'About Light TN',
                'placeholder'   => 'About Light TN',
            ],
            [
                'key'          => 'field_atomic_about_light_tn_intro_copy',
                'label'        => 'Intro Copy',
                'name'         => 'about_light_tn_intro_copy',
                'type'         => 'wysiwyg',
                'instructions' => 'Main body copy shown to the left of the image.',
                'tabs'         => 'visual',
                'toolbar'      => 'basic',
                'media_upload' => 0,
                'wrapper'      => ['width' => '45'],
            ],
            [
                'key'           => 'field_atomic_about_light_tn_image',
                'label'         => 'Main Image',
                'name'          => 'about_light_tn_image',
                'type'          => 'image',
                'instructions'  => 'Primary image shown to the right of the intro copy.',
                'return_format' => 'array',
                'preview_size'  => 'medium',
                'library'       => 'all',
                'wrapper'       => ['width' => '55'],
            ],
            [
                'key'           => 'field_atomic_about_light_tn_image_caption',
                'label'         => 'Image Caption',
                'name'          => 'about_light_tn_image_caption',
                'type'          => 'text',
                'instructions'  => 'Optional caption shown below the main image.',
                'wrapper'       => ['width' => '100'],
            ],
            [
                'key'           => 'field_atomic_about_light_tn_secondary_heading',
                'label'         => 'Secondary Heading',
                'name'          => 'about_light_tn_secondary_heading',
                'type'          => 'text',
                'default_value' => 'Meet Daryl',
                'placeholder'   => 'Meet Daryl',
            ],
            [
                'key'          => 'field_atomic_about_light_tn_columns',
                'label'        => 'Detail Columns',
                'name'         => 'about_light_tn_columns',
                'type'         => 'repeater',
                'instructions' => 'Add the text columns shown below the secondary heading. Three columns match the current design best.',
                'layout'       => 'block',
                'button_label' => 'Add detail column',
                'sub_fields'   => [
                    [
                        'key'     => 'field_atomic_about_light_tn_column_copy',
                        'label'   => 'Column Copy',
                        'name'    => 'copy',
                        'type'    => 'wysiwyg',
                        'tabs'    => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'atomic-design-about-light-tn',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 1,
    ]);

    // FAQ field group is defined in acf-json/group_atomic_faq_shared.json
    // so it appears as a real editable group in ACF > Field Groups admin.
}
add_action('acf/init', 'atomic_design_register_acf_fields');

/**
 * Store ACF JSON in the theme for version control
 */
function atomic_design_acf_json_save_point($path)
{
    return get_template_directory() . '/acf-json';
}
add_filter('acf/settings/save_json', 'atomic_design_acf_json_save_point');

function atomic_design_acf_json_load_point($paths)
{
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'atomic_design_acf_json_load_point');

/**
 * REST API support for shared CPT ACF fields.
 *
 * Accepts payloads like:
 * {
 *   "acf": {
 *     "hero_title": "Industrial Phenolic Labels",
 *     "hero_subtitle": "<p>Built for harsh environments.</p>",
 *     "hero_media": 123,
 *     "faqs_section_heading": "FAQs",
 *     "faq_layout": "two-column",
 *     "faq_items": [
 *       {
 *         "faq_question": "Question",
 *         "faq_answer": "<p>Answer</p>",
 *         "default_open": 0
 *       }
 *     ],
 *     "title_description_sections": [
 *       {
 *         "title_description_heading": "Experience You Can Trust",
 *         "title_description_content": "<p>Paragraph one.<\/p><p>Paragraph two.<\/p>"
 *       }
 *     ],
 *     "insight_columns_sections": [
 *       {
 *         "insight_columns_heading": "Nashville. We Know It.",
 *         "insight_columns_intro": "<p>Short intro copy.<\/p>",
 *         "insight_columns_items": [
 *           {
 *             "title": "Estate Properties Across Nashville’s Premier Neighborhoods",
 *             "description": "<p>Column copy.<\/p>"
 *           }
 *         ]
 *       }
 *     ],
 *     "consultation_split_sections": [
 *       {
 *         "consultation_split_heading": "Ready to light your property the right way?",
 *         "consultation_split_intro": "<p>Short intro copy.<\/p>",
 *         "consultation_split_form_id": 147,
 *         "consultation_split_image": 123
 *       }
 *     ],
 *     "design_process_sections": [
 *       {
 *         "design_process_heading": "Our Outdoor Lighting Design Process",
 *         "design_process_steps": [
 *           {
 *             "step_nav_label": "We Protect Your Landscape",
 *             "step_title": "Design Comes First",
 *             "step_badge_title": "Design-Driven Layouts",
 *             "step_description": "<p>Step copy.<\/p>",
 *             "step_image": 123,
 *             "step_icon": 456
 *           }
 *         ]
 *       }
 *     ],
 *     "proof_points_sections": [
 *       {
 *         "proof_points_heading": "Why Choose Light TN in Nashville",
 *         "proof_points_intro_title": "Our Credentials and Commitment",
 *         "proof_points_intro_copy": "<p>Short intro copy.<\/p>",
 *         "proof_points_items": [
 *           {
 *             "title": "Licensed and Accredited",
 *             "timeline": "Timeline: Week 1",
 *             "description": "<p>Card copy.<\/p>",
 *             "image": 123
 *           }
 *         ]
 *       }
 *     ],
 *     "steps_grid_sections": [
 *       {
 *         "steps_grid_heading": "How It Works",
 *         "steps_grid_heading_alignment": "center",
 *         "steps_grid_items": [
 *           {
 *             "title": "Nashville Property Consultation",
 *             "timeline": "Timeline: Week 1",
 *             "description": "<p>Card copy.<\/p>",
 *             "image": 123
 *           }
 *         ]
 *       }
 *     ],
 *     "split_callout_sections": [
 *       {
 *         "split_callout_heading": "Investment & Pricing",
 *         "split_callout_intro": "<p>Short intro copy.<\/p>",
 *         "split_callout_link": {
 *           "title": "See full pricing and what's included",
 *           "url": "https:\/\/example.com",
 *           "target": "_self"
 *         },
 *         "split_callout_panel_title": "Payment Terms:",
 *         "split_callout_panel_copy": "<p>Panel copy.<\/p>"
 *       }
 *     ],
     *     "detail_card_grid_sections": [
     *       {
     *         "detail_card_grid_heading": "Landscape Lighting Design Services",
     *         "detail_card_grid_content": "<p>Left column copy.<\/p>",
     *         "detail_card_grid_ideal_items": [
     *           {
     *             "item": "Properties with significant landscape investment"
     *           },
     *           {
     *             "item": "Homes with specimen trees and mature plantings"
     *           }
     *         ],
     *         "detail_card_grid_included_description": "<p>Custom design matched to your landscape features.<\/p>",
     *         "detail_card_grid_items": [
     *           {
     *             "title": "Site & Property Analysis",
 *             "description": "<p>Card copy.<\/p>"
 *           }
 *         ]
 *       }
 *     ],
 *     "spotlight_cards_sections": [
 *       {
 *         "spotlight_cards_heading": "What Sets Us Apart",
 *         "spotlight_cards_intro": "<p>Left column copy.<\/p>",
 *         "spotlight_cards_image": 123,
 *         "spotlight_cards_items": [
 *           {
 *             "title": "Landscape Architecture Coordination",
 *             "description": "<p>Card copy.<\/p>"
 *           }
 *         ]
 *       }
 *     ],
 *   }
 * }
 */
function atomic_design_get_rest_post_types()
{
    // Light TN CPTs that carry shared template ACF fields.
    return ['services', 'locations', 'service-location'];
}

function atomic_design_get_allowed_template_acf_fields()
{
    return [
        'hero_title',
        'hero_subtitle',
        'hero_media',
        'faqs_section_heading',
        'faq_layout',
        'faq_items',
        'title_description_sections',
        'insight_columns_sections',
        'proof_points_sections',
        'steps_grid_sections',
        'split_callout_sections',
        'detail_card_grid_sections',
        'spotlight_cards_sections',
        'consultation_split_sections',
        'design_process_sections',
        '_permalink_uri',
    ];
}

/**
 * Expose Rank Math SEO meta fields in REST for template-driven CPTs.
 *
 * This lets imports update the same SEO fields directly through the REST API.
 */
function atomic_design_register_rank_math_rest_meta()
{
    $post_types = atomic_design_get_rest_post_types();
    $meta_keys  = ['rank_math_title', 'rank_math_description'];

    $auth_callback = static function () {
        return current_user_can('edit_posts');
    };

    foreach ($post_types as $post_type) {
        foreach ($meta_keys as $meta_key) {
            register_post_meta(
                $post_type,
                $meta_key,
                [
                    'show_in_rest'  => true,
                    'single'        => true,
                    'type'          => 'string',
                    'auth_callback' => $auth_callback,
                ]
            );
        }
    }
}
add_action('init', 'atomic_design_register_rank_math_rest_meta');

function atomic_design_get_template_acf_value($field_name, $post_id)
{
    if ($field_name === '_permalink_uri') {
        return get_post_meta($post_id, '_permalink_uri', true);
    }

    return get_field($field_name, $post_id);
}

function atomic_design_update_template_acf_value($field_name, $field_value, $post_id)
{
    if ($field_name === '_permalink_uri') {
        return update_field('field_atomic_service_location_permalink_uri', $field_value, $post_id);
    }

    return update_field($field_name, $field_value, $post_id);
}

function atomic_design_sync_service_location_permalink($post_id, $custom_uri)
{
    $custom_uri = is_string($custom_uri) ? trim($custom_uri) : '';
    if ($custom_uri === '') {
        return;
    }

    if (
        !class_exists('Permalink_Manager_URI_Functions') ||
        !class_exists('Permalink_Manager_Helper_Functions')
    ) {
        return;
    }

    $sanitized_uri = Permalink_Manager_Helper_Functions::sanitize_title(trim($custom_uri, '/'), true);
    if ($sanitized_uri === '') {
        return;
    }

    Permalink_Manager_URI_Functions::save_single_uri($post_id, $sanitized_uri, false, false);
    Permalink_Manager_URI_Functions::save_all_uris();
}

function atomic_design_get_template_acf_for_rest($object)
{
    if (!function_exists('get_field')) {
        return [];
    }

    $post_id  = isset($object['id']) ? (int) $object['id'] : 0;
    $response = [];

    foreach (atomic_design_get_allowed_template_acf_fields() as $field_name) {
        $response[$field_name] = atomic_design_get_template_acf_value($field_name, $post_id);
    }

    return $response;
}

function atomic_design_register_template_acf_rest_fields()
{
    if (!function_exists('get_field')) {
        return;
    }

    foreach (atomic_design_get_rest_post_types() as $post_type) {
        register_rest_field(
            $post_type,
            'acf',
            [
                'get_callback' => 'atomic_design_get_template_acf_for_rest',
                'schema'       => null,
            ]
        );
    }
}
add_action('rest_api_init', 'atomic_design_register_template_acf_rest_fields');

function atomic_design_capture_template_acf_rest_payload($prepared_post, $request)
{
    if (!function_exists('update_field')) {
        return $prepared_post;
    }

    $acf_data = $request->get_param('acf');
    if (!is_array($acf_data) || empty($acf_data)) {
        return $prepared_post;
    }

    $allowed_fields = array_flip(atomic_design_get_allowed_template_acf_fields());
    $template_payload = array_intersect_key($acf_data, $allowed_fields);

    if (empty($template_payload)) {
        return $prepared_post;
    }

    $post_type = $prepared_post->post_type ?? '';
    if (empty($post_type)) {
        return $prepared_post;
    }

    add_action(
        "rest_after_insert_{$post_type}",
        function ($post) use ($template_payload) {
            foreach ($template_payload as $field_name => $field_value) {
                atomic_design_update_template_acf_value($field_name, $field_value, $post->ID);
            }

            if (
                $post->post_type === 'service-location' &&
                array_key_exists('_permalink_uri', $template_payload)
            ) {
                atomic_design_sync_service_location_permalink($post->ID, $template_payload['_permalink_uri']);
            }
        },
        10,
        3
    );

    return $prepared_post;
}

function atomic_design_register_template_acf_rest_savers()
{
    foreach (atomic_design_get_rest_post_types() as $post_type) {
        add_filter(
            "rest_pre_insert_{$post_type}",
            'atomic_design_capture_template_acf_rest_payload',
            10,
            2
        );
    }
}
add_action('init', 'atomic_design_register_template_acf_rest_savers');

/**
 * Register ACF blocks
 */
function atomic_design_register_acf_blocks()
{
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    // Hero block
    acf_register_block_type(
        [
            'name'            => 'hero',
            'title'           => __('Hero', 'atomic-design'),
            'description'     => __('Hero section with title, subtitle, and CTA.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/hero/hero.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'slides',
            'keywords'        => ['hero', 'banner', 'header'],
            // Show ACF fields UI inside the block by default.
            'mode'            => 'edit',
            'supports'        => [
                'align' => ['wide', 'full'],
                // Lock edit mode so editors always see the fields UI.
                'mode'            => false,
                'jsx'             => true,
                // Keep the Gutenberg "Additional CSS class(es)" field visible.
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Trust Bar block — same partial & CSS on template and static pages.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'trust-bar',
            'title'           => __('Trust Bar', 'atomic-design'),
            'description'     => __('Key facts / selling points from Synced Components → Trust Bar. Icon + text per item.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/trust-bar/trust-bar.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'awards',
            'keywords'        => ['trust', 'facts', 'selling points', 'key points'],
            'mode'            => 'preview',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Partners & Affiliations block — global logo grids.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'partners-affiliations',
            'title'           => __('Partners & Affiliations', 'atomic-design'),
            'description'     => __('Global partners and affiliations logo grids from Synced Components.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/partners-affiliations/partners-affiliations.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'groups',
            'keywords'        => ['partners', 'affiliations', 'logos', 'global'],
            'mode'            => 'preview',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Why Choose Light TN block — global icon-card section.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'why-choose-light-tn',
            'title'           => __('Why Choose Light TN', 'atomic-design'),
            'description'     => __('Global Why Choose Light TN icon cards from Synced Components.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/why-choose-light-tn/why-choose-light-tn.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'awards',
            'keywords'        => ['why choose', 'light tn', 'benefits', 'reasons', 'global'],
            'mode'            => 'preview',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Lighting & Audio Services block — global service card grid.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'lighting-audio-services',
            'title'           => __('Lighting & Audio Services', 'atomic-design'),
            'description'     => __('Global lighting and audio services card grid from Synced Components.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/lighting-audio-services/lighting-audio-services.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'screenoptions',
            'keywords'        => ['services', 'lighting', 'audio', 'cards', 'global'],
            'mode'            => 'preview',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // About Light TN block — global image + text panel section.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'about-light-tn',
            'title'           => __('About Light TN', 'atomic-design'),
            'description'     => __('Global About Light TN section from Synced Components.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/about-light-tn/about-light-tn.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'id-alt',
            'keywords'        => ['about', 'light tn', 'intro', 'global'],
            'mode'            => 'preview',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Testimonials block
    // Same partial & CSS as on CPT pages. Use on normal pages.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'testimonials',
            'title'           => __('Testimonials', 'atomic-design'),
            'description'     => __('Client testimonials pulled from the central Synced Components → Testimonials options page.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/testimonials/testimonials.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'star-filled',
            'keywords'        => ['testimonials', 'reviews', 'clients', 'ratings'],
            'mode'            => 'preview',
            'supports'        => [
                'align'           => false,
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // FAQ Accordion block
    // Insert anywhere in the Gutenberg editor on any page or CPT.
    // Each block instance has its own independent FAQ items.
    // The same CSS + JS used by the template-parts partial applies.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'faq-accordion',
            'title'           => __('FAQ Accordion', 'atomic-design'),
            'description'     => __('Expandable FAQ section. Insert on any page or template. Each instance has independent items.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/faq-accordion/faq-accordion.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'editor-help',
            'keywords'        => ['faq', 'questions', 'accordion', 'help'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => false,
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Title + Description Columns block
    // Reusable heading + rich text section.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'title-description-columns',
            'title'           => __('Title + Description Columns', 'atomic-design'),
            'description'     => __('Left-aligned heading with rich text content.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/title-description-columns/title-description-columns.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'editor-paragraph',
            'keywords'        => ['title', 'description', 'content', 'columns', 'about'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Content Columns block
    // Heading + intro + reusable text columns.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'insight-columns',
            'title'           => __('Content Columns', 'atomic-design'),
            'description'     => __('Heading, intro copy, and reusable three-column text content.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/insight-columns/insight-columns.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'columns',
            'keywords'        => ['content', 'columns', 'three columns', 'text columns', 'details'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Proof Points block
    // Intro column plus supporting image/text cards.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'proof-points',
            'title'           => __('Proof Points', 'atomic-design'),
            'description'     => __('Section with a lead intro column and supporting proof cards.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/proof-points/proof-points.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'images-alt2',
            'keywords'        => ['proof points', 'credentials', 'reasons', 'cards', 'trust'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Steps Grid block
    // Image-led process or how-it-works cards with heading alignment.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'steps-grid',
            'title'           => __('Steps Grid', 'atomic-design'),
            'description'     => __('Reusable image-led step cards with a configurable heading alignment.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/steps-grid/steps-grid.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'grid-view',
            'keywords'        => ['steps', 'process', 'how it works', 'grid', 'cards'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Split Callout block
    // Left intro content with right-side CTA and info panel.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'split-callout',
            'title'           => __('Split Callout', 'atomic-design'),
            'description'     => __('Two-column callout with left-side intro copy and right-side CTA/panel content.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/split-callout/split-callout.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'align-pull-right',
            'keywords'        => ['callout', 'pricing', 'investment', 'two column', 'panel'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Detail Card Grid block
    // Left text content with right-side card grid.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'detail-card-grid',
            'title'           => __('Detail Card Grid', 'atomic-design'),
            'description'     => __('Left column content paired with a two-column card grid.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/detail-card-grid/detail-card-grid.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'screenoptions',
            'keywords'        => ['details', 'cards', 'grid', 'service', 'features'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Spotlight Cards block
    // Left intro, right image, and a row of supporting cards.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'spotlight-cards',
            'title'           => __('Spotlight Cards', 'atomic-design'),
            'description'     => __('Left-side intro content with a right-side image and supporting cards.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/spotlight-cards/spotlight-cards.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'images-alt',
            'keywords'        => ['spotlight', 'cards', 'features', 'highlights', 'image'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Consultation Split block
    // Form on the left, visual panel on the right.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'consultation-split',
            'title'           => __('Consultation Split', 'atomic-design'),
            'description'     => __('Consultation form section with WPForms on the left and an image on the right.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/consultation-split/consultation-split.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'feedback',
            'keywords'        => ['consultation', 'form', 'wpforms', 'contact', 'split'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );

    // ----------------------------------------------------------
    // Design Process block
    // Interactive process section with step details and imagery.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'design-process',
            'title'           => __('Design Process', 'atomic-design'),
            'description'     => __('Interactive process section with step details, imagery, and a vertical step rail.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/design-process/design-process.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'editor-ol',
            'keywords'        => ['process', 'steps', 'design process', 'timeline', 'workflow'],
            'mode'            => 'edit',
            'supports'        => [
                'align'           => ['wide', 'full'],
                'mode'            => false,
                'jsx'             => true,
                'customClassName' => true,
            ],
        ]
    );
}
add_action('acf/init', 'atomic_design_register_acf_blocks');

/**
 * Register custom Gutenberg block category so theme blocks are grouped.
 */
function atomic_design_block_categories($categories)
{
    return array_merge(
        [
            [
                'slug'  => 'atomic-blocks',
                'title' => __('Atomic Design Blocks', 'atomic-design'),
                'icon'  => 'layout',
            ],
        ],
        $categories
    );
}
add_filter('block_categories_all', 'atomic_design_block_categories', 10, 2);

/**
 * Show editor-only guidance for global-content ACF blocks.
 *
 * Testimonials uses a shared field group on both the options page and block,
 * so hide the duplicate block inputs and point editors to the global source.
 */
function atomic_design_global_block_editor_notices()
{
    global $pagenow;

    if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
        return;
    }
    ?>
    <script>
    (function() {
        var notices = [
            {
                field: 'testimonials_list',
                url: <?php echo wp_json_encode(admin_url('admin.php?page=atomic-design-testimonials')); ?>,
                label: 'Testimonials'
            }
        ];

        function injectNotices() {
            notices.forEach(function(item) {
                document.querySelectorAll('[data-name="' + item.field + '"]').forEach(function(field) {
                    var fieldsGroup = field.closest('.acf-fields');
                    if (!fieldsGroup || fieldsGroup.parentNode.querySelector('.atomic-acf-notice')) {
                        return;
                    }

                    fieldsGroup.style.display = 'none';

                    var notice = document.createElement('div');
                    notice.className = 'atomic-acf-notice';
                    notice.style.cssText = 'background:#fff8e5;border-left:4px solid #f0ad4e;padding:10px 14px;margin:8px 0;font-size:12px;color:#6b4f00;border-radius:0 4px 4px 0;line-height:1.6;';
                    notice.innerHTML = 'This content is managed globally. Changes here will not affect the frontend. <a href="' + item.url + '" target="_blank" style="color:#8a5a00;font-weight:600;text-decoration:underline;">Update ' + item.label + ' →</a>';

                    fieldsGroup.parentNode.insertBefore(notice, fieldsGroup);
                });
            });
        }

        var interval = setInterval(injectNotices, 1000);
        setTimeout(function() { clearInterval(interval); }, 30000);
        injectNotices();
    })();
    </script>
    <?php
}
add_action('admin_footer', 'atomic_design_global_block_editor_notices');

/**
 * Add extra body classes for static pages: page-slug-{slug}.
 * WordPress already adds .page and .page-id-{id}. This adds .page-slug-about etc.
 * so you can target pages by slug in page-specific.css.
 */
function atomic_design_body_classes($classes)
{
    if (is_singular('page')) {
        $post = get_queried_object();
        if ($post && !empty($post->post_name)) {
            $classes[] = 'page-slug-' . sanitize_html_class($post->post_name);
        }
    }
    return $classes;
}
add_filter('body_class', 'atomic_design_body_classes', 10, 1);

/**
 * Gutenberg block styles for Group spacing presets.
 * Editors can wrap core blocks in a Group and choose:
 * - Section (96px)
 * - Section (48px)
 * - Section (Flush)
 *
 * Our component blocks (Hero/FAQs/etc.) already include their own spacing,
 * so users generally should NOT wrap those in a Group unless intentionally.
 */
function atomic_design_register_group_block_styles()
{
    if (!function_exists('register_block_style')) {
        return;
    }

    register_block_style('core/group', [
        'name'  => 'section-210',
        'label' => __('Section (210px)', 'atomic-design'),
    ]);

    register_block_style('core/group', [
        'name'  => 'section-142',
        'label' => __('Section (142px)', 'atomic-design'),
    ]);

    register_block_style('core/group', [
        'name'  => 'section-flush',
        'label' => __('Section (Flush)', 'atomic-design'),
    ]);
}
add_action('init', 'atomic_design_register_group_block_styles');

// Post types are now managed in ACF UI and synced via acf-json.
