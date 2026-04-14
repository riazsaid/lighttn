<?php
/**
 * Service Links Grid Block Template (acf/service-links-grid)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$overview_heading = get_field('service_overview_heading') ?: '';
$overview_content = get_field('service_overview_content') ?: '';
$links_heading    = get_field('service_links_heading') ?: '';
$layout           = get_field('service_links_layout') ?: 'three-column';
$items            = get_field('service_links_items');

if ($is_preview && (empty($items) || !is_array($items))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Service Links Grid</strong><br>Add service cards in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($items) || !is_array($items)) {
    return;
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
        'align'            => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'       => !empty($block['className']) ? $block['className'] : '',
    ]
);
