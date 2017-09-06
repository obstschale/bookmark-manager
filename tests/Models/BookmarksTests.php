<?php

namespace BookmarkManager\Models;

/**
 * Class BookmarksTests
 *
 * @package BookmarkManager\Models
 */
class BookmarksTests extends \WP_UnitTestCase
{

    /**
     * Add new bookmark to database and retrieve it.
     */
    public function test_add_new_bookmark()
    {
        $bookmarks = get_posts( [
            'post_type'   => 'bookmarks',
            'post_status' => 'publish,private,draft',
        ] );
        $this->assertEquals( [], $bookmarks, 'There should be no bookmark in database, yet.' );

        // Let's create one bookmark
        $bookmark = new Bookmark;
        $bookmark->title( 'Awesome Site' )
            ->content( 'Lorem ipsum dolor sit amet' )
            ->link( 'http://example.com' )
            ->status( 'publish' );

        // save to DB
        $id = $bookmark->save();

        // assert bookmark exists
        $bookmarks = get_posts( [] );
        $this->assertTrue( count( $bookmarks ) > 0 );
        $this->assertEquals( $bookmarks[ 0 ]->ID, $id );
    }


    /**
     * Get a bookmark from database by passing an ID to the class constructor.
     */
    public function test_get_bookmark_from_db_without_link()
    {
        $bookmarks = get_posts( [
            'post_type'   => 'bookmarks',
            'post_status' => 'publish,private,draft',
        ] );
        $this->assertEquals( [], $bookmarks, 'There should be no bookmark in database, yet.' );

        $expected = $this->factory->post->create_and_get( [ 'post_type' => 'bookmarks' ] );

        $bookmark = new Bookmark( $expected->ID );

        $this->assertEquals( $expected->ID, $bookmark->id() );
        $this->assertEquals( $expected->post_title, $bookmark->title() );
        $this->assertEquals( $expected->post_content, $bookmark->content() );
        $this->assertEquals( '', $bookmark->link() );
    }


    /**
     * Get a bookmark from database by passing an ID to the class constructor.
     */
    public function test_get_bookmark_from_db_with_link()
    {
        $bookmarks = get_posts( [
            'post_type'   => 'bookmarks',
            'post_status' => 'publish,private,draft',
        ] );
        $this->assertEquals( [], $bookmarks, 'There should be no bookmark in database, yet.' );

        $expected_link = 'http://example.com';
        $expected      = $this->factory->post->create_and_get( [
            'post_type'  => 'bookmarks',
            'meta_input' => [
                'bookmark_manager_link' => $expected_link
            ]
        ] );

        $bookmark = new Bookmark( $expected->ID );

        $this->assertEquals( $expected->ID, $bookmark->id() );
        $this->assertEquals( $expected->post_title, $bookmark->title() );
        $this->assertEquals( $expected->post_content, $bookmark->content() );
        $this->assertEquals( $expected_link, $bookmark->link() );
    }


    /**
     * Test content property and content() method.
     */
    public function test_content()
    {
        $bookmark = new Bookmark;
        $this->assertEquals( '', $bookmark->content() );

        $bookmark->content = 'New Content';
        $this->assertEquals( 'New Content', $bookmark->content() );

        $bookmark->content( 'Another Content' );
        $this->assertEquals( 'Another Content', $bookmark->content() );

        $sameBookmark = $bookmark->content( 1337 );
        $this->assertEquals( '1337', $bookmark->content() );

        $this->assertEquals( $sameBookmark, $bookmark );
    }


    /**
     * Test link property and link() method.
     */
    public function test_link()
    {
        $bookmark = new Bookmark;
        $this->assertEquals( '', $bookmark->link() );

        $bookmark->link = 'http://example.com';
        $this->assertEquals( 'http://example.com', $bookmark->link() );

        $sameBookmark = $bookmark->link( 'https://example2.com' );
        $this->assertEquals( 'https://example2.com', $bookmark->link() );

        $this->assertEquals( $sameBookmark, $bookmark );
    }


    /**
     * Test link property and id() method.
     */
    public function test_id()
    {
        $bookmark = new Bookmark;
        $this->assertEquals( '', $bookmark->id() );

        $bookmark->id = 123;
        $this->assertEquals( 123, $bookmark->id() );

        $sameBookmark = $bookmark->id( 1337 );
        $this->assertEquals( '1337', $bookmark->id() );

        $this->assertEquals( $sameBookmark, $bookmark );
    }


    /**
     * Test status property and status() method.
     */
    public function test_status()
    {
        $bookmark = new Bookmark;
        $this->assertEquals( 'draft', $bookmark->status() );

        $bookmark->status = 'publish';
        $this->assertEquals( 'publish', $bookmark->status() );

        $bookmark->status( 'Another Title' );
        $this->assertEquals( 'Another Title', $bookmark->status() );

        $sameBookmark = $bookmark->status( 1337 );
        $this->assertEquals( '1337', $bookmark->status() );

        $this->assertEquals( $sameBookmark, $bookmark );
    }


    /**
     * Test title property and title() method.
     */
    public function test_title()
    {
        $bookmark = new Bookmark;
        $this->assertEquals( '', $bookmark->title() );

        $bookmark->title = 'New Title';
        $this->assertEquals( 'New Title', $bookmark->title() );

        $bookmark->title( 'Another Title' );
        $this->assertEquals( 'Another Title', $bookmark->title() );

        $sameBookmark = $bookmark->title( 1337 );
        $this->assertEquals( '1337', $bookmark->title() );

        $this->assertEquals( $sameBookmark, $bookmark );
    }
}
