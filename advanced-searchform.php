<?php /**`advanced-searchform.php`*/ ?>
<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
	<?php
		if( $terms = get_terms( array( 'taxonomy' => 'location', 'orderby' => 'parent' ) ) ) : 
 
			echo '<select name="locationfilter"><option value="">Select location...</option>';

			foreach ( $terms as $term ) :
                    $list = get_term_parents_list($term->term_id, 'location', array('link' => false, 'separator' => ', '));
                        $str = substr($list, 0, -2);
				echo '<option value="' . $term->term_id . '">' . $str . '</option>'; // ID of the location as the value of an option
//				echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the location as the value of an option
                endforeach;
			echo '</select>';
		endif;
	?>

	<button>Apply filter</button>
	<input type="hidden" name="action" value="myfilter">
</form>
<div id="response"></div>