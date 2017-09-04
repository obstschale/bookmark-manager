<?php

namespace BookmarkManager\Taxonomies;


class BookmarkTagsTests extends \WP_UnitTestCase
{
    /**
     * @var BookmarkTags
     */
    protected $bookmark_tags;

    public function setUp()
    {
        $this->bookmark_tags = new BookmarkTags();
    }

    public function test_check_name()
    {
        $this->assertEquals( 'bookmark_manager_tag', $this->bookmark_tags->get_name() );
    }


    public function test_check_slug()
    {
        $this->assertEquals( 'bookmark-tags', $this->bookmark_tags->get_slug() );
    }
}
