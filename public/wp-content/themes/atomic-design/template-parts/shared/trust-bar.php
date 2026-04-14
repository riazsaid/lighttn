<?php
/**
 * Trust Bar (shared partial)
 *
 * Pulls from Synced Components → Trust Bar.
 * Icon + single text per item. Wraps by column width.
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!function_exists('get_field')) {
    return;
}
$items = get_field('trust_bar_items', 'option');
if (empty($items) || !is_array($items)) {
    return;
}
?>
<section class="trust-bar-block" aria-label="<?php esc_attr_e('Key facts', 'atomic-design'); ?>">
    <div class="container trust-bar-block__inner">
        <div class="trust-bar-block__grid">
            <?php foreach ($items as $item) :
                $icon = $item['item_icon'] ?? null;
                $text = $item['item_text'] ?? '';
                $icon_url = is_array($icon) && !empty($icon['url']) ? $icon['url'] : '';
                if (empty($text) && empty($icon_url)) {
                    continue;
                }
            ?>
                <div class="trust-bar-block__item">
                    <?php if ($icon_url) : ?>
                        <div class="trust-bar-block__icon">
                            <img src="<?php echo esc_url($icon_url); ?>"
                                 alt=""
                                 width="56" height="56"
                                 loading="lazy">
                        </div>
                    <?php endif; ?>
                    <?php if ($text) : ?>
                        <div class="trust-bar-block__text"><?php echo esc_html($text); ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
