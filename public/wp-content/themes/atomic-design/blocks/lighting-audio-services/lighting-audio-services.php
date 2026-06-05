<?php
/**
 * Lighting & Audio Services Block (acf/lighting-audio-services)
 *
 * Renders synced service cards by default, with optional per-block custom cards.
 */
if (!function_exists('get_field')) {
    return;
}

$source = get_field('lighting_audio_services_source') ?: 'synced';
$source = in_array($source, ['synced', 'custom'], true) ? $source : 'synced';
$max_items = (int) (get_field('lighting_audio_services_max_items') ?: 0);
$show_numbers = (bool) get_field('lighting_audio_services_show_numbers');

if (!empty($is_preview)) {
    $source_label = $source === 'custom' ? __('Custom Cards', 'atomic-design') : __('Synced Options', 'atomic-design');
    $max_items_label = $max_items > 0 ? (string) $max_items : __('All', 'atomic-design');
    $numbers_label = $show_numbers ? __('On', 'atomic-design') : __('Off', 'atomic-design');
    ?>
    <div class="atomic-editor-notice" style="padding:18px 20px;border-left:4px solid #29B5E8;background:#f6fbfd;color:#2E3F4F;font-family:sans-serif;font-size:13px;line-height:1.55;">
        <strong style="display:block;margin-bottom:6px;font-size:14px;"><?php esc_html_e('Lighting & Audio Services', 'atomic-design'); ?></strong>
        <div><?php esc_html_e('This block renders on the frontend. Customize the display options in the block sidebar on the right.', 'atomic-design'); ?></div>
        <div style="margin-top:10px;">
            <strong><?php esc_html_e('Current settings:', 'atomic-design'); ?></strong>
            <?php echo esc_html(sprintf(__('Source: %1$s · Max items: %2$s · Numbers: %3$s', 'atomic-design'), $source_label, $max_items_label, $numbers_label)); ?>
        </div>
        <?php if ($source === 'synced') : ?>
            <div style="margin-top:8px;">
                <a href="<?php echo esc_url(admin_url('admin.php?page=atomic-design-lighting-audio-services')); ?>" target="_blank" style="font-weight:600;color:#1A9FD4;text-decoration:underline;">
                    <?php esc_html_e('Edit synced service cards', 'atomic-design'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return;
}

$args = [
    'max_items'    => $max_items,
    'show_numbers' => $show_numbers,
    'align'        => !empty($block['align']) ? $block['align'] : 'full',
    'class_name'   => !empty($block['className']) ? $block['className'] : '',
];

if ($source === 'custom') {
    $args['heading'] = get_field('lighting_audio_services_custom_heading') ?: '';
    $args['heading_alignment'] = get_field('lighting_audio_services_custom_heading_alignment') ?: 'center';
    $args['items'] = get_field('lighting_audio_services_custom_items') ?: [];
}

get_template_part('template-parts/shared/lighting-audio-services', null, $args);
