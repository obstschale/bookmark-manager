<?php

namespace BookmarkManager\Migrations;

use BookmarkManager\Service;

/**
 * MigrationService builds a chain of migrations and runs them.
 *
 * The migration service run before other bookmark manager features are registered.
 * It builds a 'Chain of Responsibility' of migrations and runs migrations if a DB
 * update is necessary. That means, if the 'bookmark_manager_db_version' option is
 * behind the last migration version.
 *
 * Every migrations is build, but decides itself if the update procedure should run.
 * So all migrations in the chain are called but only needed updates are performed.
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\Migrations
 */
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


    /**
     * MigrationService constructor.
     *
     * Call get_chain() to populate the $chain property and fetch current
     * DB version to check later if migrations need to run.
     */
    public function __construct()
    {
        $this->get_chain();
        $this->get_current_version();
    }


    /**
     * Register the current Registerable.
     *
     * @since 0.2.0
     *
     * @return void
     */
    public function register() : void
    {
        add_action( 'init', [ $this, 'migrate' ], 5 );
    }


    /**
     * Migration method kicks off the chain of migrations.
     *
     * Firstly, check if any migration is needed. If not no chain is build.
     * Because the build of process creates new object, which we can avoid.
     *
     * If a migration is needed the chain is built up and the first migration
     * is called with the current DB version. If the migration is finished it
     * will call the next migration until no migration is left.
     *
     * @since 0.2.0
     */
    public function migrate() : void
    {
        if ( $this->migration_needed() ) {
            $migration = $this->build_chain();

            $version = $this->get_current_version();
            $migration->migrate( $version );
        }
    }


    /**
     * Build the chain of migrations and return the first element of that chain.
     *
     * A new object of each migration (element) is instantiated and passed as successor
     * to the previous migration. So the chain is built backwards to start with the first
     * and end with the last migration.
     *
     * @since 0.2.0
     *
     * @return BaseMigration First migration, which is the beginning of the chain.
     */
    public function build_chain() : BaseMigration
    {
        $chain     = $this->get_chain();
        $migration = new $chain[ count( $chain ) - 1 ]();

        for ( $i = count( $chain ) - 2; $i === 0; $i-- ) {
            $migration = new $chain[ $i ]( $migration );
        }

        return $migration;
    }


    /**
     * Check the DB version to know if a new migration is available.
     *
     * @since 0.2.0
     *
     * @return bool Whether a migration needs to run.
     */
    public function migration_needed() : bool
    {
        $chain            = $this->get_chain();
        $latest_migration = $chain[ count( $chain ) - 1 ];

        return $this->get_current_version() < $latest_migration::VERSION;
    }


    /**
     * Return the chain array, which holds all migration class references.
     *
     * The migration classes must be sorted from oldest to newest. It is the best
     * to simply name migrations according their DB version. So the sorting is more
     * obvious.
     *
     * @since 0.2.0
     *
     * @return array Array with all migration classes.
     */
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


    /**
     * Get the current DB version, which is saved in DB.
     *
     * If no DB version is yet set, the default value of 0 is returned. That also means
     * the first migration will run and set the version to 1.
     *
     * @since 0.2.0
     *
     * @return int DB Version saved in DB
     */
    public function get_current_version() : int
    {
        if ( empty( $this->current_version ) ) {
            $this->current_version = (int) get_option( BaseMigration::OPTION_NAME, 0 );
        }

        return $this->current_version;
    }
}
