<?php
/**
 * Shared loop for repeatable service links sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field service_links_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$service_links_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('service_links_sections', $service_links_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $overview_heading = isset($section['service_overview_heading']) ? (string) $section['service_overview_heading'] : '';
    $overview_content = isset($section['service_overview_content']) ? (string) $section['service_overview_content'] : '';
    $links_heading    = isset($section['service_links_heading']) ? (string) $section['service_links_heading'] : '';
    $layout           = isset($section['service_links_layout']) ? (string) $section['service_links_layout'] : 'three-column';
    $items            = isset($section['service_links_items']) && is_array($section['service_links_items'])
        ? $section['service_links_items']
        : [];

    if (empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/service-links-grid',
        null,
        [
            'overview_heading' => $overview_heading,
            'overview_content' => $overview_content,
            'links_heading'    => $links_heading,
            'layout'           => $layout,
            'items'            => $items,
        ]
    );
}
