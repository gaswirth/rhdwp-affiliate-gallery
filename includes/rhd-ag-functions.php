<?php
/**
 * RHD Affiliate Gallery Functions.
 **/


/**
 * rhd_affiliate_gallery_init function.
 *
 * @access public
 * @return void
 */
function rhd_affiliate_gallery_init() {
	register_post_type( 'affiliate_gallery', array(
		'labels'            => array(
			'name'                => __( 'Affiliate Galleries', 'rhd' ),
			'singular_name'       => __( 'Affiliate Gallery', 'rhd' ),
			'all_items'           => __( 'All Affiliate Galleries', 'rhd' ),
			'new_item'            => __( 'New Affiliate Gallery', 'rhd' ),
			'add_new'             => __( 'Add New', 'rhd' ),
			'add_new_item'        => __( 'Add New Affiliate Gallery', 'rhd' ),
			'edit_item'           => __( 'Edit Affiliate Gallery', 'rhd' ),
			'view_item'           => __( 'View Affiliate Gallery', 'rhd' ),
			'search_items'        => __( 'Search Affiliate Galleries', 'rhd' ),
			'not_found'           => __( 'No Affiliate Galleries found', 'rhd' ),
			'not_found_in_trash'  => __( 'No Affiliate Galleries found in trash', 'rhd' ),
			'parent_item_colon'   => __( 'Parent Affiliate Gallery', 'rhd' ),
			'menu_name'           => __( 'Affiliate Galleries', 'rhd' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => false,
		'supports'          => array( 'title' ),
		'has_archive'       => false,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-products',
		'show_in_rest'      => true,
		'rest_base'         => 'affiliate_gallery',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'exclude_from_search'	=> true,
		'publicly_queryable'	=> false,
		'register_meta_box_cb'	=> 'rhd_ag_meta_box'
	) );
}
add_action( 'init', 'rhd_affiliate_gallery_init' );


/**
 * rhd_affiliate_gallery_updated_messages function.
 *
 * @access public
 * @param mixed $messages
 * @return void
 */
function rhd_affiliate_gallery_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['affiliate_gallery'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Affiliate Gallery updated. <a target="_blank" href="%s">View Affiliate Gallery</a>', 'rhd'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'rhd'),
		3 => __('Custom field deleted.', 'rhd'),
		4 => __('Affiliate Gallery updated.', 'rhd'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Affiliate Gallery restored to revision from %s', 'rhd'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Affiliate Gallery published. <a href="%s">View Affiliate Gallery</a>', 'rhd'), esc_url( $permalink ) ),
		7 => __('Affiliate Gallery saved.', 'rhd'),
		8 => sprintf( __('Affiliate Gallery submitted. <a target="_blank" href="%s">Preview Affiliate Gallery</a>', 'rhd'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Affiliate Gallery scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Affiliate Gallery</a>', 'rhd'),
		// translators: Publish box date format, see http://php.net/date
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Affiliate Gallery draft updated. <a target="_blank" href="%s">Preview Affiliate Gallery</a>', 'rhd'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'rhd_affiliate_gallery_updated_messages' );


/**
 * Adds a meta box to the post editing screen
 */
function rhd_ag_meta_box() {
	add_meta_box( 'rhd_ag_image_meta', __( 'Affiliate Gallery Images', 'rhd' ), 'rhd_ag_image_meta_callback', 'affiliate_gallery' );
}


/**
 * Outputs the content of the meta box
 */
function rhd_ag_image_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'rhd_ag_nonce' );
	$rhd_stored_meta = get_post_meta( $post->ID );

	$count = ( isset( $rhd_stored_meta['rhd-ag-image-count'][0] ) ) ? $rhd_stored_meta['rhd-ag-image-count'][0] : 1;
	?>

	<div id="rhd-select-images">
		<?php for ( $i = 1; $i <= $count; ++$i ) : ?>
			<p class="rhd-ag-image-select">
				<label for="rhd-ag-image-<?php echo $i; ?>" class="rhd-row-title"><?php _e( 'Image: ', 'rhd' )?></label>
				<input type="text" name="rhd-ag-image-<?php echo $i; ?>" id="rhd-ag-image-<?php echo $i; ?>" class="rhd-ag-image" value="<?php if ( isset ( $rhd_stored_meta['rhd-ag-image-' . $i] ) ) echo $rhd_stored_meta['rhd-ag-image-' . $i][0]; ?>" />
				<input type="button" id="rhd-ag-image-button-<?php echo $i; ?>" name="rhd-ag-image-button-<?php echo $i; ?>" class="button rhd-ag-image-button" value="<?php _e( 'Choose or Upload an Image', 'rhd' )?>" data-image-id="<?php echo $i; ?>" />

				<label for="rhd-ag-link-<?php echo $i; ?>" class="rhd-row-title"><?php _e( 'Affiliate Link: ', 'rhd' )?></label>
				<input type="url" name="rhd-ag-link-<?php echo $i; ?>" id="rhd-ag-link-<?php echo $i; ?>" class="rhd-ag-link" value="<?php if ( isset ( $rhd_stored_meta['rhd-ag-link-' . $i] ) ) echo $rhd_stored_meta['rhd-ag-link-' . $i][0]; ?>" />
			</p>
		<?php endfor; ?>

		<a class="add-ag-image" href="#">&plus; Add Another Row</a>

		<input type="hidden" id="rhd-ag-image-count" name="rhd-ag-image-count" value="<?php echo $count; ?>" />
	</div>

	<?php
}


/**
 * Saves the custom meta input
 */
function rhd_ag_image_meta_save( $post_id ) {

	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'rhd_ag_nonce' ] ) && wp_verify_nonce( $_POST[ 'rhd_ag_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return;
	}

	$count = ( isset( $_POST[ 'rhd-ag-image-count' ] ) ) ? $_POST[ 'rhd-ag-image-count' ] : 0;

	if ( $count > 0 ) {
		for ( $i = 1; $i <= $count; ++$i ) {
			rhd_update_post_meta( $post_id, 'rhd-ag-image-', $i );
			rhd_update_post_meta( $post_id, 'rhd-ag-link-', $i );
		}

		update_post_meta( $post_id, 'rhd-ag-image-count', absint( $count ) );
	}

}
add_action( 'save_post', 'rhd_ag_image_meta_save' );


/**
 * rhd_update_post_meta function.
 *
 * @access public
 * @param mixed $prefix
 * @param mixed $i
 * @return void
 */
function rhd_update_post_meta( $post_id, $prefix, $i ) {
	if( isset( $_POST[ $prefix . $i ] ) && $_POST[ $prefix . $i ] !== '' ) {
		update_post_meta( $post_id, $prefix . $i, esc_url_raw( $_POST[ $prefix . $i ] ) );
	}
}


/**
 * Loads the image management javascript
 */
function rhd_ag_media_enqueue() {
	global $typenow;
	if( $typenow == 'affiliate_gallery' ) {
		wp_enqueue_media();

		// Registers and enqueues the required javascript.
		wp_register_script( 'meta-box-image', RHD_AG_PLUGIN_DIR . '/includes/media.js', array( 'jquery' ) );
		wp_localize_script( 'meta-box-image', 'meta_image',
			array(
				'title' => __( 'Choose or Upload an Image', 'rhd' ),
				'button' => __( 'Use this image', 'rhd' ),
			)
		);
		wp_enqueue_script( 'meta-box-image' );
	}
}
add_action( 'admin_enqueue_scripts', 'rhd_ag_media_enqueue' );


/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function rhd_get_image_sizes() {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}

	return $sizes;
}