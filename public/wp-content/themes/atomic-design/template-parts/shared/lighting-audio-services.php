<?php
/**
 * Lighting & Audio Services shared partial.
 *
 * Pulls from passed args, or falls back to Synced Components -> Lighting & Audio Services.
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_field')) {
    return;
}

$heading = isset($args['heading'])
    ? trim((string) $args['heading'])
    : (get_field('lighting_audio_services_heading', 'option') ?: __('Outdoor Lighting & Audio Services', 'atomic-design'));

$items = isset($args['items']) && is_array($args['items'])
    ? $args['items']
    : (get_field('lighting_audio_services_items', 'option') ?: []);

$fallback_items = isset($args['fallback_items']) && is_array($args['fallback_items'])
    ? $args['fallback_items']
    : [];

$max_items = isset($args['max_items']) ? (int) $args['max_items'] : 0;
$show_numbers = !empty($args['show_numbers']);
$heading_alignment = isset($args['heading_alignment']) ? (string) $args['heading_alignment'] : 'center';
$heading_alignment = in_array($heading_alignment, ['left', 'center'], true) ? $heading_alignment : 'center';
$section_classes = [
    'lighting-audio-services-block',
    'scroll-reveal',
    'lighting-audio-services-block--heading-' . $heading_alignment,
];

if ($show_numbers) {
    $section_classes[] = 'lighting-audio-services-block--numbered';
}

$items = is_array($items) ? array_values(array_filter($items, static function ($item) {
    $title = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim((string) $item['description']) : '';
    $link = isset($item['link']) && is_array($item['link']) ? $item['link'] : [];
    $image = $item['image'] ?? null;
    $image_id = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;
    $image_url = is_array($image) && !empty($image['url']) ? (string) $image['url'] : '';

    return $title !== '' || $description !== '' || !empty($link['url']) || $image_id > 0 || $image_url !== '';
})) : [];

if ($max_items > 0) {
    $items = array_slice($items, 0, $max_items);
}

$get_item_image = static function ($item): array {
    $image = is_array($item) ? ($item['image'] ?? null) : null;
    $image_id = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;
    $image_url = is_array($image) && !empty($image['url']) ? (string) $image['url'] : '';
    $image_alt = is_array($image) && !empty($image['alt']) ? (string) $image['alt'] : '';

    return [
        'image' => $image,
        'id'    => $image_id,
        'url'   => $image_url,
        'alt'   => $image_alt,
    ];
};

$normalize_title = static function ($title): string {
    $title = strtolower(trim(wp_strip_all_tags((string) $title)));
    $title = preg_replace('/\s+/', ' ', $title);

    return is_string($title) ? $title : '';
};

$find_fallback_image = static function ($item, $item_index) use ($fallback_items, $get_item_image, $normalize_title): array {
    if (empty($fallback_items)) {
        return $get_item_image([]);
    }

    $title = is_array($item) && isset($item['title']) ? $normalize_title($item['title']) : '';

    if ($title !== '') {
        foreach ($fallback_items as $fallback_item) {
            if (!is_array($fallback_item) || empty($fallback_item['title'])) {
                continue;
            }

            $fallback_title = $normalize_title($fallback_item['title']);
            $fallback_image = $get_item_image($fallback_item);

            if ($fallback_title === '' || (!$fallback_image['id'] && $fallback_image['url'] === '')) {
                continue;
            }

            $title_starts_with_fallback = strpos($title, $fallback_title . ' in ') === 0
                || strpos($title, $fallback_title . ' for ') === 0
                || strpos($title, $fallback_title . ' near ') === 0;

            if ($title === $fallback_title || $title_starts_with_fallback) {
                return $fallback_image;
            }
        }
    }

    if (isset($fallback_items[$item_index])) {
        $fallback_image = $get_item_image($fallback_items[$item_index]);

        if ($fallback_image['id'] || $fallback_image['url'] !== '') {
            return $fallback_image;
        }
    }

    return $get_item_image([]);
};

if ($heading === '' || empty($items)) {
    return;
}
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>">
    <div class="container lighting-audio-services-block__inner">
        <h2 class="lighting-audio-services-block__heading scroll-reveal" style="--reveal-delay: 60ms;">
            <?php echo esc_html($heading); ?></h2>

        <div class="lighting-audio-services-block__grid">
            <?php foreach ($items as $item_index => $item):
                $image_data = $get_item_image($item);
                if (!$image_data['id'] && $image_data['url'] === '') {
                    $image_data = $find_fallback_image($item, $item_index);
                }
                $image = $image_data['image'];
                $image_id = $image_data['id'];
                $image_url = $image_data['url'];
                $image_alt = $image_data['alt'];
                $title = isset($item['title']) ? trim((string) $item['title']) : '';
                $description = isset($item['description']) ? trim((string) $item['description']) : '';
                $link = isset($item['link']) && is_array($item['link']) ? $item['link'] : [];
                $link_url = !empty($link['url']) ? (string) $link['url'] : '';
                $link_title = !empty($link['title']) ? (string) $link['title'] : '';
                $link_target = !empty($link['target']) ? (string) $link['target'] : '_self';
                $card_delay = 110 + ((int) $item_index * 70);

                if ($link_title === '' && $title !== '') {
                    $link_title = sprintf(__('Explore %s', 'atomic-design'), $title);
                }
                ?>
                <article class="lighting-audio-services-block__item scroll-reveal"
                    style="--reveal-delay: <?php echo esc_attr((string) $card_delay); ?>ms;">
                    <?php if ($image_id || $image_url !== ''): ?>
                        <div class="lighting-audio-services-block__media">
                            <?php if ($image_id): ?>
                                <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                    'class' => 'lighting-audio-services-block__image',
                                    'alt' => $image_alt,
                                ]); ?>
                            <?php else: ?>
                                <img class="lighting-audio-services-block__image" src="<?php echo esc_url($image_url); ?>"
                                    alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" />
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($title !== '') : ?>
                        <h3 class="lighting-audio-services-block__title">
                            <?php if ($show_numbers) : ?>
                                <span class="lighting-audio-services-block__number"><?php echo esc_html(str_pad((string) ($item_index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                            <?php endif; ?>
                            <span><?php echo esc_html($title); ?></span>
                        </h3>
                    <?php endif; ?>

                    <?php if ($description !== ''): ?>
                        <div class="lighting-audio-services-block__description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($link_url !== '' && $link_title !== ''): ?>
                        <a class="lighting-audio-services-block__link" href="<?php echo esc_url($link_url); ?>"
                            target="<?php echo esc_attr($link_target); ?>">
                            <span><?php echo esc_html($link_title); ?></span>
                            <span aria-hidden="true">→</span>
                        </a>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
