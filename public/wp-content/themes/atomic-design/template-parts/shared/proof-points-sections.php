<?php
/**
 * Shared loop for repeatable Proof Points sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field proof_points_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$proof_points_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('proof_points_sections', $proof_points_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['proof_points_heading']) ? (string) $section['proof_points_heading'] : '';
    $intro_title     = isset($section['proof_points_intro_title']) ? (string) $section['proof_points_intro_title'] : '';
    $intro_copy      = isset($section['proof_points_intro_copy']) ? (string) $section['proof_points_intro_copy'] : '';
    $items           = isset($section['proof_points_items']) && is_array($section['proof_points_items'])
        ? $section['proof_points_items']
        : [];

    if (trim($section_heading) === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/proof-points',
        null,
        [
            'section_heading' => $section_heading,
            'intro_title'     => $intro_title,
            'intro_copy'      => $intro_copy,
            'items'           => $items,
        ]
    );
}
