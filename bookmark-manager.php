<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Bookmark Manager
 * Plugin URI:        https://github.com/obstschale/bookmark-manager
 * Description:       Let WordPress handle your bookmarks
 * Version:           0.1.1
 * Requires PHP:      7.0
 * Author:            Hans-Helge Buerger
 * Author URI:        https://hanshelgebuerger.de
 * License:           GPL-2.0
 * License URI:       https://opensource.org/licenses/GPL-2.0
 * Text Domain:       bookmark-manager
 * Domain Path:       /languages
 * GitHub Plugin URI: obstschale/bookmark-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

require( __DIR__ . '/vendor/autoload.php' );

// Initialize plugin
$bookmarkManager = new \BookmarkManager\BookmarkManager();
$bookmarkManager->register();
