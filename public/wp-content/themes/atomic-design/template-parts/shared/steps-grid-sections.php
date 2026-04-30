<?php
/**
 * Shared loop for repeatable Steps Grid sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field steps_grid_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$steps_grid_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('steps_grid_sections', $steps_grid_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading   = isset($section['steps_grid_heading']) ? (string) $section['steps_grid_heading'] : '';
    $heading_alignment = isset($section['steps_grid_heading_alignment']) ? (string) $section['steps_grid_heading_alignment'] : 'center';
    $items             = isset($section['steps_grid_items']) && is_array($section['steps_grid_items'])
        ? $section['steps_grid_items']
        : [];

    if (trim($section_heading) === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/steps-grid',
        null,
        [
            'section_heading'   => $section_heading,
            'heading_alignment' => $heading_alignment,
            'items'             => $items,
        ]
    );
}
