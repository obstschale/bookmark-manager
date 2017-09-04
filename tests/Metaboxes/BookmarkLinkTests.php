<?php

namespace BookmarkManager\Metaboxes;

use BookmarkManager\Metaboxes\BookmarkLink;

class BookmarkLinkTests extends \WP_UnitTestCase
{
    /**
     * @var BookmarkLink
     */
    protected $meta_field;

    public function setUp()
    {
        $this->meta_field = new BookmarkLink();
    }

    public function test_id_name()
    {
        $this->assertEquals( 'bookmark_manager_link', $this->meta_field->get_id() );
    }

}
