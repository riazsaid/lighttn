<?php
/**
 * Shared loop for repeatable title + rich text sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field title_description_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 * - class_name (string) Optional extra class added to section wrapper.
 */

if (!defined('ABSPATH')) {
    exit;
}

$title_description_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('title_description_sections', $title_description_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;
$class_name = isset($args['class_name']) ? (string) $args['class_name'] : '';

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['title_description_heading']) ? (string) $section['title_description_heading'] : '';
    $description     = isset($section['title_description_content']) ? (string) $section['title_description_content'] : '';
    $cta             = isset($section['title_description_cta']) && is_array($section['title_description_cta'])
        ? $section['title_description_cta']
        : [];

    if (trim($section_heading) === '' || trim(wp_strip_all_tags($description)) === '') {
        continue;
    }

    get_template_part(
        'template-parts/shared/title-description-columns',
        null,
        [
            'section_heading' => $section_heading,
            'description'     => $description,
            'cta'             => $cta,
            'class_name'      => $class_name,
        ]
    );
}
