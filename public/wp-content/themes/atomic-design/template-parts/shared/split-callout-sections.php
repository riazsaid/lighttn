<?php
/**
 * Shared loop for repeatable Split Callout sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field split_callout_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$split_callout_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('split_callout_sections', $split_callout_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['split_callout_heading']) ? (string) $section['split_callout_heading'] : '';
    $intro           = isset($section['split_callout_intro']) ? (string) $section['split_callout_intro'] : '';
    $callout_link    = isset($section['split_callout_link']) && is_array($section['split_callout_link'])
        ? $section['split_callout_link']
        : [];
    $panel_title     = isset($section['split_callout_panel_title']) ? (string) $section['split_callout_panel_title'] : '';
    $panel_copy      = isset($section['split_callout_panel_copy']) ? (string) $section['split_callout_panel_copy'] : '';

    if (trim($section_heading) === '' || trim(wp_strip_all_tags($intro)) === '') {
        continue;
    }

    get_template_part(
        'template-parts/shared/split-callout',
        null,
        [
            'section_heading' => $section_heading,
            'intro'           => $intro,
            'callout_link'    => $callout_link,
            'panel_title'     => $panel_title,
            'panel_copy'      => $panel_copy,
        ]
    );
}
