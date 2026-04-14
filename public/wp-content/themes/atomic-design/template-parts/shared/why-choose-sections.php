<?php
/**
 * Shared Why Choose sections loop for CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field why_choose_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$why_choose_sections_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('why_choose_sections', $why_choose_sections_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading     = isset($section['why_choose_heading']) ? (string) $section['why_choose_heading'] : '';
    $section_description = isset($section['why_choose_description']) ? (string) $section['why_choose_description'] : '';
    $layout              = isset($section['why_choose_layout']) ? (string) $section['why_choose_layout'] : 'two-column';
    $items               = isset($section['why_choose_items']) && is_array($section['why_choose_items'])
        ? $section['why_choose_items']
        : [];

    if ($section_heading === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/why-choose-grid',
        null,
        [
            'section_heading'     => $section_heading,
            'section_description' => $section_description,
            'layout'              => $layout,
            'items'               => $items,
        ]
    );
}
