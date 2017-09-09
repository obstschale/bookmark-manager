<?php

namespace BookmarkManager\Migrations;

use BookmarkManager\Service;

class MigrationService implements Service
{

    /**
     * @var array Array of all available migrations.
     */
    protected $chain;

    /**
     * @var int Current database version before running any migration.
     */
    protected $current_version;


    public function __construct()
    {
        $this->get_chain();
        $this->get_current_version();
    }


    /**
     * Register the current Registerable.
     *
     * @since 0.1.0
     *
     * @return void
     */
    public function register()
    {
        add_action( 'init', [ $this, 'migrate' ] );
    }


    public function migrate() : void
    {
        if ( $this->migration_needed() ) {
            $migration = $this->build_chain();

            $version   = $this->get_current_version();
            $migration->migrate( $version );
        }
    }


    public function build_chain() : BaseMigration
    {
        $chain     = $this->get_chain();
        $migration = new $chain[ count( $chain ) - 1 ]();

        for ( $i = count( $chain ) - 2; $i === 0; $i-- ) {
            $migration = new $chain[ $i ]( $migration );
        }

        return $migration;
    }


    public function migration_needed() : bool
    {
        $chain            = $this->get_chain();
        $latest_migration = $chain[ count( $chain ) - 1 ];

        return $this->get_current_version() < $latest_migration::VERSION;
    }


    protected function get_chain() : array
    {
        /**
         * It is important that the migrations are sorted from oldest to youngest.
         * Otherwise complications during the migration could occur.
         */
        $this->chain = [
            DB_Version_1::class,
        ];

        return $this->chain;
    }


    public function get_current_version() : int
    {
        if ( empty( $this->current_version ) ) {
            $this->current_version = (int) get_option( BaseMigration::OPTION_NAME, 0 );
        }

        return $this->current_version;
    }
}
