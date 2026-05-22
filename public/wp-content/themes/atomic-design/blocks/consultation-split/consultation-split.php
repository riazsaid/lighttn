<?php
/**
 * Consultation Split Block Template (acf/consultation-split)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

get_template_part(
    'template-parts/shared/consultation-split',
    null,
    [
        'align'      => !empty($block['align']) ? $block['align'] : 'full',
        'class_name' => !empty($block['className']) ? $block['className'] : '',
    ]
);
