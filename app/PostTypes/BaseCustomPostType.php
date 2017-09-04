<?php

namespace BookmarkManager\PostTypes;

use PostTypes\PostType;
use BookmarkManager\Service;

/**
 * Abstract class BaseCustomPostType to register new post types.
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\PostTypes
 */
abstract class BaseCustomPostType implements Service
{

    /**
     * Post Type object.
     *
     * @var PostTypes
     * @link https://github.com/jjgrainger/PostTypes
     */
    protected $post_type;


    /**
     * Register the custom post type.
     *
     * @since 0.2.0
     */
    public function register()
    {
        // The PostType lib registers the custom post type
        // and add its own action hook into 'init'.
        $this->post_type = new PostType( $this->get_names(), $this->get_options(),
            $this->get_labels() );
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
    abstract protected function get_names() : array;


    /**
     * Get the name of the custom post type.
     *
     * @since 0.2.0
     *
     * @return string
     */
    abstract public function get_name() : string;


    /**
     * Get the slug to use for the custom post type.
     *
     * @since 0.2.0
     *
     * @return string Custom post type slug.
     */
    abstract public function get_slug() : string;


    /**
     * Get the labels that name the custom post type.
     *
     * @since 0.2.0
     *
     * @return array Array of labels.
     */
    abstract protected function get_labels() : array;


    /**
     * Get the options that configure the custom post type.
     *
     * @since 0.2.0
     *
     * @return array Array of options.
     */
    abstract protected function get_options() : array;
}
