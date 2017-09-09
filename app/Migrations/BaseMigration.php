<?php

namespace BookmarkManager\Migrations;

/**
 * BaseMigration or base chain element.
 *
 * Each migration extends BaseMigration to be proper chain element.
 *
 * DB version means in this context, the Bookmark Manager DB version, not WordPress'.
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\Migrations
 */
abstract class BaseMigration
{

    /**
     * DB version option name.
     */
    const OPTION_NAME = 'bookmark_manager_db_version';

    /**
     * @var BaseMigration Successor of this element.
     */
    protected $next;

    /**
     * @var int Current database version
     */
    protected $current_version;


    /**
     * BaseMigration constructor.
     *
     * Set next chain element (successor). This migration will be called after
     * this migration has finished. If $next is NULL the chain ends and this is
     * the last migration.
     *
     * @param BaseMigration|null $next Successor in chain.
     */
    public function __construct( BaseMigration $next = null )
    {
        $this->next = $next;
    }


    /**
     * Migrate method does some checks and calls the update method if necessary.
     *
     * Firstly, check if this migration is newer than the current DB version. If so
     * the update procedure is called and the result is checked for \WP_Error.
     *
     * The next chain element (next migration) is called as long as no error is found.
     *
     * @param int $version Current DB version.
     *
     * @since 0.2.0
     *
     * @return bool Return false if an error is found.
     */
    final public function migrate( int $version )
    {
        $this->set_current_version( $version );

        if ( $this->is_new() ) {
            $response = $this->update_database();

            if ( is_wp_error( $response ) ) {
                /** @var $response \WP_Error */
                add_action( 'admin_notices', function () use ( $response ) {
                    $class   = 'notice notice-error';
                    $message = __( 'Irks! An migration error has occurred.', 'bookmark-manager' );

                    printf( '<div class="%1$s"><p><strong>%2$s</strong></p><p>%3$s</p></div>',
                        esc_attr( $class ), esc_html( $message ), $response->get_error_message() );
                } );

                return false;
            }
        }

        if ( ! is_null( $this->next ) ) {
            $this->next->migrate( $version );
        }
    }


    /**
     * Update procedure, which runs one or more SQL queries and updates the database.
     *
     * The method should either return the result of the query or return an \WP_Error instance
     * with a proper error code added to it.
     *
     * Don't for get to call $this->bump_db_version() afterwards to update the option.
     *
     * @since 0.2.0
     *
     * @return int|\WP_Error SQL result or \WP_Error instance if an error occurred.
     */
    abstract public function update_database();


    /**
     * Update DB version number.
     *
     * @param int $version New version.
     *
     * @return bool Result of update_option() call.
     */
    public function bump_db_version( int $version ) : bool
    {
        return update_option( self::OPTION_NAME, $version );
    }


    /**
     * Check if this migration is newer than the current DB version.
     *
     * @since 0.2.0
     *
     * @return bool Return true if migration needs to run, otherwise false.
     */
    public function is_new() : bool
    {
        return $this->get_current_version() < $this->get_version();
    }


    /**
     * Get database version number, which this migration sets.
     *
     * @since 0.2.0
     *
     * @return int
     */
    abstract public function get_version() : int;


    /**
     * Set current version, which is the current version in DB.
     * @since 0.2.0
     *
     * @param int $version DB Version
     */
    public function set_current_version( int $version )
    {
        $this->current_version = $version;
    }


    /**
     * Get current version, which is the current version in DB.
     *
     * @since 0.2.0
     *
     * @return int DB Version
     */
    public function get_current_version() : int
    {
        return $this->current_version;
    }

}
