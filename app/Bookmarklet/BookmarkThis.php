<?php
/**
 * Bookmark This Display and Handler.
 *
 */

define( 'IFRAME_REQUEST', true );

$base = $_GET[ 'base' ];

/** WordPress Administration Bootstrap */
require_once( $base . DIRECTORY_SEPARATOR . 'wp-load.php' );

/**
 * require screen.php for get_current_screen() function
 * otherwise, this could cause an fatal error. E.g. Jetpack
 * uses this function within the admin_enqueue_scripts hook.
 */
require_once( ABSPATH . 'wp-admin/includes/screen.php' );

$bookmarklet = new \BookmarkManager\Bookmarklet\Bookmarklet();
$bookmarklet->html();
