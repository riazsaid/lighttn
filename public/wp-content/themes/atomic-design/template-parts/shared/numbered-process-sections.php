<?php
/**
 * Shared loop for repeatable numbered process sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field numbered_process_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$process_sections_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('numbered_process_sections', $process_sections_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading     = isset($section['numbered_process_heading']) ? (string) $section['numbered_process_heading'] : '';
    $section_description = isset($section['numbered_process_description']) ? (string) $section['numbered_process_description'] : '';
    $layout              = isset($section['numbered_process_layout']) ? (string) $section['numbered_process_layout'] : 'three-column';
    $items               = isset($section['numbered_process_items']) && is_array($section['numbered_process_items'])
        ? $section['numbered_process_items']
        : [];

    if ($section_heading === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/numbered-process-grid',
        null,
        [
            'section_heading'     => $section_heading,
            'section_description' => $section_description,
            'layout'              => $layout,
            'items'               => $items,
        ]
    );
}
