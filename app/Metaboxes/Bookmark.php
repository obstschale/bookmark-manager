<?php

namespace BookmarkManager\Metaboxes;

use Carbon_Fields\Field;
use Carbon_Fields\Container;
use BookmarkManager\PostTypes\Bookmarks;

/**
 * Bookmark metabox class to add a new metabox (custom field) to bookmark post type.
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\Metaboxes
 */
class Bookmark extends BaseMetabox
{

    const ID = 'bookmark_manager_link';


    /**
     * Add Carbon Fields container and register metabox thru that.
     */
    public function register_fields()
    {
        Container::make( 'post_meta', __( 'Bookmark Details', 'bookmark-manager' ) )
            ->where( 'post_type', '=', Bookmarks::NAME )
            ->add_fields( [
                Field::make( 'text', $this->get_id(), __( 'Link', 'bookmark-manager' ) ),
            ] );

        // Register meta field, making it available in REST API
        //    $this->register_meta( self::$prefix . 'link' );
    }


    /**
     * Get ID for this metabox.
     *
     * @since 0.2.0
     *
     * @return string Metabox's ID.
     */
    protected function get_id() : string
    {
        return self::ID;
    }

    //public function register_meta( $meta_key )
    //{
    //    // The object type. For custom post types, this is 'post';
    //    // for custom comment types, this is 'comment'. For user meta,
    //    // this is 'user'.
    //    $object_type = 'post';
    //    $args1       = [ // Validate and sanitize the meta value.
    //        // Note: currently (4.7) one of 'string', 'boolean', 'integer',
    //        // 'number' must be used as 'type'. The default is 'string'.
    //        'type'         => 'string',
    //        // Shown in the schema for the meta key.
    //        'description'  => 'A meta key associated with a string meta value.',
    //        // Return a single value of the type.
    //        'single'       => true,
    //        // Show in the WP REST API response. Default: false.
    //        'show_in_rest' => true,
    //        'auth_callback' => function() { return true; },
    //    ];
    //    register_meta( $object_type, '_' . $meta_key, $args1 );
    //
    //}
}
