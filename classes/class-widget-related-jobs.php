<?php
/**
 * Adds DinJob_Related_Jobs widget.
 */
class DinJob_Related_Jobs extends WP_Widget {
 
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        // actual widget processes
        $widget_ops = array( 
			'classname' => 'dinjob_related_jobs',
			'description' => 'Show related jobs by custom terms.',
		);
		parent::__construct( 'dinjob_related_jobs', 'DinJob Related Jobs', $widget_ops );
        
    }
 
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        // early exit if post_type not jobs
        if( !is_singular('jobs') ) return;

        // outputs the content of the widget
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );
 
        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        $terms = wp_get_post_terms( get_the_ID(), $instance['job_taxonomy_list'], array( 'hide_empty' => false, ) );
        foreach ($terms as $term) {
            # code...
            $term_ids[] = $term->term_id;
        }
        $new_query = get_posts(
            array(
                'post_type' => 'jobs',
                'include' => [],
                // 'numberposts' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => $instance['job_taxonomy_list'],
                        'terms' => $term_ids,
                        'field' => 'term_id',
                    )
                ),
                'orderby' => 'title',
                'order' => 'ASC'

            )
        );
        $job_meta = '';
        if( is_array( $term_ids ) ){
            foreach ($term_ids as $id){
                $job_meta .= get_term( $id )->name;
                $job_meta .= $id === end( $term_ids) ? '': ', ';
            }
        }
        echo '<ul class="job-items">';
        foreach ( $new_query as $item ) {
            echo '<li class="job-item">'
            . '<a href="'. get_the_permalink( $item->ID ) .'">'
            . '<span class="job-meta-title">'. get_the_title( $item->ID ) .'</span>'
            . '<span class="job-meta-info">'. $job_meta .'</span>'
            . '</a></li>';
        }
        echo '</ul>';
        echo $after_widget;
    }
 
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        // outputs the options form in the admin
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'More Jobs From', 'dinjob' );
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
            // get all the custom taxonomies registered for jobs
            $taxonomies = get_object_taxonomies( 'jobs' );
            echo '<select id="'. $this->get_field_id( 'job_taxonomy_list' ) .'" name="'. $this->get_field_name( 'job_taxonomy_list' ) .'">';
            echo '<option>'. esc_html__( 'Select a Taxonomy', 'dinjob' ) .'</option>';
            foreach ($taxonomies as $taxonomy) {
                // get the taxonomy object
                $tax_obj = get_taxonomy($taxonomy);
                $selected = isset( $instance[ 'job_taxonomy_list' ] ) && $instance[ 'job_taxonomy_list' ] == $taxonomy ? 'selected' : '';
                echo '<option value="'. esc_attr( $taxonomy ) .'"'. esc_attr( $selected )  .'>'. esc_html( $tax_obj->label ) .'</option>';
                
            }
            echo '</select>';
        ?>
    <?php
    }
    
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['job_taxonomy_list'] = ( !empty( $new_instance['job_taxonomy_list'] ) ) ? strip_tags( $new_instance['job_taxonomy_list'] ) : '';
        
        return $instance;
    }
}
?>