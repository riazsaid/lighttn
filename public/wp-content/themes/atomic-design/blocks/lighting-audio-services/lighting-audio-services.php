<?php
/**
 * Lighting & Audio Services Block (acf/lighting-audio-services)
 *
 * Renders global service cards from Synced Components.
 */
if (!function_exists('get_field')) {
    return;
}

if (!empty($is_preview)) {
    printf(
        '<div style="padding:16px 18px;border-left:4px solid #29B5E8;background:#f6fbfd;color:#2E3F4F;font-size:13px;line-height:1.6;"><strong>Lighting &amp; Audio Services</strong><br>This block uses global content. Update it at <a href="%s" target="_blank" style="font-weight:600;color:#1A9FD4;text-decoration:underline;">Synced Components &rarr; Lighting &amp; Audio Services</a>.</div>',
        esc_url(admin_url('admin.php?page=atomic-design-lighting-audio-services'))
    );
    return;
}

get_template_part('template-parts/shared/lighting-audio-services');
