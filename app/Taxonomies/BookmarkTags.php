<?php

namespace BookmarkManager\Taxonomies;

use PostTypes\PostType;

class BookmarkTags extends BaseTaxonomy
{

    const NAME = 'bookmark_manager_tag';
    const SLUG = 'bookmark-tags';


    /**
     * Register taxonomy service.
     */
    public function register()
    {
        add_action( 'bookmark_manager.after_bookmark_post_type_registered',
            [ $this, 'register_taxonomy' ], 10, 1 );
    }


    /**
     * Register this taxonomy to bookmarks post type.
     *
     * @param PostType $bookmark
     */
    public function register_taxonomy( PostType $bookmark )
    {
        $bookmark->taxonomy( $this->get_names(), $this->get_options() );
    }


    /**
     * Get array with taxonomy names.
     *
     * @return array Array with names for taxonomy.
     */
    protected function get_names() : array
    {
        return [
            'name'     => $this->get_name(),
            'singular' => 'Tag',
            'plural'   => 'Tags',
            'slug'     => $this->get_slug(),
        ];
    }

    /**
     * Get the name of the taxonomy.
     *
     * @return string Name of taxonomy.
     */
    public function get_name() : string
    {
        return self::NAME;
    }


    /**
     * Get the slug of the taxonomy.
     *
     * @return string Slug of taxonomy.
     */
    public function get_slug() : string
    {
        return self::SLUG;
    }


    /**
     * Get array with options for taxonomy.
     *
     * @return array Array with taxonomy options.
     */
    public function get_options() : array
    {
        return [
            'hierarchical'          => false,
            'show_in_rest'          => true,
            'rest_base'             => 'bookmark-tags',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        ];
    }

}
