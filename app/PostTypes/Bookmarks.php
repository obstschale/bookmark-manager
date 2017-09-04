<?php

namespace BookmarkManager\PostTypes;

use PostTypes\PostType;

/**
 * Bookmark Post Type class for setting up a new custom post type for bookmarks.
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\PostTypes
 */
class Bookmarks extends BaseCustomPostType
{

    const NAME = 'bookmark';
    const SLUG = 'bookmarks';


    /**
     * Register custom post type.
     */
    public function register()
    {
        // Don't forget to call parent's register method, which adds the CPT.
        parent::register();

        $this->post_type->translation( 'bookmark-manager' );
        $this->post_type->icon( 'dashicons-admin-links' );

        /**
         * Make new post type object available for other classes to add additional features,
         * like taxonomies or metaboxes.
         * 
         * @since 0.2.0
         */
        do_action( 'bookmark_manager.after_bookmark_post_type_registered', $this->post_type );
    }


    /**
     * Get the slug of this custom post type.
     *
     * @since 0.2.0
     *
     * @return string Bookmark post type slug.
     */
    protected function get_slug() : string
    {
        return self::SLUG;
    }


    /**
     * Get names array for the custom post type.
     *
     * @since 0.2.0
     *
     * @link  https://github.com/jjgrainger/PostTypes
     *
     * @return array Array of names for PosTypes abstraction.
     */
    protected function get_names() : array
    {
        return [
            'name'     => $this->get_name(),
            'singular' => __( 'Bookmark', 'bookmark-manager' ),
            'plural'   => __( 'Bookmarks', 'bookmark-manager' ),
            'slug'     => $this->get_slug(),
        ];
    }


    /**
     * Get the name of the custom post type.
     *
     * @return string Custom post type name.
     */
    protected function get_name() : string
    {
        return self::NAME;
    }


    /**
     * Get the labels that name the custom post type.
     *
     * @since 0.2.0
     *
     * @return array Array of labels.
     */
    protected function get_labels() : array
    {
        return [// Will be generated by Lib
        ];
    }


    /**
     * Get the options that configure the custom post type.
     *
     * @since 0.2.0
     *
     * @return array Array of options.
     */
    protected function get_options() : array
    {
        return [
            'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
            'publicly_queryable'    => true,
            'show_in_nav_menus'     => true,
            'has_archive'           => true,
            'show_in_rest'          => true,
            'rest_base'             => $this->get_slug(),
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];
    }
}
