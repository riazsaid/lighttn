<?php
/**
 * Shared loop for repeatable Spotlight Cards sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field spotlight_cards_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$spotlight_cards_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('spotlight_cards_sections', $spotlight_cards_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['spotlight_cards_heading']) ? (string) $section['spotlight_cards_heading'] : '';
    $intro           = isset($section['spotlight_cards_intro']) ? (string) $section['spotlight_cards_intro'] : '';
    $image           = isset($section['spotlight_cards_image']) && is_array($section['spotlight_cards_image'])
        ? $section['spotlight_cards_image']
        : [];
    $items           = isset($section['spotlight_cards_items']) && is_array($section['spotlight_cards_items'])
        ? $section['spotlight_cards_items']
        : [];

    if (trim($section_heading) === '' || trim(wp_strip_all_tags($intro)) === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/spotlight-cards',
        null,
        [
            'section_heading' => $section_heading,
            'intro'           => $intro,
            'image'           => $image,
            'items'           => $items,
        ]
    );
}
