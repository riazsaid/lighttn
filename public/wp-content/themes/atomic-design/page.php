<?php
get_header();
?>

<main id="site-content">
    <?php
    while (have_posts()):
        the_post();

        $post_id = get_the_ID();
        $use_content_container = false;
        if (metadata_exists('post', $post_id, 'page_use_content_container')) {
            $use_content_container = (bool) get_post_meta($post_id, 'page_use_content_container', true);
        } else {
            $container_default_start = strtotime('2026-06-12 00:00:00');
            $post_created_at = strtotime(get_post_field('post_date_gmt', $post_id) ?: get_post_field('post_date', $post_id));
            $use_content_container = $post_created_at >= $container_default_start;
        }

        $content_classes = $use_content_container
            ? 'container page-content-container'
            : 'page-content-container page-content-container--full';
        ?>
        <div class="<?php echo esc_attr($content_classes); ?>">
            <?php the_content(); ?>
        </div>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
?>
