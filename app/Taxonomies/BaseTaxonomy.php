<?php

namespace BookmarkManager\Taxonomies;

use BookmarkManager\Service;

/**
 * Abstract class BaseTaxonomy.
 *
 * @since 0.2.0
 *
 * @package BookmarkManager\Taxonomies
 */
abstract class BaseTaxonomy implements Service
{

    /**
     * Register taxonomy service.
     */
    abstract public function register();


    /**
     * Get the name of the taxonomy.
     *
     * @return string Name of taxonomy.
     */
    abstract public function get_name() : string;


    /**
     * Get the slug of the taxonomy.
     *
     * @return string Slug of taxonomy.
     */
    abstract public function get_slug() : string;
}
