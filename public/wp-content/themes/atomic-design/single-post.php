<?php
/**
 * Single blog post template.
 *
 * @package AtomicDesign
 */

get_header();
?>

<main id="site-content" class="blog-single">
    <?php
    while (have_posts()) :
        the_post();

        $post_categories = get_the_category();
        $primary_category = ! empty($post_categories) ? $post_categories[0]->name : __('Blog', 'atomic-design');
        $blog_page_id = (int) get_option('page_for_posts');
        $blog_url = $blog_page_id > 0 ? get_permalink($blog_page_id) : get_post_type_archive_link('post');
        ?>
        <article <?php post_class('blog-article'); ?>>
            <header class="blog-article__header">
                <div class="container blog-article__header-inner">
                    <a class="blog-article__back" href="<?php echo esc_url($blog_url ?: home_url('/')); ?>">
                        <span aria-hidden="true">←</span>
                        <?php esc_html_e('Back to Blogs', 'atomic-design'); ?>
                    </a>
                    <p class="blog-article__meta">
                        <span><?php echo esc_html($primary_category); ?></span>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </p>
                    <h1 class="blog-article__title"><?php the_title(); ?></h1>
                    <?php if (has_excerpt()) : ?>
                        <p class="blog-article__dek"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>
                </div>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <figure class="container blog-article__hero">
                    <?php the_post_thumbnail('full', [
                        'class' => 'blog-article__image',
                        'loading' => 'eager',
                        'decoding' => 'async',
                    ]); ?>
                    <?php if (get_the_post_thumbnail_caption()) : ?>
                        <figcaption><?php echo esc_html(get_the_post_thumbnail_caption()); ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endif; ?>

            <div class="container">
                <div class="blog-article__content">
                    <?php the_content(); ?>
                </div>

                <footer class="blog-article__footer">
                    <?php the_tags('<div class="blog-article__tags">', '', '</div>'); ?>
                    <a class="blog-article__back blog-article__back--footer" href="<?php echo esc_url($blog_url ?: home_url('/')); ?>">
                        <span aria-hidden="true">←</span>
                        <?php esc_html_e('Back to Blogs', 'atomic-design'); ?>
                    </a>
                </footer>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
