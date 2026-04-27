<?php
/**
 * Why Choose Light TN shared partial.
 *
 * Pulls from Synced Components -> Why Choose Light TN.
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_field')) {
    return;
}

$heading = get_field('why_choose_light_tn_heading', 'option') ?: __('Why Choose Light TN', 'atomic-design');
$intro   = get_field('why_choose_light_tn_description', 'option') ?: '';
$items   = get_field('why_choose_light_tn_items', 'option') ?: [];

$items = is_array($items)
    ? array_values(array_filter($items, static function ($item) {
        $title       = isset($item['title']) ? trim((string) $item['title']) : '';
        $description = isset($item['description']) ? trim((string) $item['description']) : '';
        $icon        = $item['icon'] ?? null;
        $icon_id     = is_array($icon) && !empty($icon['ID']) ? (int) $icon['ID'] : 0;
        $icon_url    = is_array($icon) && !empty($icon['url']) ? $icon['url'] : '';

        return $title !== '' || $description !== '' || $icon_id || $icon_url;
    }))
    : [];

if ($heading === '' || empty($items)) {
    return;
}

$align      = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name = !empty($args['class_name']) ? (string) $args['class_name'] : '';
$classes    = trim('why-choose-light-tn align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($classes); ?>">
    <div class="container why-choose-light-tn__inner">
        <div class="why-choose-light-tn__header">
            <h2 class="why-choose-light-tn__heading"><?php echo esc_html($heading); ?></h2>

            <?php if (trim(wp_strip_all_tags($intro)) !== '') : ?>
                <div class="why-choose-light-tn__intro">
                    <?php echo wp_kses_post($intro); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="why-choose-light-tn__grid">
            <?php foreach ($items as $item) :
                $title       = isset($item['title']) ? trim((string) $item['title']) : '';
                $description = isset($item['description']) ? trim((string) $item['description']) : '';
                $icon        = $item['icon'] ?? null;
                $icon_id     = is_array($icon) && !empty($icon['ID']) ? (int) $icon['ID'] : 0;
                $icon_url    = is_array($icon) && !empty($icon['url']) ? $icon['url'] : '';
                ?>
                <article class="why-choose-light-tn__card">
                    <?php if ($icon_id || $icon_url) : ?>
                        <div class="why-choose-light-tn__icon" aria-hidden="true">
                            <?php if ($icon_id) : ?>
                                <?php echo wp_get_attachment_image($icon_id, 'thumbnail', false, [
                                    'class' => 'why-choose-light-tn__icon-image',
                                    'alt'   => '',
                                ]); ?>
                            <?php else : ?>
                                <img class="why-choose-light-tn__icon-image"
                                     src="<?php echo esc_url($icon_url); ?>"
                                     alt=""
                                     loading="lazy" />
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($title !== '') : ?>
                        <h3 class="why-choose-light-tn__card-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($description !== '') : ?>
                        <p class="why-choose-light-tn__card-description"><?php echo esc_html($description); ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
