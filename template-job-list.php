<?php

/* 
 * Template Name: Job Listing
 */
get_header(); ?>

<main id="site-content" role="main">

    <?php

            /**
             * Job Post Query
             */
            // WP_Query arguments
            $args = array(
                'post_type' => array('jobs'),
                'post_status' => array('publish'),
                'nopaging' => false,
                'order' => 'DESC',
                'posts_per_page' => 5
            );

            // The Query
            $jobs = new WP_Query($args);
//            var_dump($jobs);
            // The Loop
            if ($jobs->have_posts()) {
                $i = 0;
                while ($jobs->have_posts()) {
                    $i++;
                    if ($i > 1) {
                        echo '<hr class="post-separator styled-separator is-style-wide section-inner" aria-hidden="true" />';
                    }
                    $jobs->the_post();
                    // do something
                    get_template_part('template-parts/content', 'jobs');
                }
            } elseif (is_search()) {
                ?>

        <div class="no-search-results-form section-inner thin">

        <?php
        get_search_form(
                array(
                    'label' => __('search again', 'twentytwenty'),
                )
        );
        ?>

        </div><!-- .no-search-results -->

            <?php
        }

        // Restore original Post Data
        wp_reset_postdata();
        /**
         * Job Post Query
         */
        get_template_part('template-parts/pagination');
        ?>

</main><!-- #site-content -->

    <?php get_template_part('template-parts/footer-menus-widgets'); 



get_footer();
