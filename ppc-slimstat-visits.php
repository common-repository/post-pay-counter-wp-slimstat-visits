<?php
/*
Plugin Name: Post Pay Counter - WP-Slimstat visits
Plugin URI: http://postpaycounter.com/pay-writers-per-visit-wordpress
Description: Allows integration of WP-Slimstat data with Post Pay Counter.
Author: Stefano Ottolenghi
Version: 1.1
Author URI: http://www.thecrowned.org/
*/

global $ppc_wp_slimstat_include_status;

/*
 * Includes WP-Slimstat database file to make queries.
 *
 * @since	1.0
 */ 
function ppc_wp_slimstat_include_file() {
	global $ppc_wp_slimstat_include_status;

	if ( ! is_file( $dir = WPMU_PLUGIN_DIR . '/wp-slimstat/admin/view/wp-slimstat-db.php' ) ) {
		if ( ! is_file( $dir = WP_PLUGIN_DIR . '/wp-slimstat/admin/view/wp-slimstat-db.php' ) )
			$dir = false;
	}

	if( $dir )
		$ppc_wp_slimstat_include_status = @include_once( $dir );
	else
		$ppc_wp_slimstat_include_status = false;
		
}
add_action( "init", "ppc_wp_slimstat_include_file" );

/*
 * Gets post visits.
 *
 * @since	1.0
 * @param	$post object a WP_Post object
 * @return 	int post visits
 */
function ppc_wp_slimstat_views( $post ) {
	global $ppc_wp_slimstat_include_status;

	if( $ppc_wp_slimstat_include_status ) {
		$filters = 'content_id equals ' . $post->ID;
		wp_slimstat_db::init( $filters );
		$count_post_views = wp_slimstat_db::count_records( "id", "", false );
	} else {
		$count_post_views = -1; //On negative figures we know there was an issue including the db file
	}
	
	return $count_post_views;
}
