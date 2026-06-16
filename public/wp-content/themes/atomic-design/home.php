<?php
/**
 * Blog index template.
 *
 * Used when a page is assigned as the Posts page in Settings > Reading.
 *
 * @package AtomicDesign
 */

get_header();

$posts_page_id = (int) get_option('page_for_posts');
$blog_title = $posts_page_id > 0 ? get_the_title($posts_page_id) : __('Blogs', 'atomic-design');
$blog_intro = $posts_page_id > 0 ? get_the_excerpt($posts_page_id) : '';

if ('' === trim($blog_intro)) {
    $blog_intro = __('Ideas, project notes, and practical guidance for outdoor lighting design, installation, and long-term performance.', 'atomic-design');
}
?>

<main id="site-content" class="blog-index">
    <section class="blog-index__hero">
        <div class="container blog-index__hero-inner">
            <h1 class="blog-index__title"><?php echo esc_html($blog_title ?: __('Blogs', 'atomic-design')); ?></h1>
            <p class="blog-index__intro"><?php echo esc_html($blog_intro); ?></p>
        </div>
    </section>

    <section class="blog-index__posts" aria-label="<?php esc_attr_e('Blog posts', 'atomic-design'); ?>">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="blog-card-grid">
                    <?php
                    while (have_posts()) :
                        the_post();

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
                                <p class="blog-card__meta">
                                    <span><?php echo esc_html($primary_category); ?></span>
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                                </p>
                                <h2 class="blog-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <?php if (has_excerpt()) : ?>
                                    <p class="blog-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                                <?php endif; ?>
                                <a class="blog-card__link" href="<?php the_permalink(); ?>">
                                    <?php esc_html_e('Read More', 'atomic-design'); ?>
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                the_posts_pagination([
                    'mid_size' => 1,
                    'prev_text' => __('Previous', 'atomic-design'),
                    'next_text' => __('Next', 'atomic-design'),
                    'class' => 'blog-pagination',
                ]);
                ?>
            <?php else : ?>
                <div class="blog-empty">
                    <h2><?php esc_html_e('No blog posts yet', 'atomic-design'); ?></h2>
                    <p><?php esc_html_e('Imported posts will appear here automatically.', 'atomic-design'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
