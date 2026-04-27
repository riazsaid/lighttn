<?php
/**
 * Shared loop for repeatable Insight Columns sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field insight_columns_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$insight_columns_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('insight_columns_sections', $insight_columns_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['insight_columns_heading']) ? (string) $section['insight_columns_heading'] : '';
    $intro           = isset($section['insight_columns_intro']) ? (string) $section['insight_columns_intro'] : '';
    $items           = isset($section['insight_columns_items']) && is_array($section['insight_columns_items'])
        ? $section['insight_columns_items']
        : [];

    if (trim($section_heading) === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/insight-columns',
        null,
        [
            'section_heading' => $section_heading,
            'intro'           => $intro,
            'items'           => $items,
        ]
    );
}
