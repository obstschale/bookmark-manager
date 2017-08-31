<?php

namespace BookmarkManager\Models;

class Bookmark
{

    /**
     * Bookmark content.
     *
     * @var string
     */
    public $content;

    /**
     * Bookmark ID.
     *
     * @var int
     */
    public $id;

    /**
     * Bookmark link.
     *
     * @var string
     */
    public $link;

    /**
     * Bookmark status (aka post_status).
     *
     * @var string
     */
    public $status = 'draft';

    /**
     * Bookmark title.
     *
     * @var string
     */
    public $title;


    public function __construct( int $id = null )
    {
        if ( ! is_null( $id ) ) {
            $data = get_post( $id );
            $meta = get_post_meta( $id );

            $this->id( $id );
            $this->title( $data->post_title );
            $this->content( $data->post_content );
            $this->link = $meta[ 'bookmark_manager_link' ][0] ?? '';
        }
    }


    /**
     * Save this bookmark to database.
     *
     * @TODO Escape and validate fields before saving.
     *
     * @return int|\WP_Error
     */
    public function save()
    {
        return wp_insert_post( [
            'post_title'   => $this->title(),
            'post_content' => $this->content(),
            'meta_input'   => [
                'bookmark_manager_link' => esc_url_raw( $this->link() ),
            ],
            'post_status'  => $this->status(),
        ] );
    }


    /**
     * Getter and Setter method for content property.
     *
     * @param string|null $content Content of bookmark
     *
     * @return string|$this
     */
    public function content( $content = null )
    {
        if ( is_null( $content ) ) {
            return $this->content;
        }

        $this->content = $content;

        return $this;
    }


    /**
     * Getter and Setter method for link property.
     *
     * @param string|null $link Link of bookmark
     *
     * @return string|$this
     */
    public function link( $link = null )
    {
        if ( is_null( $link ) ) {
            return $this->link;
        }

        $this->link = $link;

        return $this;
    }

    /**
     * Getter and Setter method for id property.
     *
     * @param string|null $id ID of bookmark
     *
     * @return string|$this
     */
    public function id( $id = null )
    {
        if ( is_null( $id ) ) {
            return $this->id;
        }

        $this->id = $id;

        return $this;
    }


    /**
     * Getter and Setter method for status property.
     *
     * @param string|null $status Status of bookmark
     *
     * @return string|$this
     */
    public function status( $status = null )
    {
        if ( is_null( $status ) ) {
            return $this->status;
        }

        $this->status = $status;

        return $this;
    }


    /**
     * Getter and Setter method for title property.
     *
     * @param string|null $title Title of bookmark
     *
     * @return string|$this
     */
    public function title( $title = null )
    {
        if ( is_null( $title ) ) {
            return $this->title;
        }

        $this->title = $title;

        return $this;
    }

}
