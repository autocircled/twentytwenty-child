<?php
function twenty_twenty_child_enqueue_child_styles() {
    $parent_style = 'parent-style'; 
	wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css' );
    //wp_register_script('google_adsense', 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js', array(), null, false);
    //wp_enqueue_script('google_adsense');
	}
add_action( 'wp_enqueue_scripts', 'twenty_twenty_child_enqueue_child_styles' );

/*Write here your own functions */
/**
 * Add a sidebar.
 */
function twenty20_child_theme_setup() {
    $shared_args = array(
		'before_title'  => '<h2 class="widget-title subheading heading-size-3">',
		'after_title'   => '</h2>',
		'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
		'after_widget'  => '</div></div>',
	);

	// Main Sidebar #1.
	register_sidebar(
		array_merge(
			$shared_args,
			array(
				'name'        => __( 'Main Sidebar', 'twentytwenty' ),
				'id'          => 'main-sidebar-1',
				'description' => __( 'Widgets in this area will be displayed in the main sidebar.', 'twentytwenty' ),
			)
		)
	);
}
add_action( 'widgets_init', 'twenty20_child_theme_setup' );

function rtp_rssv_scripts() {
    global $wp_scripts;
    if (!is_a($wp_scripts, 'WP_Scripts'))
        return;
    foreach ($wp_scripts->registered as $handle => $script)
        $wp_scripts->registered[$handle]->ver = null;
}

function rtp_rssv_styles() {
    global $wp_styles;
    if (!is_a($wp_styles, 'WP_Styles'))
        return;
    foreach ($wp_styles->registered as $handle => $style)
        $wp_styles->registered[$handle]->ver = null;
}

add_action('wp_print_scripts', 'rtp_rssv_scripts', 999);
add_action('wp_print_footer_scripts', 'rtp_rssv_scripts', 999);

add_action('admin_print_styles', 'rtp_rssv_styles', 999);
add_action('wp_print_styles', 'rtp_rssv_styles', 999);

function get_excerpt(){
$excerpt = get_the_content();
$excerpt = preg_replace(" ([.*?])",'',$excerpt);
$excerpt = strip_shortcodes($excerpt);
$excerpt = strip_tags($excerpt);
$excerpt = substr($excerpt, 0, 150);
$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
$excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
$excerpt = $excerpt.'...';
return $excerpt;
}

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// This is a test comment
// add filters to search query
add_action( 'pre_get_posts', 'advanced_search_query' );
function advanced_search_query( $query ) {

    if ( isset( $_REQUEST['search'] ) && $_REQUEST['search'] == 'advanced' && ! is_admin() && $query->is_search && $query->is_main_query() ) {

        $query->set( 'post_type', 'jobs' );

        $_location = $_GET['location'] != '' ? $_GET['location'] : '';

        $meta_query = array(
                            array(
                                'key'     => 'location', // assumed your meta_key is 'car_model'
                                'value'   => $_location,
                                'compare' => 'LIKE', // finds models that matches 'model' from the select field
                            )
                        );
        $query->set( 'meta_query', $meta_query );

    }
}

add_filter('get_the_archive_title', 'taxonomy_title_filter');
function taxonomy_title_filter(){
    if ( is_tax() ) {
		$queried_object = get_queried_object();
		if ( $queried_object ) {
			$tax = get_taxonomy( $queried_object->taxonomy );
//                        var_dump($tax);
                        if($tax->name == 'location'){
                            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term. */
                            $title = sprintf( __( '%1$s %2$s' ), '<span class="by-who">Jobs from</span>', single_term_title( '', false ) );
                        }elseif($tax->name == 'company'){
                            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term. */
                            $title = sprintf( __( '%1$s %2$s' ), '<span class="by-who">Jobs offered by</span>', single_term_title( '', false ) );
                        }elseif($tax->name == 'job_industry' OR $tax->name == 'job_type'){
                            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term. */
                            $title = sprintf( __( '%1$s %2$s' ), '<span class="by-who">Jobs available for</span>', single_term_title( '', false ) );
                        }
		}
	}
        return $title;
}

if( !function_exists('is_amp_endpoint')){
    function is_amp_endpoint(){
        return false;
    }
}

if (!function_exists('google_analytics')){
    function google_analytics(){
        $output = <<<HTML
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-104920476-5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-104920476-5');
</script>
HTML;
        if(function_exists('is_amp_endpoint')){
            if(!is_amp_endpoint()){
                echo $output;
            }
        }
    }
}

add_action('wp_body_open', 'google_analytics');



//Insert ads after second paragraph of single post content.
 
//add_filter( 'the_content', 'prefix_insert_post_ads' );
 
function prefix_insert_post_ads( $content ) {
     
    $ad_code = '<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="fluid"
     data-ad-layout-key="-gi-p+5p-6b-53"
     data-ad-client="ca-pub-1647729644239136"
     data-ad-slot="4718884498"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>';
 
    if ( is_single() && ! is_admin() ) {
        return prefix_insert_after_paragraph( $ad_code, 5, 3, $content );
    }
     
    return $content;
}
  
// Parent Function that makes the magic happen
  
function prefix_insert_after_paragraph( $insertion, $paragraph_id, $repeat, $content ) {
    $repeat = $repeat+1;
    $closing_p = '</p>';
    $paragraphs = explode( $closing_p, $content ); // 46
    $ad_count = count($paragraphs) / $repeat; //15.333333333333
    $ad_gap = ceil($ad_count);//16
    $g = 0;
    foreach ($paragraphs as $index => $paragraph) {
 
        if ( trim( $paragraph ) ) {
            $paragraphs[$index] .= $closing_p;
        }
    }
    $repeat_count = 0;
    for ($repeat_count = 0; $repeat_count < $repeat; $repeat_count++){
        if ( $repeat_count == 0 && $paragraph_id == $index + 1 ) {
            $paragraphs[$index] .= $insertion;
            $g = $paragraph_id+$ad_gap;
        }
        if ( $repeat_count > 0 ) {
            
            $paragraphs[$g] .= $insertion;
            $g+=$ad_gap;
        }
    }
    
//    var_dump($paragraphs);
    return implode( '', $paragraphs );
}

add_filter( 'c2c_blog_time_format', 'my_blog_time_format' );

/**
 * Returns a custom datetime format string for default use
 * by the Blog Time plugin.
 *
 * See https://php.net/date for more information regarding the time format.
 *
 * @param string $format Original format string (ignored)
 * @return string New format string
 */
function my_blog_time_format( $format ) {
    return 'M d, Y h:i:s A';
}

require_once __DIR__ . '/classes/class-widget-related-jobs.php';

function register_dinjob_widgets() {
    register_widget( 'DinJob_Related_Jobs' );
}
add_action( 'widgets_init', 'register_dinjob_widgets' );