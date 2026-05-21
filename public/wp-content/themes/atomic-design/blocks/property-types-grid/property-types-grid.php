<?php
/**
 * Property Types Grid Block Template (acf/property-types-grid)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('property_types_grid_heading') ?: '';
$items           = get_field('property_types_grid_items') ?: [];

if ($is_preview && (empty($section_heading) || empty($items) || !is_array($items))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Property Types Grid</strong><br>Add a heading and property cards in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || empty($items) || !is_array($items)) {
    return;
}

get_template_part(
    'template-parts/shared/property-types-grid',
    null,
    [
        'section_heading' => $section_heading,
        'items'           => $items,
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
