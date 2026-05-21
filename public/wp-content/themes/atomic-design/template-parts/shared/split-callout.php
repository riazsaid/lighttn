<?php
/**
 * Shared Split Callout section.
 *
 * Args:
 * - section_heading (string) Required.
 * - intro (string) Optional HTML.
 * - left_secondary_title (string) Optional title shown below intro on left.
 * - left_secondary_copy (string) Optional HTML shown below secondary title.
 * - investment_ranges_content (string) Optional HTML in top right card.
 * - cards (array) Optional repeater rows: title, copy.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$intro           = isset($args['intro']) ? trim((string) $args['intro']) : '';
$left_secondary_title = isset($args['left_secondary_title']) ? trim((string) $args['left_secondary_title']) : '';
$left_secondary_copy  = isset($args['left_secondary_copy']) ? trim((string) $args['left_secondary_copy']) : '';
$investment_ranges_content = isset($args['investment_ranges_content']) ? trim((string) $args['investment_ranges_content']) : '';
$cards           = isset($args['cards']) && is_array($args['cards']) ? $args['cards'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$cards = array_values(array_filter($cards, static function ($card) {
    $title = isset($card['title']) ? trim((string) $card['title']) : '';
    $copy  = isset($card['copy']) ? trim(wp_strip_all_tags((string) $card['copy'])) : '';
    return $title !== '' || $copy !== '';
}));

$has_intro = trim(wp_strip_all_tags($intro)) !== '';
$has_ranges = trim(wp_strip_all_tags($investment_ranges_content)) !== '';
$has_cards = !empty($cards);

if ($section_heading === '' || !$has_intro || (!$has_ranges && !$has_cards)) {
    return;
}

$section_class = trim('split-callout align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container split-callout__inner">
        <div class="split-callout__grid">
            <div class="split-callout__intro scroll-reveal" style="--reveal-delay: 70ms;">
                <h2 class="split-callout__heading"><?php echo esc_html($section_heading); ?></h2>

                <div class="split-callout__intro-copy">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>

                <?php if ($left_secondary_title !== '' || trim(wp_strip_all_tags($left_secondary_copy)) !== '') : ?>
                    <div class="split-callout__intro-secondary">
                        <?php if ($left_secondary_title !== '') : ?>
                            <h3 class="split-callout__intro-secondary-title"><?php echo esc_html($left_secondary_title); ?></h3>
                        <?php endif; ?>
                        <?php if (trim(wp_strip_all_tags($left_secondary_copy)) !== '') : ?>
                            <div class="split-callout__intro-secondary-copy">
                                <?php echo wp_kses_post(wpautop($left_secondary_copy)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="split-callout__aside">
                <?php if ($has_ranges) : ?>
                    <article class="split-callout__ranges-card scroll-reveal" style="--reveal-delay: 120ms;">
                        <h3 class="split-callout__ranges-title"><?php esc_html_e('Project Investment Ranges', 'atomic-design'); ?></h3>
                        <div class="split-callout__ranges-copy">
                            <?php echo wp_kses_post($investment_ranges_content); ?>
                        </div>
                    </article>
                <?php endif; ?>

                <?php if ($has_cards) : ?>
                    <div class="split-callout__cards">
                        <?php foreach ($cards as $index => $card) :
                            $card_title = isset($card['title']) ? trim((string) $card['title']) : '';
                            $card_copy  = isset($card['copy']) ? trim((string) $card['copy']) : '';
                            $delay = 170 + ((int) $index * 60);
                            ?>
                            <article class="split-callout__card scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                                <?php if ($card_title !== '') : ?>
                                    <h4 class="split-callout__card-title"><?php echo esc_html($card_title); ?></h4>
                                <?php endif; ?>
                                <?php if (trim(wp_strip_all_tags($card_copy)) !== '') : ?>
                                    <div class="split-callout__card-copy">
                                        <?php echo wp_kses_post(wpautop($card_copy)); ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
