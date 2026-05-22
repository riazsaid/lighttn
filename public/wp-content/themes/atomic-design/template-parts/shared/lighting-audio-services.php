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

$heading_alignment = isset($args['heading_alignment']) ? (string) $args['heading_alignment'] : 'center';
$heading_alignment = in_array($heading_alignment, ['left', 'center'], true) ? $heading_alignment : 'center';
$section_classes = [
    'lighting-audio-services-block',
    'scroll-reveal',
    'lighting-audio-services-block--heading-' . $heading_alignment,
];

$items = is_array($items) ? array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim((string) $item['description']) : '';
    $link        = isset($item['link']) && is_array($item['link']) ? $item['link'] : [];
    $image       = $item['image'] ?? null;
    $image_id    = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;
    $image_url   = is_array($image) && !empty($image['url']) ? (string) $image['url'] : '';

    return $title !== '' || $description !== '' || !empty($link['url']) || $image_id > 0 || $image_url !== '';
})) : [];

if ($heading === '' || empty($items)) {
    return;
}
?>
<section class="<?php echo esc_attr(implode(' ', $section_classes)); ?>">
    <div class="container lighting-audio-services-block__inner">
        <h2 class="lighting-audio-services-block__heading scroll-reveal" style="--reveal-delay: 60ms;"><?php echo esc_html($heading); ?></h2>

        <div class="lighting-audio-services-block__grid">
            <?php foreach ($items as $item_index => $item) :
                $image    = $item['image'] ?? null;
                $image_id = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;
                $image_url = is_array($image) && !empty($image['url']) ? (string) $image['url'] : '';
                $image_alt = is_array($image) && !empty($image['alt']) ? (string) $image['alt'] : '';
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
                <article class="lighting-audio-services-block__item scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $card_delay); ?>ms;">
                    <?php if ($image_id || $image_url !== '') : ?>
                        <div class="lighting-audio-services-block__media">
                            <?php if ($image_id) : ?>
                                <?php echo wp_get_attachment_image($image_id, 'large', false, [
                                    'class' => 'lighting-audio-services-block__image',
                                    'alt'   => $image_alt,
                                ]); ?>
                            <?php else : ?>
                                <img class="lighting-audio-services-block__image"
                                     src="<?php echo esc_url($image_url); ?>"
                                     alt="<?php echo esc_attr($image_alt); ?>"
                                     loading="lazy" />
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($title !== '') : ?>
                        <h3 class="lighting-audio-services-block__title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($description !== '') : ?>
                        <div class="lighting-audio-services-block__description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($link_url !== '' && $link_title !== '') : ?>
                        <a class="lighting-audio-services-block__link"
                           href="<?php echo esc_url($link_url); ?>"
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
