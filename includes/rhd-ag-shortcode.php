<?php
/**
 * RHD AFFILIATE GALLERY
 *
 * Shortcode
**/

add_shortcode( 'rhd-ag', 'rhd_ag_shortcode' );
function rhd_ag_shortcode( $atts ) {
	global $post;

	extract( shortcode_atts(
		array(
			'gallery_id'	=> 0,
			'gallery_slug'	=> null,
			'cols'			=> null,
			'thumbsize'		=> null,
		),
		$atts
	));

	if ( $gallery_slug && ! $gallery_id ) {
		$gallery = get_page_by_path( $gallery_slug, OBJECT, 'affiliate_gallery' );
		$gallery_id = $gallery->ID;
	} else {
		$gallery_id = 0;
	}

	$output = rhd_affiliate_gallery( $gallery_id, $cols, $thumbsize, false );

	return $output;
}