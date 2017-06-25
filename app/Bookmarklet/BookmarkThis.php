<?php
/**
 * Press This Display and Handler.
 *
 * @package WordPress
 * @subpackage Press_This
 */

define('IFRAME_REQUEST' , true);

$base = $_GET['base'];

/** WordPress Administration Bootstrap */
require_once( $base . DIRECTORY_SEPARATOR . 'wp-load.php' );

$bookmarklet = new \BookmarkManager\Bookmarklet\Bookmarklet();
$bookmarklet->html();
