<?php
/**
 * Class SampleTest
 *
 * @package Bookmark_Manager
 */

/**
 * Sample test case.
 */
class SampleTests extends WP_UnitTestCase
{

    /**
     * A single example test.
     */
    function test_sample()
    {
        // Replace this with some actual testing code.
        $this->assertTrue( true );
    }


    function test_sample_string()
    {

        $string = 'Unit tests are sweet';

        $this->assertEquals( 'Unit tests are sweet', $string );
        $this->assertNotEquals( 'Unit tests suck', $string );
    }


    function test_sample_number()
    {

        $p = $this->factory->post->create( array( 'post_title' => 'Test Post' ) );

        $query = new WP_Query( array(
            's' => 'Test Post'
        ) );

        $posts = $query->query( $query );

        $this->assertEqualSets( array( $p ), wp_list_pluck( $posts, 'ID' ) );

    }
}
