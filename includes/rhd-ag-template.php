<?php
/**
 * RHD AFFILIATE GALLERY
 *
 * Retrieves uncloaked affiliate links and redirects.
 *
 * @package WordPress
 * @subpackage rhd-affiliate-gallery
 */

get_header();

$get_link = $_GET['id'];

$images = get_post_meta( get_the_ID(), 'rhd-ag-images', true );
$links = array();
$i = 1; // 1-indexed array

foreach ( $images as $image ) {
	$links["affiliate-$i"] = $image['link'];
	$i++;
}

if ( array_key_exists( $get_link, $links ) ) {
	wp_redirect( $links[$get_link], 302 );
	exit;
} else {
	wp_redirect( home_url( '/404' ) );
	exit;
}

get_footer();