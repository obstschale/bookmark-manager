<?php
namespace BookmarkManager;

use PostTypes\PostType;
use Carbon_Fields\Field;
use Carbon_Fields\Container;

class BookmarksPostType extends BookmarkManager {

  function __construct() {

    // Sample Custom Post Type - Client
    $this->add_bookmarks_post_type();

  }

  private function add_bookmarks_post_type() {
    // Reference: https://github.com/jjgrainger/PostTypes

    $options = [
      'supports' => array('title', 'editor', 'thumbnail'),
      'labels' => array(
        'menu_name' => 'Bookmarks'
      ),
      'exclude_from_search' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_nav_menus' => false,
      'rewrite' => false,
      'has_archive' => true
    ];

    $cpt = new PostType(
      array(
        'name' => 'bookmarks',
        'singular' => 'Bookmark',
        'plural' => 'Bookmarks',
        'slug' => 'bookmarks'
      ), $options
    );
    $cpt->icon('dashicons-admin-links');

    // Add fields
    Container::make('post_meta', 'Bookmark Details')
      ->show_on_post_type($cpt->postTypeName)
      ->add_fields(array(
        Field::make('text', self::$prefix.'link', 'Link'),
      )
    );

  }

}
