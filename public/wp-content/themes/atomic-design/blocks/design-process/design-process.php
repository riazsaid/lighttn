<?php
/**
 * Design Process Block Template (acf/design-process)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('design_process_heading') ?: '';
$steps           = get_field('design_process_steps') ?: [];

if ($is_preview && (empty($section_heading) || empty($steps) || !is_array($steps))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Design Process</strong><br>Add a heading and process steps in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || empty($steps) || !is_array($steps)) {
    return;
}

get_template_part(
    'template-parts/shared/design-process',
    null,
    [
        'section_heading' => $section_heading,
        'steps'           => $steps,
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
