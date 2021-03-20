<?php
/**
 * The default template for displaying content
 *
 * Used for both singular and index.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */
// Company name with link
$company_array = get_the_terms($post->ID, 'company');
if ($company_array) {
    $i = 0;
    foreach ($company_array as $company) {
        $company_url = get_term_link($company, 'company');
        $i++;
    }
}
// Job locations

$job_locations = get_the_terms($post->ID, 'location');

if ($job_locations) {
    $location_full = '';
    $j = count($job_locations) - 1;
    foreach ($job_locations as $location) {
        $location_link = get_term_link($location);
        $location_full .= '<a href="'. $location_link .'">' . $location->name . '</a>' . ($j > 0 ? ', ' : '');
        $j--;
    }
}

// Job Type
$job_types = get_the_terms($post->ID, 'job_type');
if ($job_types) {
    $all_job_types = '';
    $k = count($job_types) - 1;
    foreach ($job_types as $type) {
        $all_job_types .= '<a href="'. get_term_link($type, 'job_type') .'">' . $type->name . '</a>' . ($k > 0 ? ', ' : '');
        $k--;
    }
}

// Job Industry
$job_industries = get_the_terms($post->ID, 'job_industry');
if ($job_industries) {
    $all_job_industries = '';
    $l = count($job_industries) - 1;
    foreach ($job_industries as $industry) {
        $all_job_industries .= '<a href="'. get_term_link($industry, 'job_industry') .'">' . $industry->name . '</a>' . ($l > 0 ? ', ' : '');
        $l--;
    }
}
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<?php
$entry_header_classes = '';

if (is_singular()) {
    $entry_header_classes .= ' header-footer-group';
}
$company_image = get_the_post_thumbnail_url(null, 'post-thumbnails');
?>

    <header class="entry-header has-text-align-center<?php echo esc_attr($entry_header_classes); ?>">

        <div class="entry-header-inner section-inner medium">
            <div class="job-header">
<?php
if (is_search() || !is_singular() || is_front_page()) {
    the_title('<h2 class="entry-title heading-size-5"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');

    if ($company_array) {
        echo '<a href="' . esc_url($company_url) . '">' . $company_array[0]->name . '</a>';
    }

    if ($job_locations) {
        echo '<p>' . $location_full . '</p>';
    }
} else {
    the_title('<h1 class="entry-title heading-size-5">', '</h1>');

    if ($company_array) {
        echo '<a href="' . esc_url($company_url) . '">' . $company_array[0]->name . '</a>';
    }

    if ($job_locations) {
        echo '<p>' . $location_full . '</p>';
    }
}
?>
            </div><!-- .job-header -->
            <div class="clear"></div>
        </div><!-- .entry-header-inner -->

    </header><!-- .entry-header -->
    <?php //get_template_part('template-ads/ads', 'archive'); ?>

    <div class="post-inner <?php echo is_page_template('templates/template-full-width.php') ? '' : 'thin'; ?> ">

        <div class="entry-content">

<?php
if (is_search() || !is_singular() || is_front_page()) {
    echo '<p>' . get_excerpt() . '</p>';
} else {
?>
            <div class="job-info">
                <div class="section-inner">
                    <div class="col left">
                        <div class="job-field">
                            <h2 class="heading-size-6">Seniority level</h2>
                            <p>
                                <?php
                                    $seniority_level = get_field_object('seniority_level');
                                    echo $seniority_level['value'];
                                ?>
                            </p>
                        </div>
                        <div class="job-field">
                            <h2 class="heading-size-6">Employment type</h2>
                            <p>
                                <?php 
                                    $employment_type = get_field_object('employment_type');
                                    echo $employment_type['value']; 
                                ?>
                            </p>
                        </div>
                    </div>
                    <div class="col right">
                        <div class="job-field">
                            <h2 class="heading-size-6">Job Functionality</h2>
                            <p><?php echo $all_job_types; ?></p>
                        </div>
                        <div class="job-field">
                            <h2 class="heading-size-6">Industry</h2>
                            <p><?php echo $all_job_industries; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    the_content();
    ?>
                
                <div class="apply-now">
    <?php
    $platform = get_field('platform');
    if ($platform == 'other') {
        $button_text = 'Apply on company website';
    }else {
        $button_text = 'Apply with LinkedIn';
    }
    ?>
                    <a href="<?php the_field('apply_now'); ?>" target="_blank" class="wp-block-button__link" rel="ugc nofollow"><?php echo $button_text; ?></a>
                </div>
                <?php
            }
            ?>

        </div><!-- .entry-content -->

    </div><!-- .post-inner -->
    <div class="section-inner">
        <?php
        wp_link_pages(
                array(
                    'before' => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__('Page', 'twentytwenty') . '"><span class="label">' . __('Pages:', 'twentytwenty') . '</span>',
                    'after' => '</nav>',
                    'link_before' => '<span class="page-number">',
                    'link_after' => '</span>',
                )
        );

        edit_post_link();

        // Single bottom post meta.
        twentytwenty_the_post_meta(get_the_ID(), 'single-bottom');

        if (is_single()) {

            get_template_part('template-parts/entry-author-bio');
        }
        ?>

    </div><!-- .section-inner -->

    <?php
    if (is_single()) {

        get_template_part('template-parts/navigation');
    }

    /**
     *  Output comments wrapper if it's a post, or if comments are open,
     * or if there's a comment number – and check for password.
     * */
    if (( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && !post_password_required()) {
        ?>

        <div class="comments-wrapper section-inner">

    <?php comments_template(); ?>

        </div><!-- .comments-wrapper -->

    <?php
}
?>

</article><!-- .post -->

