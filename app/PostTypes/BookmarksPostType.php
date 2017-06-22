<?php

namespace BookmarkManager\PostTypes;

use PostTypes\PostType;
use Carbon_Fields\Field;
use Carbon_Fields\Container;
use BookmarkManager\BookmarkManager;

class BookmarksPostType extends BookmarkManager
{

    /**
     * @var PostType
     */
    public $cpt = null;


    public function __construct()
    {

        // Sample Custom Post Type - Client
        $this->add_bookmarks_post_type();
        $this->add_meta_fields();
        $this->add_tags_taxonomy();

    }


    public function add_bookmarks_post_type()
    {
        // Reference: https://github.com/jjgrainger/PostTypes

        $options = [
            'supports'              => [ 'title', 'editor', 'thumbnail' ],
            'labels'                => [
                'menu_name' => __( 'Bookmarks', 'bookmark-manager' ),
            ],
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_nav_menus'   => false,
            'rewrite'             => false,
            'has_archive'         => true
        ];

        $labels = [
            'featured_image' => __( 'Screenshot', 'bookmark-manager' ),
        ];

        $this->cpt = new PostType( [
            'name'     => 'bookmarks',
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
            'hierarchical' => false,
        ];

        $this->cpt->taxonomy( $name, $options );
    }
}
