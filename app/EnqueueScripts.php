<?php

namespace BookmarkManager;

/**
 * Register and enqueue scripts and styles for Bookmark Manager.
 *
 * @TODO    If not Debug enqueue min.js versions
 *
 * @package BookmarkManager
 */
class EnqueueScripts extends BookmarkManager
{

    function __construct()
    {

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

    }


    /**
     * Enqueue scripts used on frontend of site
     */
    public function enqueue_frontend_scripts()
    {

        // Enqueuing custom CSS for child theme (Twentysixteen was used for testing)
        //wp_register_style( 'bookmark-manager-bookmarklet-style', BookmarkManager::plugin_url() . 'public/css/bookmarklet.css', [], '1.0' );

    }


    /**
     * Enqueue scripts used in WP admin interface
     */
    public function enqueue_admin_scripts()
    {

        # Enqueue JS
        /**
         * @TODO register script only and enqueue it in admin class
         */
        wp_enqueue_script( 'carbon-field-bookmarklet-field', BookmarkManager::plugin_url() . 'public/js/admin.js',
            [ 'carbon-fields' ] );

        wp_register_script( 'bookmark-manager-bookmarklet', BookmarkManager::plugin_url() . 'public/js/bookmarklet.js',
            [], '1.0', true );

        # Enqueue CSS
        /**
         * @TODO register style only and enqueue it in admin class
         */
        wp_enqueue_style( 'bookmark-manager-admin-style', BookmarkManager::plugin_url() . 'public/css/admin.css' );

        wp_register_style( 'bulma', 'https://cdnjs.cloudflare.com/ajax/libs/bulma/0.5.1/css/bulma.min.css', [], '0.5.1' );

        wp_register_style( 'bookmark-manager-bookmarklet-style',
                BookmarkManager::plugin_url() . 'public/css/bookmarklet.css', [ 'bulma' ], '1.0' );

    }

}
