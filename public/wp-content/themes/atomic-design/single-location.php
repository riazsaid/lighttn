<?php
/**
 * Single Location template
 *
 * Structure:
 * Hero → Title Description 1 → Trust Bar → Title Description 2
 * → Service Links 1 → Area Coverage 1 → Why Choose 1 → Title Description 3 → Testimonials → FAQs.
 */
get_header();
?>

<main id="site-content">
    <?php
    if (function_exists('get_field')) {
        get_template_part('template-parts/shared/hero', null, ['post_id' => get_queried_object_id()]);
    }

    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>

    <?php
    if (function_exists('get_field')) {
        $post_id = get_queried_object_id();

        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/trust-bar');
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
        ]);
        get_template_part('template-parts/shared/service-links-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/area-coverage-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/why-choose-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 3,
        ]);
    }

    get_template_part('template-parts/shared/testimonials');
    if (function_exists('get_field')) {
        get_template_part('template-parts/shared/faqs', null, ['post_id' => $post_id]);
    }
    ?>
</main>

<?php
get_footer();
