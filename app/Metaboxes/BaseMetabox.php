<?php

namespace BookmarkManager\Metaboxes;

use BookmarkManager\Service;
use Carbon_Fields\Carbon_Fields;

/**
 * Abstract class BaseMetabox to add new metaboxes.
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\Metaboxes
 */
abstract class BaseMetabox implements Service
{

    /**
     * Register the metabox using Carbon Fields.
     *
     * @since 0.2.0
     */
    public function register()
    {
        add_action( 'after_setup_theme', function () {
            Carbon_Fields::boot();
        } );
        add_action( 'carbon_fields_register_fields', [ $this, 'register_fields' ] );
    }


    /**
     * Get ID for this metabox.
     *
     * @since 0.2.0
     *
     * @return string Metabox's ID.
     */
    abstract public function get_id() : string;


    /**
     * Register meta fields using Carbon Fields.
     */
    abstract public function register_fields();
}
