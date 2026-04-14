<?php
/**
 * Industry Solutions Block (acf/industry-solutions)
 *
 * Uses the same partial as CPT template pages. One HTML source, one CSS file.
 * Data from Synced Components → Industry Solutions.
 */
if (!function_exists('get_field')) {
    return;
}

if (!empty($is_preview)) {
    printf(
        '<div style="padding:16px 18px;border-left:4px solid #f0ad4e;background:#fff8e5;color:#6b4f00;font-size:13px;line-height:1.6;"><strong>Industry Solutions</strong><br>This block uses global content. Update it at <a href="%s" target="_blank" style="font-weight:600;color:#8a5a00;text-decoration:underline;">Synced Components → Industry Solutions</a>.</div>',
        esc_url(admin_url('admin.php?page=atomic-design-industry-solutions'))
    );
    return;
}

$items = get_field('industry_solutions_list', 'option');
get_template_part('template-parts/shared/industry-solutions');
