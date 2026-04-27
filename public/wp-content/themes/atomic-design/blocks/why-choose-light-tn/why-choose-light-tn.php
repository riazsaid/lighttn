<?php
/**
 * Why Choose Light TN Block (acf/why-choose-light-tn)
 *
 * Renders the global icon-card section from Synced Components.
 */
if (!function_exists('get_field')) {
    return;
}

if (!empty($is_preview)) {
    printf(
        '<div style="padding:16px 18px;border-left:4px solid #29B5E8;background:#f6fbfd;color:#2E3F4F;font-size:13px;line-height:1.6;"><strong>Why Choose Light TN</strong><br>This block uses global content. Update it at <a href="%s" target="_blank" style="font-weight:600;color:#1A9FD4;text-decoration:underline;">Synced Components &rarr; Why Choose Light TN</a>.</div>',
        esc_url(admin_url('admin.php?page=atomic-design-why-choose-light-tn'))
    );
    return;
}

get_template_part(
    'template-parts/shared/why-choose-light-tn',
    null,
    [
        'align'      => !empty($block['align']) ? $block['align'] : 'full',
        'class_name' => !empty($block['className']) ? $block['className'] : '',
    ]
);
