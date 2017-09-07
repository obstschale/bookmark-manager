<?php

namespace BookmarkManager\Settings\Fields;

use BookmarkManager\Bookmarklet\Bookmarklet;
use Carbon_Fields\Field\Field;
use BookmarkManager\BookmarkManager;

class Bookmarklet_Field extends Field
{

    /**
     * Prepare the field type for use
     * Called once per field type when activated
     */
    public static function field_type_activated()
    {
        // silence is gold
    }


    /**
     * Enqueue scripts and styles in admin
     * Called once per field type
     */
    public static function admin_enqueue_scripts()
    {
        $root_uri = BookmarkManager::plugin_url();

        # Enqueue JS
        wp_enqueue_script( 'carbon-field-bookmarklet', $root_uri . 'public/js/bundle.js',
            [ 'carbon-fields-boot' ] );

        # Enqueue CSS
        wp_enqueue_style( 'carbon-field-bookmarklet', $root_uri . 'public/css/field.css' );
    }


    /**
     * Returns an array that holds the field data, suitable for JSON representation.
     *
     * @param bool $load Should the value be loaded from the database or use the value from the
     *                   current instance.
     *
     * @return array
     */
    public function to_json( $load )
    {
        $field_data = parent::to_json( $load );

        $field_data = array_merge( $field_data, [
            'bookmarklet' => $this->get_bookmarklet_code(),
            'text'        => [
                'description'           => __( "Drag the bookmarklet below to your bookmarks bar. Then, when you're on a page you want to bookmark, simply \"bookmark\" this",
                    'bookmark-manager' ),
                'bookmark_this'         => __( "Bookmark This", 'bookmark-manger' ),
                'bookmarklet_code'      => __( "Copy \"Bookmark This\" bookmarklet code",
                    'bookmark-manger' ),
                'copy_code_description' => __( "If you can't drag the bookmarklet to your bookmarks, copy the following code and create a new bookmark. Paste the code into the new bookmark's URL field.",
                    'bookmark-manager' ),
            ],
        ] );

        return $field_data;
    }


    public function get_bookmarklet_code()
    {
        $src = @file_get_contents( BookmarkManager::plugin_path() . 'public/js/bookmark-this.js' );
        //$src = @file_get_contents( BookmarkManager::plugin_path() . 'resources/js/bookmarklet/bookmark-this.min.js' );

        // WordPress base url can differ from plugins URL. We have to transmit it
        // to be able to call AJAX or the REST API
        $base = wp_json_encode( get_home_path() );

        if ( $src ) {
            $url  = wp_json_encode( BookmarkManager::plugin_url() . 'app/Bookmarklet/BookmarkThis.php?v=' . Bookmarklet::version() );
            $src  = str_replace( 'window.bt_base', $base, $src );
            $link = 'javascript:void' . str_replace( 'window.bt_url', $url, $src );
        }

        $link = str_replace( [ "\r", "\n", "\t" ], '', $link );

        /**
         * Filters the Press This bookmarklet link.
         *
         * @since 1.0.0
         *
         * @param string $link The Bookmark This bookmarklet link.
         */
        return apply_filters( 'bookmark_this_link', $link );
    }
}
