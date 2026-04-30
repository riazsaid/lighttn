<?php
/**
 * Spotlight Cards Block Template (acf/spotlight-cards)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('spotlight_cards_heading') ?: '';
$intro           = get_field('spotlight_cards_intro') ?: '';
$image           = get_field('spotlight_cards_image') ?: [];
$items           = get_field('spotlight_cards_items') ?: [];

$has_image = is_array($image) && (!empty($image['ID']) || !empty($image['url']));

if ($is_preview && (empty($section_heading) || trim(wp_strip_all_tags((string) $intro)) === '' || !$has_image || empty($items) || !is_array($items))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Spotlight Cards</strong><br>Add the heading, intro copy, image, and supporting cards in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || trim(wp_strip_all_tags((string) $intro)) === '' || !$has_image || empty($items) || !is_array($items)) {
    return;
}

get_template_part(
    'template-parts/shared/spotlight-cards',
    null,
    [
        'section_heading' => $section_heading,
        'intro'           => $intro,
        'image'           => $image,
        'items'           => $items,
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
