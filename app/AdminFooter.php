<?php

namespace BookmarkManager;

class AdminFooter extends BookmarkManager
{

    function __construct()
    {
        add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ], 11 );
        add_filter( 'update_footer', [ $this, 'update_footer' ], 11 );
    }


    /**
     * Overrides WordPress text in Footer
     *
     * @param $admin_footer_text string
     *
     * @return string
     */
    public function admin_footer_text( $admin_footer_text )
    {
        $screen = get_current_screen();

        if ( $screen->post_type === 'bookmarks' ) {
            $admin_footer_text = sprintf( __( 'Thank you for using Bookmark Manager' ) );

            return $admin_footer_text;
        }

        return $admin_footer_text;
    }


    /**
     * Overrides WordPress Version in Footer
     *
     * @param $update_footer_text string
     *
     * @return string
     */
    public function update_footer( $update_footer_text )
    {
        $screen = get_current_screen();
        $default_text = $update_footer_text;

        if ( $screen->post_type === 'bookmarks' ) {
            $update_footer_text = '<span class="bookmark-manager-update-footer">' . sprintf( __( 'Bookmark Manager Version %s',
                    self::$textdomain ), self::$settings['version'] ) . '</span>';

            return $update_footer_text . ', WordPress ' . $default_text;
        }

        return $update_footer_text;
    }

}
