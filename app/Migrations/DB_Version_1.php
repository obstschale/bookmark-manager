<?php

namespace BookmarkManager\Migrations;

/**
 * Migration 1: DB_Version_1 class.
 *
 * This migration updates the old post type value 'bookmarks' to
 * the new value 'bookmark'. The singular term is used now and this
 * migration updates old bookmarks, so they don't get lost.
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\Migrations
 */
class DB_Version_1 extends BaseMigration
{

    const VERSION = 1;


    /**
     * Migration 1 updates post type value to new singular name.
     *
     * @since 0.2.0
     *
     * @return int|\WP_Error
     */
    public function update_database()
    {
        global $wpdb;

        /**
         * Hard code post type here instead of using PostTypes/Bookmarks::NAME to avoid
         * complications in the future if another migration would also update the post type and
         * PostTypes/Bookmarks::NAME would change. So be explicit here!
         */
        $result = $wpdb->update( $wpdb->posts, [ 'post_type' => 'bookmark' ],
            [ 'post_type' => 'bookmarks' ] );

        if ( $result === false ) {
            $error = new \WP_Error();
            $error->add( "migration1",
                __( "Migration 1 failed, while updating old post type 'bookmarks' to new one 'bookmark'.",
                    'bookmark-manager' ) );

            return $error;
        }

        $this->bump_db_version( $this->get_version() );

        return $result;
    }


    /**
     * Get database version number, which this migration sets.
     *
     * @since 0.2.0
     *
     * @return int
     */
    public function get_version() : int
    {
        return self::VERSION;
    }
}
