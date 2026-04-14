<?php
/**
 * Industry Solutions Section (Shared Partial)
 *
 * Pulls from the central ACF Options Page:
 * Synced Components → Industry Solutions
 *
 * Same grid on all service template pages: icon, title, description, learn-more link.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_field')) {
    return;
}

$heading = get_field('industry_solutions_heading', 'option') ?: 'Industry Solutions';
$items   = get_field('industry_solutions_list', 'option');

if (empty($items) || !is_array($items)) {
    return;
}
?>

<section class="industry-solutions-block">
    <div class="container">

        <?php if (!empty($heading)) : ?>
            <h2 class="industry-solutions-heading"><?php echo esc_html($heading); ?></h2>
        <?php endif; ?>

        <div class="industry-solutions-grid">
            <?php foreach ($items as $item) :
                $icon  = $item['item_icon'] ?? null;
                $icon_url = is_array($icon) && !empty($icon['url']) ? (string) $icon['url'] : '';
                $title = $item['item_title'] ?? '';
                $desc  = $item['item_description'] ?? '';
                $link  = $item['item_link'] ?? null;
            ?>
                <article class="industry-solution-card">
                    <div class="industry-solution-card__icon" aria-hidden="true">
                        <?php if (!empty($icon_url)) : ?>
                            <img src="<?php echo esc_url($icon_url); ?>"
                                 alt=""
                                 width="48" height="48"
                                 loading="lazy">
                        <?php endif; ?>
                    </div>

                    <div class="industry-solution-card__content">
                        <?php if ($title) : ?>
                            <h3 class="industry-solution-card__title"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>

                        <?php if ($desc) : ?>
                            <div class="industry-solution-card__description">
                                <?php echo wp_kses_post(wpautop($desc)); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_array($link) && !empty($link['url'])) : ?>
                            <p class="industry-solution-card__link-wrap">
                                <a href="<?php echo esc_url($link['url']); ?>"
                                   class="industry-solution-card__link"
                                   <?php echo !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : ''; ?>
                                   <?php echo !empty($link['target']) && $link['target'] === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
                                    <?php echo esc_html(!empty($link['title']) ? $link['title'] : $title); ?>
                                    <span class="industry-solution-card__arrow" aria-hidden="true">→</span>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
