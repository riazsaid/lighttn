<?php
/**
 * Trust Bar (shared partial)
 *
 * Pulls from Synced Components → Trust Bar.
 * Card layout: icon + title + description.
 * Backward compatible with legacy single text field.
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
<section class="trust-bar-block scroll-reveal" aria-label="<?php esc_attr_e('Key facts', 'atomic-design'); ?>">
    <div class="container trust-bar-block__inner">
        <div class="trust-bar-block__grid">
            <?php foreach ($items as $index => $item) :
                $icon = $item['item_icon'] ?? null;
                $icon_url = is_array($icon) && !empty($icon['url']) ? $icon['url'] : '';
                $icon_alt = is_array($icon) && !empty($icon['alt']) ? (string) $icon['alt'] : '';
                $title = isset($item['item_title']) ? trim((string) $item['item_title']) : '';
                $description = isset($item['item_description']) ? trim((string) $item['item_description']) : '';
                $legacy_text = isset($item['item_text']) ? trim((string) $item['item_text']) : '';

                // Fallback for existing rows that only have legacy single text.
                if ($title === '' && $legacy_text !== '') {
                    $title = $legacy_text;
                }

                $delay = 80 + ((int) $index * 70);
                if ($title === '' && $description === '' && empty($icon_url)) {
                    continue;
                }
            ?>
                <div class="trust-bar-block__item scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                    <?php if ($icon_url) : ?>
                        <div class="trust-bar-block__icon">
                            <img src="<?php echo esc_url($icon_url); ?>"
                                 alt="<?php echo esc_attr($icon_alt); ?>"
                                 width="56" height="56"
                                 loading="lazy">
                        </div>
                    <?php endif; ?>
                    <?php if ($title !== '') : ?>
                        <h3 class="trust-bar-block__title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                    <?php if ($description !== '') : ?>
                        <div class="trust-bar-block__description"><?php echo esc_html($description); ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
