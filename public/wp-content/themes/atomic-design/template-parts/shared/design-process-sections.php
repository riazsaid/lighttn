<?php
/**
 * Shared loop for repeatable Design Process sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field design_process_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$design_process_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('design_process_sections', $design_process_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['design_process_heading']) ? (string) $section['design_process_heading'] : '';
    $steps           = isset($section['design_process_steps']) && is_array($section['design_process_steps'])
        ? $section['design_process_steps']
        : [];

    if (trim($section_heading) === '' || empty($steps)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/design-process',
        null,
        [
            'section_heading' => $section_heading,
            'steps'           => $steps,
        ]
    );
}
