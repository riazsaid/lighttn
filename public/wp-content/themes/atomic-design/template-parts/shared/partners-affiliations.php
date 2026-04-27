<?php
/**
 * Partners & Affiliations shared partial.
 *
 * Pulls from Synced Components -> Partners & Affiliations.
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_field')) {
    return;
}

$groups = [
    [
        'heading' => get_field('partners_heading', 'option') ?: __('Partners', 'atomic-design'),
        'items'   => get_field('partners_items', 'option') ?: [],
    ],
    [
        'heading' => get_field('affiliations_heading', 'option') ?: __('Affiliations', 'atomic-design'),
        'items'   => get_field('affiliations_items', 'option') ?: [],
    ],
];

$has_items = false;
foreach ($groups as $group) {
    if (!empty($group['items']) && is_array($group['items'])) {
        $has_items = true;
        break;
    }
}

if (!$has_items) {
    return;
}
?>
<section class="partners-affiliations-block">
    <div class="container partners-affiliations-block__inner">
        <?php foreach ($groups as $group) :
            $items = is_array($group['items']) ? $group['items'] : [];
            $items = array_values(array_filter($items, static function ($item) {
                $name = isset($item['name']) ? trim((string) $item['name']) : '';
                $logo = $item['logo'] ?? null;
                $logo_id = is_array($logo) && !empty($logo['ID']) ? (int) $logo['ID'] : 0;
                $logo_url = is_array($logo) && !empty($logo['url']) ? $logo['url'] : '';

                return $logo_id || $logo_url || $name !== '';
            }));

            if (empty($items)) {
                continue;
            }
            ?>
            <?php
            $is_carousel = count($items) > 3;
            $list_class   = $is_carousel ? 'partners-affiliations-block__track' : 'partners-affiliations-block__grid';
            ?>
            <div class="partners-affiliations-block__group">
                <?php if (!empty($group['heading'])) : ?>
                    <h2 class="partners-affiliations-block__heading"><?php echo esc_html($group['heading']); ?></h2>
                <?php endif; ?>

                <?php if ($is_carousel) : ?>
                    <div class="partners-affiliations-block__carousel"
                         data-partners-carousel
                         aria-label="<?php echo esc_attr($group['heading']); ?>">
                        <div class="partners-affiliations-block__viewport">
                <?php endif; ?>

                <div class="<?php echo esc_attr($list_class); ?>">
                    <?php foreach ($items as $item) :
                        $name = isset($item['name']) ? trim((string) $item['name']) : '';
                        $logo = $item['logo'] ?? null;
                        $link = isset($item['link']) ? trim((string) $item['link']) : '';
                        $logo_id = is_array($logo) && !empty($logo['ID']) ? (int) $logo['ID'] : 0;
                        $logo_url = is_array($logo) && !empty($logo['url']) ? $logo['url'] : '';

                        if (!$logo_id && !$logo_url && $name === '') {
                            continue;
                        }

                        $tag = $link !== '' ? 'a' : 'div';
                        $attrs = $link !== '' ? ' href="' . esc_url($link) . '"' : '';
                        ?>
                        <<?php echo esc_html($tag); ?> class="partners-affiliations-block__card"<?php echo $attrs; ?>>
                            <?php if ($logo_id) : ?>
                                <?php echo wp_get_attachment_image($logo_id, 'medium', false, [
                                    'class' => 'partners-affiliations-block__logo',
                                    'alt'   => $name,
                                ]); ?>
                            <?php elseif ($logo_url) : ?>
                                <img class="partners-affiliations-block__logo"
                                     src="<?php echo esc_url($logo_url); ?>"
                                     alt="<?php echo esc_attr($name); ?>"
                                     loading="lazy" />
                            <?php elseif ($name !== '') : ?>
                                <span class="partners-affiliations-block__fallback"><?php echo esc_html($name); ?></span>
                            <?php endif; ?>
                        </<?php echo esc_html($tag); ?>>
                    <?php endforeach; ?>
                </div>

                <?php if ($is_carousel) : ?>
                        </div>
                        <div class="partners-affiliations-block__dots" aria-hidden="false"></div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
