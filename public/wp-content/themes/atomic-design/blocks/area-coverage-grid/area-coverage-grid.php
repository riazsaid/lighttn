<?php
/**
 * Area Coverage Grid Block Template (acf/area-coverage-grid)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('area_coverage_heading') ?: '';
$section_description = get_field('area_coverage_description') ?: '';
$areas = get_field('area_coverage_items');
$cta = get_field('area_coverage_cta');

if ($is_preview && (empty($section_heading) || empty($areas) || !is_array($areas))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Area Coverage Grid</strong><br>Add a heading and area labels in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || empty($areas) || !is_array($areas)) {
    return;
}

get_template_part(
    'template-parts/shared/area-coverage-grid',
    null,
    [
        'section_heading' => $section_heading,
        'section_description' => $section_description,
        'areas' => $areas,
        'cta' => $cta,
        'align' => !empty($block['align']) ? $block['align'] : 'full',
        'class_name' => !empty($block['className']) ? $block['className'] : '',
    ]
);
