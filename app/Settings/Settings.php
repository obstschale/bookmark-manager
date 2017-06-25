<?php

namespace BookmarkManager\Settings;

use Carbon_Fields\Field;
use Carbon_Fields\Container;
use BookmarkManager\BookmarkManager;

class Settings extends BookmarkManager
{

    /**
     * Settings Carbon Fields Container.
     *
     * @var Container
     */
    public $container;


    /**
     * Create a options/settings page in WP Admin
     */
    public function __construct()
    {
        include_once dirname(__FILE__) . '/Fields/Bookmarklet_Field.php';

        $this->container = Container::make( 'theme_options',
            __( 'Settings', 'bookmark-manager' ) )->set_page_parent( 'edit.php?post_type=bookmarks' );

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
            Field::make( 'bookmarklet', self::$prefix . 'bookmarklet'),
        ] );
    }


    public function add_paparazzo_tab()
    {
        if ( ! $this->is_production() ) {
            $this->container->add_tab( __( 'Paparazzo', 'bookmark-manager' ), [
                Field::make( 'text', self::$prefix . 'paparazzo', 'Paparazzo API' ),
            ] );
        }
    }


    public function add_debug_tab()
    {
        if ( isset( $_GET[ 'debug' ] ) || ! $this->is_production() ) {
            $this->container->add_tab( __( 'Debug', 'bookmark-manager' ), [
                Field::make( 'textarea', self::$prefix . 'debug', 'DEBUG INFOS' ),
            ] );
        }
    }

}
