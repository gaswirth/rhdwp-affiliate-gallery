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
		$instance['color1'] = ( $new_instance['color1'] ) ? strip_tags( $new_instance['color1'] ) : '';
		$instance['color2'] = ( $new_instance['color2'] ) ? strip_tags( $new_instance['color2'] ) : '';
		$instance['widget_loc'] = ( $new_instance['widget_loc'] ) ? strip_tags( $new_instance['widget_loc'] ) : '';

		return $instance;
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget

		extract( $args );
		extract( $instance );

		$si_settings = get_option( 'rhd_si_plugin_settings' );
		extract( $si_settings );

		$facebook = ( ! empty( $rhd_si_facebook ) ) ? $rhd_si_facebook : '';
		$twitter = ( ! empty( $rhd_si_twitter ) ) ? $rhd_si_twitter : '';
		$pinterest = ( ! empty( $rhd_si_pinterest ) ) ? $rhd_si_pinterest : '';
		$instagram = ( ! empty( $rhd_si_instagram ) ) ? $rhd_si_instagram : '';
		$bloglovin = ( ! empty( $rhd_si_bloglovin ) ) ? $rhd_si_bloglovin : '';

		$color1_def = '#205c99';
		$color2_def = '#0ab3ae';

		$title = ( ! empty( $title ) ) ? apply_filters( 'widget_title', $title ) : '';

		$color1 = ( isset( $color1 ) ) ? $color1 : $color1_def;
		$color2 = ( isset( $color2 ) ) ? $color2 : $color2_def;

		$widget_loc = ( ! empty ( $widget_loc ) ) ? $widget_loc : 'default';

		if ( stripos( $instagram, '@' ) === 0 )
			$instagram = substr( $instagram, 1 );

		if ( stripos( $pinterest, '+' ) === 0 )
			$pinterest = substr( $pinterest, 1 );

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;
		?>

		<style scoped>
			#rhd-social-icons-<?php echo $widget_loc; ?> a path {
				fill: <?php echo $color1; ?>;
			}

			#rhd-social-icons-<?php echo $widget_loc; ?> a:hover path,
			#rhd-social-icons-<?php echo $widget_loc; ?> a:active path {
				fill: <?php echo $color2; ?>;
			}
		</style>

		<ul id="rhd-social-icons-<?php echo $widget_loc; ?>" class="rhd-social-icons">
			<?php if ( $facebook ) : ?>
				<li class="rhd-social-icon facebook-icon">
					<a href="<?php echo esc_attr( $facebook ); ?>" target="_blank">
						<svg width="100%" height="100%" viewBox="0 0 40 80" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<path d="M10,80 L25,80 L25,40 L38.59375,40 L40,26.25 L25,26.25 L25,19.375 C25,17.29166 25.3125,15.83334 25.9375,15 C26.5625,14.16666 28.0729,13.75 30.46875,13.75 L40,13.75 L40,0 L26.25,0 C19.99997,0 15.72918,1.48436 13.4375,4.45312 C11.14582,7.42189 10,11.97914 10,18.125 L10,26.25 L0,26.25 L0,40 L10,40 L10,80 Z" />
						</svg>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( $twitter ) : ?>
				<li class="rhd-social-icon twitter-icon">
					<a href="//twitter.com/<?php echo esc_attr( $twitter ); ?>" target="_blank">
						<svg width="100%" height="100%" viewBox="0 0 80 66" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" >
							<path d="M77.8125,1.75 C74.58332,3.62501 71.09377,4.92708 67.34375,5.65625 C64.11457,2.21873 60.10419,0.5 55.3125,0.5 C50.83331,0.5 46.97918,2.11457 43.75,5.34375 C40.52082,8.57293 38.90625,12.42706 38.90625,16.90625 C38.90625,18.15626 39.0625,19.40624 39.375,20.65625 C32.7083,20.34375 26.43232,18.6771 20.54687,15.65625 C14.66143,12.6354 9.68752,8.57294 5.625,3.46875 C4.062492,5.96876 3.28125,8.72915 3.28125,11.75 C3.28125,17.58336 5.729142,22.11457 10.625,25.34375 C8.02082,25.34375 5.572928,24.66667 3.28125,23.3125 L3.28125,23.625 C3.28125,27.47919 4.505196,30.91665 6.953125,33.9375 C9.401054,36.95835 12.55206,38.88541 16.40625,39.71875 C14.94791,40.03125 13.48959,40.1875 12.03125,40.1875 C10.98958,40.1875 9.94792,40.08333 8.90625,39.875 C10.05209,43.20835 12.00519,45.9427 14.76563,48.07812 C17.52606,50.21355 20.67707,51.28125 24.21875,51.28125 C18.28122,55.968773 11.51045,58.3125 3.90625,58.3125 C2.656244,58.3125 1.354173,58.260417 0,58.15625 C7.708372,63.052108 16.0937,65.5 25.15625,65.5 C32.44795,65.5 39.11455,64.093764 45.15625,61.28125 C51.19795,58.468736 56.11977,54.7969 59.92187,50.26562 C63.72398,45.73435 66.66666,40.7344 68.75,35.26562 C70.83334,29.79685 71.875,24.30211 71.875,18.78125 L71.875,16.75 C75.10418,14.35415 77.81249,11.4896 80,8.15625 C76.97915,9.51042 73.85418,10.39583 70.625,10.8125 C74.16668,8.62499 76.56249,5.60419 77.8125,1.75 L77.8125,1.75 Z" />
						</svg>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( $pinterest ) : ?>
				<li class="rhd-social-icon pinterest-icon">
					<a href="//pinterest.com/<?php echo esc_attr( $pinterest ); ?>" target="_blank">
						<svg width="100%" height="100%" viewBox="0 0 80 80" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" >
							<path d="M40,0 C28.95828,0 19.53129,3.90621 11.71875,11.71875 C3.906211,19.53129 0,28.95828 0,40 C0,51.04172 3.906211,60.46871 11.71875,68.28125 C19.53129,76.093789 28.95828,80 40,80 C51.04172,80 60.46871,76.093789 68.28125,68.28125 C76.09379,60.46871 80,51.04172 80,40 C80,28.95828 76.09379,19.53129 68.28125,11.71875 C60.46871,3.90621 51.04172,0 40,0 L40,0 Z M43.125,50.78125 C42.1875,50.78125 41.43229,50.65104 40.85938,50.39062 C40.28646,50.13021 39.50521,49.66146 38.51562,48.98438 C37.52604,48.30729 36.92708,47.91667 36.71875,47.8125 C35.88541,51.87502 34.94792,55.20832 33.90625,57.8125 C32.86458,60.41668 31.35418,62.49999 29.375,64.0625 C28.85416,62.08332 28.75,59.68751 29.0625,56.875 C29.375,54.06249 29.6875,51.92709 30,50.46875 C30.3125,49.01041 30.80729,47.00522 31.48438,44.45312 C32.16146,41.90103 32.65625,39.94792 32.96875,38.59375 C32.23958,36.82291 31.95312,34.92189 32.10938,32.89062 C32.26563,30.85936 33.02083,29.16667 34.375,27.8125 C35.72917,26.45833 37.29166,26.14583 39.0625,26.875 C40.62501,27.5 41.38021,28.80207 41.32812,30.78125 C41.27604,32.76043 40.78125,34.76561 39.84375,36.79688 C38.90625,38.82814 38.51562,40.7552 38.67188,42.57812 C38.82813,44.40105 39.89582,45.52083 41.875,45.9375 C44.06251,46.35417 46.01562,45.72917 47.73438,44.0625 C49.45313,42.39583 50.65104,40.31251 51.32812,37.8125 C52.00521,35.31249 52.1875,32.70835 51.875,30 C51.5625,27.29165 50.67709,25.20834 49.21875,23.75 C47.23957,21.77082 44.81772,20.67708 41.95312,20.46875 C39.08853,20.26042 36.48439,20.78124 34.14062,22.03125 C31.79686,23.28126 29.8698,25.15624 28.35938,27.65625 C26.84895,30.15626 26.35416,32.86457 26.875,35.78125 C26.97917,36.51042 27.31771,37.34375 27.89062,38.28125 C28.46354,39.21875 28.80208,39.97396 28.90625,40.54688 C29.01042,41.11979 28.75,42.18749 28.125,43.75 C23.33331,42.70833 21.09375,39.01045 21.40625,32.65625 C21.51042,28.28123 23.17707,24.55731 26.40625,21.48438 C29.63543,18.41144 33.3854,16.61459 37.65625,16.09375 C42.86461,15.57291 47.47394,16.5104 51.48438,18.90625 C55.49481,21.3021 57.8125,24.79164 58.4375,29.375 C59.27084,35.00003 58.20314,40.0781 55.23438,44.60938 C52.26561,49.14065 48.22919,51.19792 43.125,50.78125 L43.125,50.78125 Z" />
						</svg>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( $instagram ) : ?>
				<li class="rhd-social-icon instagram-icon">
					<a href="//instagram.com/<?php echo esc_attr( $instagram ); ?>" target="_blank">
						<svg width="100%" height="100%" viewBox="0 0 81 80" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" >
							<path d="M77.09375,0 L4.28125,0 C3.343745,0 2.51042,0.36458 1.78125,1.09375 C1.0520797,1.82292 0.6875,2.65625 0.6875,3.59375 L0.6875,76.40625 C0.6875,77.343755 1.0520797,78.17708 1.78125,78.90625 C2.51042,79.6354203 3.343745,80 4.28125,80 L77.09375,80 C78.03125,80 78.86458,79.6354203 79.59375,78.90625 C80.32292,78.17708 80.6875,77.343755 80.6875,76.40625 L80.6875,3.59375 C80.6875,2.65625 80.32292,1.82292 79.59375,1.09375 C78.86458,0.36458 78.03125,0 77.09375,0 L77.09375,0 Z M54.59375,40 C54.59375,43.85419 53.2396,47.1354 50.53125,49.84375 C47.8229,52.5521 44.54169,53.90625 40.6875,53.90625 C36.83331,53.90625 33.5521,52.5521 30.84375,49.84375 C28.1354,47.1354 26.78125,43.85419 26.78125,40 C26.78125,36.14581 28.1354,32.8646 30.84375,30.15625 C33.5521,27.4479 36.83331,26.09375 40.6875,26.09375 C44.54169,26.09375 47.8229,27.4479 50.53125,30.15625 C53.2396,32.8646 54.59375,36.14581 54.59375,40 L54.59375,40 Z M70.6875,70 L10.6875,70 L10.6875,35 L17.25,35 C16.9375,36.66667 16.78125,38.33333 16.78125,40 C16.78125,46.56253 19.12498,52.18748 23.8125,56.875 C28.50002,61.56252 34.12497,63.90625 40.6875,63.90625 C47.25003,63.90625 52.87498,61.56252 57.5625,56.875 C62.25002,52.18748 64.59375,46.56253 64.59375,40 C64.59375,38.33333 64.4375,36.66667 64.125,35 L70.6875,35 L70.6875,70 Z M70.6875,25 L55.6875,25 L55.6875,10 L70.6875,10 L70.6875,25 Z" />
						</svg>
					</a>
				</li>
			<?php endif; ?>

			<?php if ( $bloglovin ) : ?>
				<li class="rhd-social-icon bloglovin-icon">
					<a href="<?php echo esc_attr( $bloglovin ); ?>" target="_blank">
						<svg width="100%" height="100%" viewBox="0 0 88 77" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" >
							<defs>
								<polygon id="bl-path-1" points="0.024 0.295166667 0.024 76.0383021 62.9744 76.0383021 62.9744 0.295166667"></polygon>
						    </defs>
							<g transform="translate(0.000000, 0.802083)">
								<mask id="bl-mask-2" fill="white">
									<use xlink:href="#bl-path-1"></use>
								</mask>
								<path d="M36.9236,61.8434323 L16.0832,61.8434323 L16.0832,44.582599 L36.9236,44.582599 C43.1532,44.582599 46.5512,48.4438281 46.5512,53.2130156 C46.5512,58.663974 42.9268,61.8434323 36.9236,61.8434323 L36.9236,61.8434323 Z M36.244,30.3877292 L16.0832,30.3877292 L16.0832,14.4896354 L36.244,14.4896354 C41.6808,14.4896354 45.0788,17.7829896 45.0788,22.4386823 C45.0788,27.3217656 41.6808,30.3877292 36.244,30.3877292 L36.244,30.3877292 Z M41.0012,76.0383021 C55.4988,76.0383021 62.9744,66.9535052 62.9744,55.4841146 C62.9744,46.0588333 56.6316,38.2232813 48.4764,36.9744375 C55.612,35.4982031 61.502,29.0253906 61.502,19.6001094 C61.502,9.49345833 54.1396,0.295166667 39.7552,0.295166667 L0,0.295166667 L0,76.0383021 L41.0012,76.0383021 L41.0012,76.0383021 Z" mask="url(#bl-mask-2)" />
							</g>
							<path d="M87.828,11.5828854 C87.828,4.20171354 83.068,0 77.748,0 C72.876,0 68.8,4.08821875 68.8,8.97130208 C68.8,13.8539844 72.196,17.1473385 76.5,17.1473385 C77.408,17.1473385 78.312,17.0338437 78.652,16.8068542 C77.748,20.8946719 73.556,25.6642604 69.932,27.594474 L76.16,32.7049479 C83.184,27.7083698 87.828,20.0998073 87.828,11.5828854" />
						</svg>
					</a>
				</li>
			<?php endif; ?>
		</ul>

		<?php
		echo $after_widget;
	}

	public function form( $instance ) {
		// outputs the options form on admin
		$args = array();

		$args['title'] = ! empty( $instance['title'] )? esc_attr( $instance['title'] ) : '';
		$args['color1'] = ! empty( $instance['color1'] )? esc_attr( $instance['color1'] ) : '';
		$args['color2'] = ! empty( $instance['color2'] )? esc_attr( $instance['color2'] ) : '';
?>

		<?php wp_enqueue_style( 'rhd-social-icons', RHD_AG_PLUGIN_DIR . '/rhd-si-main.css' ); ?>

		<h3><?php _e( 'Widget Options:' ); ?></h3>
		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>">Widget Title (optional): </label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $args['title']; ?>" >
		</p>

		<div class="rhd-social-icons-entries">
			<p>
				<label for="<?php echo $this->get_field_name( 'color1' ); ?>">Base Color: </label>
				<input id="<?php echo $this->get_field_id( 'color1' ); ?>" name="<?php echo $this->get_field_name( 'color1' ); ?>" type="text" placeholder="#205c99" value="<?php echo $args['color1']; ?>" >
			</p>
			<p>
				<label for="<?php echo $this->get_field_name( 'color2' ); ?>">Hover Color: </label>
				<input id="<?php echo $this->get_field_id( 'color2' ); ?>" name="<?php echo $this->get_field_name( 'color2' ); ?>" type="text" placeholder="#0ab3ae" value="<?php echo $args['color2']; ?>" >
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