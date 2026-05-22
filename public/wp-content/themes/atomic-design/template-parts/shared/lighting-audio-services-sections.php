<?php
/**
 * Shared loop for repeatable Lighting & Audio Services sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field lighting_audio_services_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$lighting_audio_services_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('lighting_audio_services_sections', $lighting_audio_services_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $heading           = isset($section['lighting_audio_services_heading']) ? (string) $section['lighting_audio_services_heading'] : '';
    $heading_alignment = isset($section['lighting_audio_services_heading_alignment']) ? (string) $section['lighting_audio_services_heading_alignment'] : 'center';
    $items             = isset($section['lighting_audio_services_items']) && is_array($section['lighting_audio_services_items'])
        ? $section['lighting_audio_services_items']
        : [];

    if (trim($heading) === '' || empty($items)) {
        continue;
    }

    get_template_part(
        'template-parts/shared/lighting-audio-services',
        null,
        [
            'heading'           => $heading,
            'heading_alignment' => $heading_alignment,
            'items'             => $items,
        ]
    );
}
