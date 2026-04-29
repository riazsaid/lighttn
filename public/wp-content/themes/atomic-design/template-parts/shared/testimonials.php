<?php
/**
 * Testimonials Section (Shared Partial)
 *
 * Pulls all data from Synced Components -> Testimonials.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_field')) {
    return;
}

$testimonials = get_field('testimonials_list', 'option');

if (empty($testimonials) || !is_array($testimonials)) {
    return;
}

$heading = get_field('testimonials_heading', 'option') ?: 'Trusted by Homeowners Across Middle Tennessee';
$intro   = get_field('testimonials_intro', 'option') ?: 'Our clients’ feedback reflects our focus on thoughtful design, professional installation, and outdoor systems that perform exactly as intended.';

$bg_type = get_field('testimonials_bg_type', 'option') ?: 'none';
$color_1 = get_field('testimonials_color_1', 'option');
$color_2 = get_field('testimonials_color_2', 'option');

$bg_style = '';
if ($bg_type === 'solid' && $color_1) {
    $bg_style = 'style="background:' . esc_attr($color_1) . ';"';
} elseif ($bg_type === 'gradient' && $color_1 && $color_2) {
    $bg_style = 'style="background:linear-gradient(135deg,' . esc_attr($color_1) . ',' . esc_attr($color_2) . ');"';
}
?>

<section class="testimonials-block scroll-reveal" <?php echo $bg_style; ?>>
    <div class="container testimonials-block__inner">
        <div class="testimonials-block__intro scroll-reveal" style="--reveal-delay: 70ms;">
            <?php if ($heading) : ?>
                <h2 class="testimonials-heading"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($intro) : ?>
                <div class="testimonials-intro">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="testimonials-grid" role="list">
            <?php foreach ($testimonials as $index => $item) :
                $avatar = $item['reviewer_avatar'] ?? null;
                $name   = $item['reviewer_name'] ?? '';
                $rating = (int) ($item['star_rating'] ?? 5);
                $text   = $item['testimonial_text'] ?? '';
                $delay  = 120 + ((int) $index * 90);

                if (empty($text) && empty($name)) {
                    continue;
                }

                $rating = max(1, min(5, $rating));
                ?>
                <article class="testimonial-card scroll-reveal" role="listitem" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                    <div class="star-rating" aria-label="<?php echo esc_attr($rating); ?> out of 5 stars">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <span class="star <?php echo $i <= $rating ? 'star--filled' : 'star--empty'; ?>" aria-hidden="true">★</span>
                        <?php endfor; ?>
                    </div>

                    <?php if ($text) : ?>
                        <div class="testimonial-text">
                            <?php echo wp_kses_post(wpautop($text)); ?>
                        </div>
                    <?php endif; ?>

                    <div class="testimonial-author">
                        <?php if (!empty($avatar['url'])) : ?>
                            <img src="<?php echo esc_url($avatar['url']); ?>"
                                 alt="<?php echo esc_attr($name); ?>"
                                 width="46"
                                 height="46"
                                 class="reviewer-avatar"
                                 loading="lazy">
                        <?php else : ?>
                            <span class="reviewer-avatar reviewer-avatar--placeholder" aria-hidden="true"></span>
                        <?php endif; ?>

                        <?php if ($name) : ?>
                            <strong class="reviewer-name"><?php echo esc_html($name); ?></strong>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
