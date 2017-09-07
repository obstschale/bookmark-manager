<?php

namespace BookmarkManager\Settings;

use BookmarkManager\PostTypes\Bookmarks;
use BookmarkManager\Service;
use BookmarkManager\Settings\Fields\Number_Field;
use Carbon_Fields\Field;
use Carbon_Fields\Container;
use Carbon_Fields\Carbon_Fields;
use BookmarkManager\BookmarkManager;

class Settings implements Service
{

    /**
     * Settings Carbon Fields Container.
     *
     * @var Container
     */
    public $container;

    public static $prefix = 'bookmark_manager_';


    /**
     * Create a options/settings page in WP Admin
     */
    public function register()
    {
        add_action( 'after_setup_theme', function () {
            Carbon_Fields::boot();

            //define( 'Carbon_Field_Number\\DIR', __DIR__ );
            Carbon_Fields::extend( Number_Field::class, function ( $container ) {
                return new Number_Field( $container[ 'arguments' ][ 'type' ],
                    $container[ 'arguments' ][ 'name' ], $container[ 'arguments' ][ 'label' ] );
            } );
        } );
        add_action( 'carbon_fields_register_fields', [ $this, 'register_fields' ] );
    }


    public function register_fields()
    {
        $this->container = Container::make( 'theme_options', __( 'Settings', 'bookmark-manager' ) )
            ->set_page_parent( 'edit.php?post_type=' . Bookmarks::NAME );

        $this->add_settings_tabs();
        $this->add_paparazzo_tab();
        /** TODO: Here would be a good place for a filter, which can add new settings tabs */
        $this->add_debug_tab();
    }


    /**
     * @link https://carbonfields.net/docs/containers-theme-options/ Carbin Fields Docs
     */
    public function add_settings_tabs()
    {
        $this->container->add_tab( __( 'General' ), [
            //Field::make( 'bookmarklet', self::$prefix . 'bookmarklet' ),
            Field::make( 'number', self::$prefix . 'bookmarklet' ),
        ] );
    }


    public function add_paparazzo_tab()
    {
        if ( ! BookmarkManager::is_production() ) {
            $this->container->add_tab( __( 'Paparazzo', 'bookmark-manager' ), [
                Field::make( 'text', self::$prefix . 'paparazzo', 'Paparazzo API' ),
            ] );
        }
    }


    public function add_debug_tab()
    {
        if ( isset( $_GET[ 'debug' ] ) || ! BookmarkManager::is_production() ) {
            $this->container->add_tab( __( 'Debug', 'bookmark-manager' ), [
                Field::make( 'textarea', self::$prefix . 'debug', 'DEBUG INFOS' ),
            ] );
        }
    }

}
