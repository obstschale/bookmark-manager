<?php

namespace BookmarkManager\Migrations;

abstract class BaseMigration
{

    const OPTION_NAME = 'bookmark_manager_db_version';

    /**
     * @var BaseMigration
     */
    protected $next;

    /**
     * @var int Current database version
     */
    protected $current_version;


    public function __construct( BaseMigration $next = null )
    {
        $this->next = $next;
    }


    final public function migrate( int $version )
    {
        $this->set_current_version( $version );

        if ( $this->is_new() ) {
            $this->update_database();
        }

        if ( ! is_null( $this->next ) ) {
            $this->next->migrate( $version );
        }
    }


    abstract public function update_database();


    public function bump_db_version( int $version ) : bool
    {
        return update_option( self::OPTION_NAME, $version );
    }


    public function is_new()
    {
        return $this->get_current_version() < $this->get_version();
    }


    abstract public function get_version() : int;


    public function set_current_version( int $version ) : void
    {
        $this->current_version = $version;
    }


    public function get_current_version() : int
    {
        return $this->current_version;
    }

}
