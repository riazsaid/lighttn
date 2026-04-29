<?php
/**
 * Single Service Location template
 *
 * e.g. /locations/{city-slug}/{service-slug}/
 * Structure:
 * Hero → Title Description 1 → Trust Bar → Title Description 2
 * → Insight Columns → Proof Points → Design Process → Steps Grid → Title Description 3 → Consultation Split → Testimonials → FAQs
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
        get_template_part('template-parts/shared/insight-columns-sections', null, [
            'post_id' => $post_id,
        ]);
        get_template_part('template-parts/shared/proof-points-sections', null, [
            'post_id' => $post_id,
        ]);
        get_template_part('template-parts/shared/design-process-sections', null, [
            'post_id' => $post_id,
        ]);
        get_template_part('template-parts/shared/steps-grid-sections', null, [
            'post_id' => $post_id,
        ]);
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 3,
        ]);
    }
    if (function_exists('get_field')) {
        get_template_part('template-parts/shared/consultation-split-sections', null, [
            'post_id' => $post_id,
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
