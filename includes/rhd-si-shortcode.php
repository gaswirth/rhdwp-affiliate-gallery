<?php
/**
 * RHD SOCIAL ICONS
 *
 * Shortcode
**/

add_shortcode( 'rhd-social-icons', 'rhd_social_icons_shortcode' );
function rhd_social_icons_shortcode( $atts ) {
	extract( shortcode_atts(
		array(
			'title' 		=> '',
			'color1'		=> null,
			'color2'		=> null,
			'widget_loc'	=> 'sidebar'
		),
		$atts
	));

	$args = array(
		'before_title'	=> '<h2 class="widget-title">',
		'after_title'	=> '</h2>',
		'before_widget' => '<div id="rhd-social-icons-' . $widget_loc . '-widget" class="widget widget-rhd-social-icons-' . $widget_loc . '">',
		'after_widget'  => '</div>'
	);

	ob_start();
	the_widget( 'RHD_Social_Icons', $atts, $args );
	$output = ob_get_clean();

	return $output;
}