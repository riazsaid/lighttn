<?php
/**
 * Shared loop for repeatable area coverage sections on location templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field area_coverage_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$area_coverage_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('area_coverage_sections', $area_coverage_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['area_coverage_heading']) ? (string) $section['area_coverage_heading'] : '';
    $section_description = isset($section['area_coverage_description']) ? (string) $section['area_coverage_description'] : '';
    $areas = isset($section['area_coverage_items']) && is_array($section['area_coverage_items'])
        ? $section['area_coverage_items']
        : [];
    $cta = isset($section['area_coverage_cta']) && is_array($section['area_coverage_cta'])
        ? $section['area_coverage_cta']
        : [];

    if ($section_heading === '' || empty($areas)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/area-coverage-grid',
        null,
        [
            'section_heading' => $section_heading,
            'section_description' => $section_description,
            'areas' => $areas,
            'cta' => $cta,
        ]
    );
}
