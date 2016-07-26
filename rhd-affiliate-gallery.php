<?php
/**
 * Plugin Name: RHD Affiliate Gallery
 * Version: 0.1-alpha
 * Description: Displays affiliate links in a pretty custom gallery.
 * Author: Roundhouse Designs
 * Author URI: https://roundhouse-designs.com
 * Text Domain: rhd
 * @package RHD Affiliate Gallery
 */


/* ==========================================================================
	Setup
   ========================================================================== */

define( 'RHD_AG_PLUGIN_DIR', plugin_dir_url(__FILE__) );

require( 'includes/rhd-ag-functions.php' );
// require( 'includes/rhd-ag-shortcode.php' );



class RHD_Affiliate_Gallery extends WP_Widget {
	function __construct() {
		parent::__construct(
	 		'rhd_affiliate_gallery', // Base ID
			__( 'RHD Affiliate Gallery', 'rhd' ), // Name
			array( 'description' => __( 'Displays affiliate links in a pretty custom gallery.', 'rhd' ), ) // Args
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'display_styles' ) );
	}

	public function display_styles() {
		wp_enqueue_style( 'rhd-si-main', RHD_AG_PLUGIN_DIR . 'css/rhd-si-main.css' );
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['cols'] = ( $new_instance['cols'] ) ? absint( $new_instance['cols'] ) : '';
		$instance['thumbsize'] = ( $new_instance['thumbsize'] ) ? $new_instance['thumbsize'] : '';

		return $instance;
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget

		extract( $args );
		extract( $instance );

		$title = ( ! empty( $title ) ) ? apply_filters( 'widget_title', $title ) : '';

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;


		// WIDGET OUTPUT HERE


		echo $after_widget;
	}

	public function form( $instance ) {
		// outputs the options form on admin
		$args = array();

		$title = ! empty( $instance['title'] )? esc_attr( $instance['title'] ) : '';
		$cols = ! empty( $instance['cols'] )? esc_attr( $instance['cols'] ) : 2;
		$thumbsize = ! empty( $instance['thumbsize'] )? $instance['thumbsize'] : '';

		$sizes = rhd_get_image_sizes();
?>

		<h3><?php _e( 'Widget Options:' ); ?></h3>
		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>">Widget Title (optional): </label><br />
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" >
		</p>

		<div class="rhd-ag-options">
			<p>
				<label for="<?php echo $this->get_field_name( 'cols' ); ?>">Columns: </label><br />
				<input id="<?php echo $this->get_field_id( 'cols' ); ?>" name="<?php echo $this->get_field_name( 'cols' ); ?>" type="number" placeholder="2" value="<?php echo $cols; ?>" >
			</p>
			<p>
				<label for="<?php echo $this->get_field_name( 'thumbsize' ); ?>">Image Size: </label><br />
				<select name="<?php echo $this->get_field_name( 'thumbsize' ); ?>">
					<?php foreach ( $sizes as $size => $atts ) : ?>
						<?php
						$label = ' (' . $atts['width'] . 'x' . $atts['height'];
						if ( $atts['crop'] === true ) $label .= ' cropped';
						$label .= ')';
						?>
						<option value="<?php echo $size; ?>" <?php selected( $thumbsize, $size ); ?>><?php _e( $size . $label, 'rhd' )?></option>
					<?php endforeach; ?>
				</select>
			</p>
		</div><!-- .rhd-social-icons-entries -->

<?php
	}
}


/**
 * Register RHD_Affiliate_Gallery
 *
 * @access public
 * @return void
 */
function register_rhd_affiliate_gallery_widget()
{
    register_widget( 'RHD_Affiliate_Gallery' );
}
add_action( 'widgets_init', 'register_rhd_affiliate_gallery_widget' );