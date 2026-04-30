<?php
/**
 * Shared Spotlight Cards section.
 *
 * Args:
 * - section_heading (string) Required.
 * - intro (string) Required HTML/text.
 * - image (array) Optional ACF image array.
 * - items (array) Required repeater rows: title, description.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$intro           = isset($args['intro']) ? trim((string) $args['intro']) : '';
$image           = isset($args['image']) && is_array($args['image']) ? $args['image'] : [];
$items           = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$items = array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim(wp_strip_all_tags((string) $item['description'])) : '';
    return $title !== '' || $description !== '';
}));

$image_id  = !empty($image['ID']) ? (int) $image['ID'] : 0;
$image_url = !empty($image['url']) ? (string) $image['url'] : '';
$image_alt = !empty($image['alt']) ? (string) $image['alt'] : '';

if ($section_heading === '' || trim(wp_strip_all_tags($intro)) === '' || (!$image_id && $image_url === '') || empty($items)) {
    return;
}

$section_class = trim('spotlight-cards align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container spotlight-cards__inner">
        <div class="spotlight-cards__top">
            <div class="spotlight-cards__intro scroll-reveal" style="--reveal-delay: 70ms;">
                <h2 class="spotlight-cards__heading"><?php echo esc_html($section_heading); ?></h2>

                <div class="spotlight-cards__intro-copy">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>
            </div>

            <div class="spotlight-cards__media scroll-reveal" style="--reveal-delay: 120ms;">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'large', false, [
                        'class' => 'spotlight-cards__image',
                        'alt'   => $image_alt,
                    ]); ?>
                <?php else : ?>
                    <img
                        class="spotlight-cards__image"
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        loading="lazy"
                    />
                <?php endif; ?>
            </div>
        </div>

        <div class="spotlight-cards__grid">
            <?php foreach ($items as $index => $item) :
                $title       = isset($item['title']) ? trim((string) $item['title']) : '';
                $description = isset($item['description']) ? trim((string) $item['description']) : '';
                $delay       = 140 + ((int) $index * 70);
                ?>
                <article class="spotlight-cards__card scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                    <?php if ($title !== '') : ?>
                        <h3 class="spotlight-cards__card-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if (trim(wp_strip_all_tags($description)) !== '') : ?>
                        <div class="spotlight-cards__card-description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
