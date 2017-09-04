<?php

namespace BookmarkManager\Tests\PostTypes;

use BookmarkManager\PostTypes\Bookmarks;

class BookmarksTests extends \WP_UnitTestCase
{

    /**
     * @var Bookmarks
     */
    protected $bookmarks;

    public function setUp()
    {
        $this->bookmarks = new Bookmarks();
    }

    public function test_check_name()
    {
        $this->assertEquals( 'bookmark', $this->bookmarks->get_name() );
    }


    public function test_check_slug()
    {
        $this->assertEquals( 'bookmarks', $this->bookmarks->get_slug() );
    }
}
