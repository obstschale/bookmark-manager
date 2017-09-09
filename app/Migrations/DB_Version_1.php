<?php

namespace BookmarkManager\Migrations;

class DB_Version_1 extends BaseMigration
{

    const VERSION = 1;


    public function update_database()
    {
        var_dump( 'Migration 01' );
        $this->bump_db_version( self::VERSION );
    }


    public function get_version() : int
    {
        return self::VERSION;
    }
}
