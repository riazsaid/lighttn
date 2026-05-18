<?php
/**
 * Shared loop for repeatable Detail Card Grid sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field detail_card_grid_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$detail_card_grid_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('detail_card_grid_sections', $detail_card_grid_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['detail_card_grid_heading']) ? (string) $section['detail_card_grid_heading'] : '';
    $content         = isset($section['detail_card_grid_content']) ? (string) $section['detail_card_grid_content'] : '';
    $ideal_items     = isset($section['detail_card_grid_ideal_items']) && is_array($section['detail_card_grid_ideal_items'])
        ? $section['detail_card_grid_ideal_items']
        : [];
    $included_description = isset($section['detail_card_grid_included_description'])
        ? (string) $section['detail_card_grid_included_description']
        : '';
    $items           = isset($section['detail_card_grid_items']) && is_array($section['detail_card_grid_items'])
        ? $section['detail_card_grid_items']
        : [];
    $has_content     = trim(wp_strip_all_tags($content)) !== ''
        || !empty($ideal_items)
        || trim(wp_strip_all_tags($included_description)) !== '';

    if (trim($section_heading) === '' || !$has_content || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/detail-card-grid',
        null,
        [
            'section_heading' => $section_heading,
            'content'         => $content,
            'ideal_items'     => $ideal_items,
            'included_description' => $included_description,
            'items'           => $items,
        ]
    );
}
