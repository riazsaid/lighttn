<?php
/**
 * Single Service Location template
 *
 * e.g. /locations/{city-slug}/{service-slug}/
 * Structure:
 * Hero → Title Description 1 → Trust Bar → Why Choose 1
 * → Title Description 2 → Industry Solutions → Why Choose 2
 * → Title Description 3 → Testimonials → FAQs
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
        get_template_part('template-parts/shared/why-choose-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
        ]);
    }

    get_template_part('template-parts/shared/industry-solutions');
    if (function_exists('get_field')) {
        get_template_part('template-parts/shared/numbered-process-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/why-choose-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
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
