<?php

namespace BookmarkManager\Migrations;

class BaseMigrationTests extends \PHPUnit_Framework_Test
{

    /**
     * @var BaseMigration
     */
    protected $stub;


    public function setUp()
    {
        parent::setUp();

        $this->stub = $this->getMockForAbstractClass( BaseMigration::class );
    }


    public function test_that_migration_is_newer_than_current_version()
    {
        $this->stub->set_current_version( 0 );
        $this->stub->expects( $this->any() )->method( 'get_version' )->willReturn( 1 );

        $this->assertTrue( $this->stub->is_new() );
    }


    public function test_that_migration_is_not_newer_than_current_version()
    {
        $this->stub->set_current_version( 2 );
        $this->stub->expects( $this->any() )->method( 'get_version' )->willReturn( 1 );

        $this->assertFalse( $this->stub->is_new() );
    }


    public function test_bumping_version_number()
    {
        // Delete option first, because phpunit loads the plugin,
        // which runs the migration and sets the version number.
        delete_option( BaseMigration::OPTION_NAME );

        $db_version = get_option( BaseMigration::OPTION_NAME );
        $this->assertFalse( $db_version );

        $this->stub->bump_db_version( 1 );
        $db_version = get_option( BaseMigration::OPTION_NAME );
        $this->assertEquals( 1, $db_version );
    }

    public function test_no_migration_runs()
    {
        $this->stub->expects( $this->any() )->method( 'get_version' )->willReturn( 1 );
        $this->stub->set_current_version( 1 );
        $this->assertEquals( 1, $this->stub->get_current_version() );

        $this->stub->migrate( 1 );
        $this->assertEquals( 1, $this->stub->get_current_version() );
    }
}
