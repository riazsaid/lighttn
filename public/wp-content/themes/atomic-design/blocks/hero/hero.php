<?php
/**
 * Hero Block Template (acf/hero)
 *
 * Fields are attached via ACF JSON and appear in the block sidebar.
 *
 * @param array  $block      Block settings and attributes.
 * @param string $content    Block inner HTML (unused for ACF blocks).
 * @param bool   $is_preview True during Gutenberg preview.
 * @param int    $post_id    Current post ID.
 */

if (!function_exists('get_field')) {
    return;
}

$title     = get_field('hero_title') ?: '';
$subtitle  = get_field('hero_subtitle') ?: '';
$bg_image  = get_field('hero_media'); // Reused field: now treated as the background image.

if ($is_preview && empty($title) && empty($subtitle)) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Hero</strong><br>Add a title/subtitle in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($title) && empty($subtitle)) {
    return;
}

$bg_url = '';
if (!empty($bg_image) && is_array($bg_image) && !empty($bg_image['url'])) {
    $bg_url = $bg_image['url'];
}

get_template_part(
    'template-parts/shared/hero',
    null,
    [
        'title'      => $title,
        'subtitle'   => $subtitle,
        'bg_url'     => $bg_url,
        'align'      => !empty($block['align']) ? $block['align'] : 'full',
        'class_name' => !empty($block['className']) ? $block['className'] : '',
    ]
);
