<?php

namespace BookmarkManager;

use BookmarkManager\Admin\AdminFooter;
use BookmarkManager\Settings\Settings;
use BookmarkManager\Widgets\WidgetLoader;
use BookmarkManager\PostTypes\BookmarksPostType;
use BookmarkManager\REST\REST_Bookmarks_Controller;

class BookmarkManager
{

    public static $settings;

    public static $version;

    public static $prefix;

    protected static $registry;


    function __construct()
    {
        $_settings = [
            'data'                => get_plugin_data( __FILE__ ),
            'path'                => realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR,
            'url'                 => plugin_dir_url( __FILE__ ),
            'object_cache_group'  => 'bookmark_manager_cache',
            'object_cache_expire' => 72, // In hours
            'prefix'              => 'bookmark_manager_', // Change to your own unique field prefix
        ];

        // Set text domain and option prefix
        self::$settings = $_settings;
        self::$prefix   = $_settings[ 'prefix' ];
        self::$version  = $_settings[ 'data' ][ 'Version' ];

        // Enqueue scripts
        new EnqueueScripts();


        // Add admin settings page(s)
        new Settings();

        // Create Custom Post Type(s)
        new BookmarksPostType();

        // Create custom widgets
        //new WidgetLoader();

        // Edit Admin Footer Text
        new AdminFooter();

        $rest = new REST_Bookmarks_Controller();
        $rest->register();

    }


    private static function load_registry()
    {
        self::$registry = [
            'data'                => get_plugin_data( dirname( dirname( __FILE__ ) ) . '/bookmark-manager.php' ),
            'plugin_url'          => plugin_dir_url( dirname( __FILE__ ) . 'bookmark-manager.php' ),
            'plugin_path'         => realpath( dirname( plugin_dir_path( __FILE__ ) ) ) . DIRECTORY_SEPARATOR,
            'object_cache_group'  => 'bookmark_manager_cache',
            'object_cache_expire' => 72, // In hours
            'prefix'              => 'bookmark_manager_', // Change to your own unique field prefix
        ];
    }


    public static function get_registry_value( $key )
    {
        if ( empty( self::$registry ) ) {
            self::load_registry();
        }

        $value = self::$registry[ $key ] ?? false;

        if ( $value !== false ) {
            return $value;
        }

        return self::$registry['data'][ $key ] ?? false;
    }


    public static function plugin_path()
    {
        return self::get_registry_value( 'plugin_path' );
    }


    public static function plugin_url()
    {
        return self::get_registry_value( 'plugin_url' );
    }


    public function version()
    {
        return self::get_registry_value( 'Version' );
    }


    /**
     * Returns true if WP_ENV is anything other than 'development' or 'staging'.
     *   Useful for determining whether or not to enqueue a minified or non-
     *   minified script (which can be useful for debugging via browser).
     *
     * @return bool
     */
    public function is_production()
    {
        if ( ! defined( 'WP_ENV' ) ) {
            return true;
        } else {
            return ! in_array( WP_ENV, [ 'development', 'staging' ] );
        }
    }


    /**
     * Returns true if request is via Ajax.
     *
     * @return bool
     */
    public function is_ajax()
    {
        return defined( 'DOING_AJAX' ) && DOING_AJAX;
    }


    /**
     * Returns script ?ver= version based on environment (WP_ENV)
     *
     * If WP_ENV is not defined or equals anything other than 'development' or 'staging'
     * returns $script_version (if defined) else plugin verson. If WP_ENV is defined
     * as 'development' or 'staging', returns string representing file last modification
     * date (to discourage browser during development).
     *
     * @param string $script         The filesystem path (relative to the script location of
     *                               calling script) to return the version for.
     * @param string $script_version (optional) The version that will be returned if
     *                               WP_ENV is defined as anything other than 'development' or 'staging'.
     *
     * @return string
     */
    public function get_script_version( $script, $return_minified = false, $script_version = null )
    {
        $version = $script_version ?: self::$settings[ 'data' ][ 'Version' ];
        if ( $this->is_production() ) {
            return $version;
        }

        $script = $this->get_script_path( $script, $return_minified );
        if ( file_exists( $script ) ) {
            $version = date( "ymd-Gis", filemtime( $script ) );
        }

        return $version;
    }


    /**
     * Returns script path or URL, either regular or minified (if exists).
     *
     * If in production mode or if @param $force_minify == true, inserts '.min' to the filename
     *                                (if exists), else return script name without (example: style.css vs
     *                                style.min.css).
     *
     * @param string $script          The relative (to the plugin folder) path to the script.
     * @param bool   $return_minified If true and is_production() === true then will prefix the
     *                                extension with .min. NB! Due to performance reasons, I did not include logic to
     *                                check to see if the script_name.min.ext exists, so use only when you know it
     *                                exists.
     * @param bool   $return_url      If true, returns full-qualified URL rather than filesystem path.
     *
     * @return string The URL or path to minified or regular $script.
     */
    public function get_script_path( $script, $return_minified = false, $return_url = false )
    {
        $script = trim( $script, '/' );
        if ( $return_minified && strpos( $script, '.' ) && $this->is_production() ) {
            $script_parts     = explode( '.', $script );
            $script_extension = end( $script_parts );
            array_pop( $script_parts );
            $script = implode( '.', $script_parts ) . '.min.' . $script_extension;
        }

        return self::$settings[ $return_url ? 'url' : 'path' ] . $script;
    }


    /**
     * Returns absolute URL of $script.
     *
     * @param string $script The relative (to the plugin folder) path to the script.
     * @param        bool
     */
    public function get_script_url( $script, $return_minified = false )
    {
        return $this->get_script_path( $script, $return_minified, true );
    }


    /**
     * Merges two array, eliminating duplicates
     *
     * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
     * Matching keys' values in the second array overwrite those in the first array, as is the
     * case with array_merge().
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    private function array_merge_recursive_distinct( array &$array1, array &$array2 )
    {
        // Credit: http://php.net/manual/en/function.array-merge-recursive.php#92195
        $merged = $array1;

        foreach ( $array2 as $key => &$value ) {
            if ( is_array( $value ) && isset ( $merged [ $key ] ) && is_array( $merged [ $key ] ) ) {
                $merged [ $key ] = self::array_merge_recursive_distinct( $merged [ $key ], $value );
            } else {
                $merged [ $key ] = $value;
            }
        }

        return $merged;
    }

}
