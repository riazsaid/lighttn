<?php
/**
 * About Light TN shared partial.
 *
 * Pulls from Synced Components -> About Light TN.
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_field')) {
    return;
}

$heading           = get_field('about_light_tn_heading', 'option') ?: __('About Light TN', 'atomic-design');
$intro_copy        = get_field('about_light_tn_intro_copy', 'option') ?: '';
$image             = get_field('about_light_tn_image', 'option') ?: null;
$image_caption     = get_field('about_light_tn_image_caption', 'option') ?: '';
$secondary_heading = get_field('about_light_tn_secondary_heading', 'option') ?: '';
$columns           = get_field('about_light_tn_columns', 'option') ?: [];

$columns = is_array($columns)
    ? array_values(array_filter($columns, static function ($column) {
        $copy = isset($column['copy']) ? trim(wp_strip_all_tags((string) $column['copy'])) : '';
        return $copy !== '';
    }))
    : [];

$image_id  = is_array($image) && !empty($image['ID']) ? (int) $image['ID'] : 0;
$image_url = is_array($image) && !empty($image['url']) ? (string) $image['url'] : '';
$image_alt = is_array($image) && !empty($image['alt']) ? (string) $image['alt'] : '';

if (
    $heading === '' ||
    trim(wp_strip_all_tags($intro_copy)) === '' ||
    (!$image_id && $image_url === '') ||
    $secondary_heading === '' ||
    empty($columns)
) {
    return;
}

$align      = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name = !empty($args['class_name']) ? (string) $args['class_name'] : '';
$variant    = !empty($args['variant']) ? (string) $args['variant'] : 'default';
$variant    = $variant === 'template' ? 'template' : 'default';
$classes    = trim('about-light-tn about-light-tn--' . $variant . ' align' . $align . ' ' . $class_name);

if ($variant === 'template') {
    $template_cards = [];

    if (trim(wp_strip_all_tags($intro_copy)) !== '') {
        $template_cards[] = $intro_copy;
    }

    foreach ($columns as $column) {
        if (count($template_cards) >= 2) {
            break;
        }

        $copy = isset($column['copy']) ? trim((string) $column['copy']) : '';

        if (trim(wp_strip_all_tags($copy)) !== '') {
            $template_cards[] = $copy;
        }
    }
    ?>

    <section class="<?php echo esc_attr($classes); ?> scroll-reveal">
        <div class="container about-light-tn__inner">
            <h2 class="about-light-tn__heading scroll-reveal" style="--reveal-delay: 60ms;"><?php echo esc_html($heading); ?></h2>

            <div class="about-light-tn__template-grid">
                <figure class="about-light-tn__figure scroll-reveal" style="--reveal-delay: 100ms;">
                    <?php if ($image_id) : ?>
                        <?php echo wp_get_attachment_image($image_id, 'large', false, [
                            'class' => 'about-light-tn__image',
                            'alt'   => $image_alt,
                        ]); ?>
                    <?php else : ?>
                        <img class="about-light-tn__image"
                             src="<?php echo esc_url($image_url); ?>"
                             alt="<?php echo esc_attr($image_alt); ?>"
                             loading="lazy" />
                    <?php endif; ?>
                </figure>

                <?php foreach ($template_cards as $index => $copy) :
                    $delay = 140 + ((int) $index * 70);
                    ?>
                    <article class="about-light-tn__template-card scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                        <?php echo wp_kses_post(wpautop($copy)); ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return;
}
?>

<section class="<?php echo esc_attr($classes); ?> scroll-reveal">
    <div class="container about-light-tn__inner">
        <div class="about-light-tn__top">
            <div class="about-light-tn__intro scroll-reveal" style="--reveal-delay: 60ms;">
                <h2 class="about-light-tn__heading"><?php echo esc_html($heading); ?></h2>

                <div class="about-light-tn__intro-copy">
                    <?php echo wp_kses_post(wpautop($intro_copy)); ?>
                </div>
            </div>

            <figure class="about-light-tn__figure scroll-reveal" style="--reveal-delay: 110ms;">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'large', false, [
                        'class' => 'about-light-tn__image',
                        'alt'   => $image_alt,
                    ]); ?>
                <?php else : ?>
                    <img class="about-light-tn__image"
                         src="<?php echo esc_url($image_url); ?>"
                         alt="<?php echo esc_attr($image_alt); ?>"
                         loading="lazy" />
                <?php endif; ?>

                <?php if (trim((string) $image_caption) !== '') : ?>
                    <figcaption class="about-light-tn__caption"><?php echo esc_html($image_caption); ?></figcaption>
                <?php endif; ?>
            </figure>
        </div>

        <div class="about-light-tn__bottom">
            <header class="about-light-tn__secondary-header scroll-reveal" style="--reveal-delay: 80ms;">
                <h3 class="about-light-tn__secondary-heading"><?php echo esc_html($secondary_heading); ?></h3>
            </header>

            <div class="about-light-tn__columns">
                <?php foreach ($columns as $index => $column) :
                    $copy  = isset($column['copy']) ? trim((string) $column['copy']) : '';
                    $delay = 120 + ((int) $index * 70);
                    ?>
                    <article class="about-light-tn__column scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                        <div class="about-light-tn__column-copy">
                            <?php echo wp_kses_post(wpautop($copy)); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
