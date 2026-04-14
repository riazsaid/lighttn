<?php
/**
 * Trust Bar Block (acf/trust-bar)
 *
 * Same partial & CSS as on CPT pages. Use on static pages.
 */
if (!function_exists('get_field')) {
    return;
}

if (!empty($is_preview)) {
    printf(
        '<div style="padding:16px 18px;border-left:4px solid #f0ad4e;background:#fff8e5;color:#6b4f00;font-size:13px;line-height:1.6;"><strong>Trust Bar</strong><br>This block uses global content. Update it at <a href="%s" target="_blank" style="font-weight:600;color:#8a5a00;text-decoration:underline;">Synced Components → Trust Bar</a>.</div>',
        esc_url(admin_url('admin.php?page=atomic-design-trust-bar'))
    );
    return;
}

$items = get_field('trust_bar_items', 'option');
get_template_part('template-parts/shared/trust-bar');
