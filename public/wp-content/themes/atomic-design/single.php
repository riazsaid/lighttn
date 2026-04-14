<?php
get_header();
?>

<main id="site-content">
    <section class="section">
        <div class="container">
            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </section>

</main>

<?php
get_footer();
?>
