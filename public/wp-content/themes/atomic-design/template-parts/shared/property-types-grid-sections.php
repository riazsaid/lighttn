<?php
/**
 * Shared loop for repeatable Property Types Grid sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field property_types_grid_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$property_types_grid_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('property_types_grid_sections', $property_types_grid_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['property_types_grid_heading']) ? (string) $section['property_types_grid_heading'] : '';
    $items           = isset($section['property_types_grid_items']) && is_array($section['property_types_grid_items'])
        ? $section['property_types_grid_items']
        : [];

    if (trim($section_heading) === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/property-types-grid',
        null,
        [
            'section_heading' => $section_heading,
            'items'           => $items,
        ]
    );
}

