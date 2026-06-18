<?php
/**
 * Tag archive template.
 *
 * @package AtomicDesign
 */

get_header();
?>

<main id="site-content" class="blog-index">
    <section class="blog-index__hero">
        <div class="container blog-index__hero-inner">
            <h1 class="blog-index__title"><?php single_tag_title(); ?></h1>
        </div>
    </section>

    <section class="blog-index__posts" aria-label="<?php esc_attr_e('Tagged blog posts', 'atomic-design'); ?>">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="blog-card-grid">
                    <?php
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/blog-card');
                    endwhile;
                    ?>
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
                    <h2><?php esc_html_e('No posts found for this tag', 'atomic-design'); ?></h2>
                    <p><?php esc_html_e('Try another tag or return to the blog.', 'atomic-design'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
