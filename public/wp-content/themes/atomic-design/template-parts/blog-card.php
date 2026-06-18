<?php
/**
 * Blog card template part.
 *
 * @package AtomicDesign
 */

$post_categories = get_the_category();
$primary_category = ! empty($post_categories) ? $post_categories[0]->name : __('Blog', 'atomic-design');
?>

<article <?php post_class('blog-card'); ?>>
    <a class="blog-card__media" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large', [
                'class' => 'blog-card__image',
                'loading' => 'lazy',
                'decoding' => 'async',
            ]); ?>
        <?php else : ?>
            <span class="blog-card__placeholder" aria-hidden="true"></span>
        <?php endif; ?>
    </a>

    <div class="blog-card__body">
        <?php /*
        <p class="blog-card__meta">
            <span><?php echo esc_html($primary_category); ?></span>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
        </p>
        */ ?>
        <h2 class="blog-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <?php
        $card_excerpt = trim(get_the_excerpt());
        if ($card_excerpt !== '') :
            ?>
            <p class="blog-card__excerpt"><?php echo esc_html($card_excerpt); ?></p>
        <?php endif; ?>
        <a class="blog-card__link" href="<?php the_permalink(); ?>">
            <?php esc_html_e('Read More', 'atomic-design'); ?>
            <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
