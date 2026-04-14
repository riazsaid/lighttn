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
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@600;700&display=swap',
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

    // Industry Solutions — same grid on all service template pages (icon, title, description, link).
    acf_add_options_sub_page(
        [
            'page_title'  => 'Industry Solutions',
            'menu_title'  => 'Industry Solutions',
            'menu_slug'   => 'atomic-design-industry-solutions',
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
 *     "hero_primary_link": {"title":"Request a Quote","url":"\/contact","target":"_self"},
 *     "hero_secondary_link": {"title":"View Materials","url":"\/materials","target":"_self"},
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
 *     "why_choose_sections": [
 *       {
 *         "why_choose_heading": "Why Custom Phenolic Labels",
 *         "why_choose_items": [
 *           {
 *             "why_choose_item_title": "Custom Engraving,\nDone In-House",
 *             "why_choose_item_description": "Supporting paragraph."
 *           }
 *         ]
 *       }
 *     ],
 *     "title_description_sections": [
 *       {
 *         "title_description_heading": "Experience You Can Trust",
 *         "title_description_content": "<p>Paragraph one.<\/p><p>Paragraph two.<\/p>"
 *       }
 *     ],
 *     "numbered_process_sections": [
 *       {
 *         "numbered_process_heading": "Our Process: From Data to Delivery",
 *         "numbered_process_description": "<p>Section intro.<\/p>",
 *         "numbered_process_items": [
 *           {
 *             "numbered_process_item_title": "Data Intake & Review",
 *             "numbered_process_item_description": "<p>Step details.<\/p>"
 *           }
 *         ]
 *       }
 *     ],
 *     "service_links_sections": [
 *       {
 *         "service_overview_heading": "Service Overview",
 *         "service_overview_content": "<p>Overview copy.<\/p>",
 *         "service_links_heading": "Engraved Products for Ashburn",
 *         "service_links_layout": "three-column",
 *         "service_links_items": [
 *           {
 *             "service_link_image": 123,
 *             "service_link_title": "Phenolic Labels",
 *             "service_link_body": "<p>Card body.<\/p>",
 *             "service_link_text": "Ashburn VA Phenolic Labels",
 *             "service_link_url": "\/services\/phenolic-labels\/"
 *           }
 *         ]
 *       }
 *     ],
 *     "area_coverage_sections": [
 *       {
 *         "area_coverage_heading": "Shipping to Ashburn and Northern Virginia Data Centers",
 *         "area_coverage_description": "<p>Intro copy.<\/p>",
 *         "area_coverage_items": [
 *           {
 *             "area_coverage_label": "Ashburn\n(Data Center Alley)"
 *           }
 *         ],
 *         "area_coverage_cta": {
 *           "title": "Get a Fast Quote",
 *           "url": "\/contact\/",
 *           "target": "_self"
 *         }
 *       }
 *     ],
 *     "industry_static_image": 123
 *   }
 * }
 */
function atomic_design_get_rest_post_types()
{
    // CPTs that carry shared template ACF fields (imported via n8n REST API).
    return ['service', 'industry', 'location', 'service-location'];
}

function atomic_design_get_allowed_template_acf_fields()
{
    return [
        'hero_title',
        'hero_subtitle',
        'hero_primary_link',
        'hero_secondary_link',
        'hero_media',
        'faqs_section_heading',
        'faq_layout',
        'faq_items',
        'why_choose_sections',
        'title_description_sections',
        'numbered_process_sections',
        'service_links_sections',
        'area_coverage_sections',
        'industry_static_image',
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

function atomic_design_get_template_acf_for_rest($object)
{
    if (!function_exists('get_field')) {
        return [];
    }

    $post_id  = isset($object['id']) ? (int) $object['id'] : 0;
    $response = [];

    foreach (atomic_design_get_allowed_template_acf_fields() as $field_name) {
        $response[$field_name] = get_field($field_name, $post_id);
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
                update_field($field_name, $field_value, $post->ID);
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
    // Industry Solutions block
    // Same partial & CSS as on CPT pages. Use on normal pages.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'industry-solutions',
            'title'           => __('Industry Solutions', 'atomic-design'),
            'description'     => __('Industry solutions grid from Synced Components → Industry Solutions. Same section as on service/location pages.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/industry-solutions/industry-solutions.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'grid-view',
            'keywords'        => ['industry', 'solutions', 'grid'],
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
    // Why Choose Grid block
    // Manual Gutenberg section for normal pages/posts.
    // CPT templates use the separate why_choose_sections repeater flow.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'why-choose-grid',
            'title'           => __('Why Choose Grid', 'atomic-design'),
            'description'     => __('Heading plus a two-column list of reasons, benefits, or differentiators.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/why-choose-grid/why-choose-grid.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'screenoptions',
            'keywords'        => ['why choose', 'benefits', 'features', 'reasons'],
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
    // Title + Description Columns block
    // Reusable heading + rich text section with auto-split columns.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'title-description-columns',
            'title'           => __('Title + Description Columns', 'atomic-design'),
            'description'     => __('Centered heading with rich text automatically split into two columns.', 'atomic-design'),
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
    // Numbered Process Grid block
    // Heading + intro + auto-numbered process cards.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'numbered-process-grid',
            'title'           => __('Numbered Process Grid', 'atomic-design'),
            'description'     => __('Section with heading, intro, and automatically numbered process steps.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/numbered-process-grid/numbered-process-grid.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'list-view',
            'keywords'        => ['process', 'steps', 'numbered', 'workflow'],
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
    // Service Links Grid block
    // Overview copy + linked service cards with optional images.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'service-links-grid',
            'title'           => __('Service Links Grid', 'atomic-design'),
            'description'     => __('Overview copy plus a grid of linked service cards.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/service-links-grid/service-links-grid.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'images-alt2',
            'keywords'        => ['services', 'links', 'cards', 'grid', 'products'],
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
    // Area Coverage Grid block
    // Heading, intro text, list of served areas, and optional CTA.
    // ----------------------------------------------------------
    acf_register_block_type(
        [
            'name'            => 'area-coverage-grid',
            'title'           => __('Area Coverage Grid', 'atomic-design'),
            'description'     => __('Location coverage section with area labels and an optional CTA.', 'atomic-design'),
            'render_template' => get_template_directory() . '/blocks/area-coverage-grid/area-coverage-grid.php',
            'category'        => 'atomic-blocks',
            'icon'            => 'location-alt',
            'keywords'        => ['areas', 'coverage', 'location', 'shipping'],
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
 * Restrict certain custom ACF blocks to intended editor contexts.
 *
 * Example: Why Choose Grid is for normal static content pages/posts,
 * not the CPT template-driven flows that already use dedicated repeaters.
 */
function atomic_design_limit_custom_blocks($allowed_blocks, $block_editor_context)
{
    if (
        !is_array($allowed_blocks)
        || !isset($block_editor_context->post)
        || !is_object($block_editor_context->post)
    ) {
        return $allowed_blocks;
    }

    $post_type = $block_editor_context->post->post_type ?? '';

    if ($post_type !== 'post' && $post_type !== 'page') {
        $allowed_blocks = array_values(
            array_filter(
                $allowed_blocks,
                static function ($block_name) {
                    return $block_name !== 'acf/why-choose-grid';
                }
            )
        );
    }

    return $allowed_blocks;
}
add_filter('allowed_block_types_all', 'atomic_design_limit_custom_blocks', 10, 2);

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
