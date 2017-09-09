<?php

namespace BookmarkManager\Migrations;

/**
 * Class DB_Version_1_Tests
 *
 * @since   0.2.0
 *
 * @package BookmarkManager\Migrations
 */
class DB_Version_1_Tests extends \WP_UnitTestCase
{

    /**
     * Test migration 1, if it converts all 'bookmarks' to 'bookmark' post types.
     */
    public function test_migration()
    {
        // Check if DB is empty.
        $bookmarks = get_posts( [
            'post_type'   => 'bookmarks',
            'post_status' => 'publish,private,draft'
        ] );
        $this->assertEquals( [], $bookmarks,
            "There should be no 'bookmarks' posts in database, yet." );

        // Create 10 'bookmarks' posts.
        $expected = $this->factory->post->create_many( 10, [ 'post_type' => 'bookmarks' ] );

        // Check if no 'bookmark' posts are in DB.
        $bookmark = get_posts( [
            'post_type'      => 'bookmark',
            'post_status'    => 'publish,private,draft',
            'posts_per_page' => 15,
        ] );
        $this->assertEquals( [], $bookmark,
            "There should be no 'bookmark' posts in database, yet." );

        $migration = new DB_Version_1();
        $migration->migrate( 0 );

        // All 'bookmarks' posts should be gone
        $bookmarks = get_posts( [
            'post_type'   => 'bookmarks',
            'post_status' => 'publish,private,draft'
        ] );
        $this->assertEquals( [], $bookmarks,
            "All 'bookmarks' posts should be gone and migrated by migration." );

        $bookmarks = get_posts( [
            'post_type'      => 'bookmark',
            'post_status'    => 'publish,private,draft',
            'posts_per_page' => 15,
        ] );
        $this->assertEquals( count( $expected ), count( $bookmarks ),
            "There should be the the same number of 'bookmark' posts as we created earlier as 'bookmarks'." );
    }

    /**
     * Test if migration sets DB version.
     */
    public function test_if_migration_updates_version()
    {
        delete_option( BaseMigration::OPTION_NAME );

        $migration = new DB_Version_1();
        $migration->migrate( 0 );

        $db_version = get_option( BaseMigration::OPTION_NAME );

        $this->assertEquals( DB_Version_1::VERSION, $db_version );
    }
}
