<?php

namespace BookmarkManager\PostTypes;

use PostTypes\PostType;
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use BookmarkManager\BookmarkManager;

class BookmarksPostType extends BookmarkManager
{

    /**
     * @var PostType
     */
    public $cpt = null;

    /**
     * Custom Post Type slug
     *
     * @var string
     */
    public static $name = 'bookmarks';


    public function __construct()
    {

        add_filter( 'post_row_actions', [ $this, 'open_bookmark_action' ], 10, 2 );

        // Sample Custom Post Type - Client
        $this->add_bookmarks_post_type();
        $this->add_meta_fields();
        $this->add_tags_taxonomy();

    }


    public function add_bookmarks_post_type()
    {
        // Reference: https://github.com/jjgrainger/PostTypes

        $options = [
            'supports'              => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
            'labels'                => [
                'menu_name' => __( 'Bookmarks', 'bookmark-manager' ),
            ],
            'publicly_queryable'    => true,
            'show_in_nav_menus'     => true,
            'has_archive'           => true,
            'show_in_rest'          => true,
            'rest_base'             => 'bookmarks',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        ];

        $labels = [
            'featured_image' => __( 'Screenshot', 'bookmark-manager' ),
        ];

        $this->cpt = new PostType( [
            'name'     => self::$name,
            'singular' => 'Bookmark',
            'plural'   => 'Bookmarks',
            'slug'     => 'bookmarks'
        ], $options, $labels );

        $this->cpt->icon( 'dashicons-admin-links' );

    }


    public function add_meta_fields()
    {

        Container::make( 'post_meta', 'Bookmark Details' )->show_on_post_type( $this->cpt->postTypeName )->add_fields( [
            Field::make( 'text', self::$prefix . 'link', 'Link' ),
        ] );

        // Register meta field, making it available in REST API
        $this->register_meta( self::$prefix . 'link' );

    }


    public function add_tags_taxonomy()
    {
        $name = [
            'name'     => 'bookmark_manager_tag',
            'singular' => 'Tag',
            'plural'   => 'Tags',
            'slug'     => 'bookmark-tag',
        ];

        $options = [
            'hierarchical'          => false,
            'show_in_rest'          => true,
            'rest_base'             => 'bookmark-tags',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ];

        $this->cpt->taxonomy( $name, $options );
    }


    public function register_meta( $meta_key )
    {
        // The object type. For custom post types, this is 'post';
        // for custom comment types, this is 'comment'. For user meta,
        // this is 'user'.
        $object_type = 'post';
        $args1       = [ // Validate and sanitize the meta value.
            // Note: currently (4.7) one of 'string', 'boolean', 'integer',
            // 'number' must be used as 'type'. The default is 'string'.
            'type'         => 'string',
            // Shown in the schema for the meta key.
            'description'  => 'A meta key associated with a string meta value.',
            // Return a single value of the type.
            'single'       => true,
            // Show in the WP REST API response. Default: false.
            'show_in_rest' => true,
            'auth_callback' => function() { return true; },
        ];
        register_meta( $object_type, '_' . $meta_key, $args1 );

    }


    public function open_bookmark_action($actions, $post)
    {
        if ( $post->post_type == self::$name ) {
            $url = carbon_get_the_post_meta( self::$prefix . 'link' );
            $actions['open_bookmark'] = sprintf(
                __( '<a href="%1$s" title="%2$s">Open Bookmark</a>', 'bookmark-manager' ),
                    esc_url( $url ),
                    $post->post_title
            );
        }

        return $actions;
    }

}
