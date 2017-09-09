<?php

namespace BookmarkManager;

use BookmarkManager\Metaboxes;
use BookmarkManager\Migrations\MigrationService;
use BookmarkManager\PostTypes;
use BookmarkManager\Taxonomies;
use BookmarkManager\Admin\AdminFooter;
use BookmarkManager\Settings\Settings;
use BookmarkManager\Widgets\WidgetLoader;
use BookmarkManager\Provider\ServiceProvider;
use BookmarkManager\REST\REST_Bookmarks_Controller;

class BookmarkManager implements Registerable
{

    public static $settings;

    public static $prefix;

    protected static $registry;

    protected $enqueue_scripts;


    function __construct()
    {
        $_settings = [
            'object_cache_group'  => 'bookmark_manager_cache',
            'object_cache_expire' => 72, // In hours
            'prefix'              => 'bookmark_manager_', // Change to your own unique field prefix
        ];

        // Set text domain and option prefix
        self::$settings = $_settings;
        self::$prefix   = $_settings[ 'prefix' ];

        //$this->enqueue_scripts = $enqueue_scripts;
        new EnqueueScripts();

        // Add admin settings page(s)
        //new Settings();

        // Create custom widgets
        //new WidgetLoader();

        // Edit Admin Footer Text
        new AdminFooter();

        $rest = new REST_Bookmarks_Controller();
        $rest->register();

    }


    private static function load_registry()
    {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
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

        return self::$registry[ 'data' ][ $key ] ?? false;
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
    public static function is_production()
    {
        if ( ! defined( 'WP_ENV' ) ) {
            return true;
        } else {
            return ! in_array( WP_ENV, [ 'development', 'staging' ] );
        }
    }


    /**
     * Register the plugin with the WordPress system.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function register()
    {
        add_action( 'plugins_loaded', [ $this, 'register_services' ] );
    }


    /**
     * Register the individual services of this plugin.
     *
     * @since 0.2.0
     */
    public function register_services()
    {
        $services = $this->get_services();

        foreach ( $services as $class ) {
            $service = new $class();
            if ( $service instanceof Service ) {
                $service->register();
            }
        }
    }


    /**
     * Get services to register for this plugin.
     *
     * @since 0.2.0
     *
     * @return array Array of services to register.
     */
    private function get_services() : array
    {
        return [
            MigrationService::class,
            Taxonomies\BookmarkTags::class,
            Metaboxes\BookmarkLink::class,
            PostTypes\Bookmarks::class,
            Settings::class,
        ];
    }
}
