<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Bookmark Manager
 * Plugin URI:        https://github.com/obstschale/bookmark-manager
 * Description:       Let WordPress handle your bookmarks
 * Version:           0.1.0
 * Author:            Hans-Helge Buerger
 * Author URI:        https://hanshelgebuerger.de
 * License:           GPL-2.0
 * License URI:       https://opensource.org/licenses/GPL-2.0
 * GitHub Plugin URI: obstschale/bookmark-manager
 */

/*	Copyright 2017	  Hans-Helge Buerger (https://hanshelgebuerger.de)

		This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

require( __DIR__ . '/vendor/autoload.php' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$updatePhp = new WPUpdatePhp( '5.4.0' );
$updatePhp->set_plugin_name( 'Bookmark Manager' );

if ( $updatePhp->does_it_meet_required_php_version() ) {

    // Initialize plugin - Change to use your own namespace
    return new \BookmarkManager\BookmarkManager( [
        'data'                => get_plugin_data( __FILE__ ),
        'path'                => realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR,
        'url'                 => plugin_dir_url( __FILE__ ),
        'object_cache_group'  => 'bookmark_manager_cache',
        'object_cache_expire' => 72, // In hours
        'prefix'              => 'bookmark_manager_', // Change to your own unique field prefix
    ] );

} else {
    add_action( 'admin_init', function () {
        // Deactivate Plugin
        deactivate_plugins( plugin_basename( __FILE__ ) );
    } );

    add_action( 'admin_notices', function () {
        // Remove 'Plugin activated' message
        if ( isset( $_GET[ 'activate' ] ) ) {
            unset( $_GET[ 'activate' ] );
        }
    } );
}
