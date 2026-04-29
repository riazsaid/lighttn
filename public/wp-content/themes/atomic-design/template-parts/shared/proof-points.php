<?php
/**
 * Shared Proof Points section.
 *
 * Args:
 * - section_heading (string) Required.
 * - intro_title (string) Optional.
 * - intro_copy (string) Optional HTML.
 * - items (array) Required repeater rows: title, description, image.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$intro_title     = isset($args['intro_title']) ? trim((string) $args['intro_title']) : '';
$intro_copy      = isset($args['intro_copy']) ? trim((string) $args['intro_copy']) : '';
$items           = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$items = array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim(wp_strip_all_tags((string) $item['description'])) : '';
    $image       = isset($item['image']) && is_array($item['image']) ? $item['image'] : [];

    return $title !== '' || $description !== '' || !empty($image['url']);
}));

if ($section_heading === '' || empty($items)) {
    return;
}

$section_class = trim('proof-points align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container proof-points__inner">
        <header class="proof-points__header scroll-reveal">
            <h2 class="proof-points__heading"><?php echo esc_html($section_heading); ?></h2>
        </header>

        <div class="proof-points__grid">
            <div class="proof-points__intro scroll-reveal" style="--reveal-delay: 70ms;">
                <?php if ($intro_title !== '') : ?>
                    <h3 class="proof-points__intro-title"><?php echo esc_html($intro_title); ?></h3>
                <?php endif; ?>

                <?php if (trim(wp_strip_all_tags($intro_copy)) !== '') : ?>
                    <div class="proof-points__intro-copy">
                        <?php echo wp_kses_post(wpautop($intro_copy)); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php foreach ($items as $index => $item) :
                $title       = isset($item['title']) ? trim((string) $item['title']) : '';
                $description = isset($item['description']) ? trim((string) $item['description']) : '';
                $image       = isset($item['image']) && is_array($item['image']) ? $item['image'] : [];
                $delay       = 120 + ((int) $index * 70);
                ?>
                <article class="proof-points__card scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                    <?php if (!empty($image['url'])) : ?>
                        <div class="proof-points__image-wrap">
                            <img
                                class="proof-points__image"
                                src="<?php echo esc_url($image['url']); ?>"
                                alt="<?php echo esc_attr($image['alt'] ?? $title); ?>"
                                loading="lazy"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ($title !== '') : ?>
                        <h3 class="proof-points__card-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if (trim(wp_strip_all_tags($description)) !== '') : ?>
                        <div class="proof-points__card-description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
