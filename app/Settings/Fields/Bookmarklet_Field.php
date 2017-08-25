<?php

namespace Carbon_Fields\Field;

use BookmarkManager\BookmarkManager;
use BookmarkManager\Bookmarklet\Bookmarklet;

class Bookmarklet_Field extends Field
{

    /*
     * Properties
     */
    protected $example_property = 'hi';


    /**
     * to_json()
     *
     * You can use this method to modify the field properties that are added to the JSON object.
     * The JSON object is used by the Backbone Model and the Underscore template.
     *
     * @param bool $load Should the value be loaded from the database or use the value from the current instance.
     *
     * @return array
     */
    public function to_json( $load )
    {
        $field_data = parent::to_json( $load ); // do not delete

        $field_data = array_merge( $field_data, [
            'example_property' => $this->example_property,
        ] );

        return $field_data;
    }


    /**
     * template()
     *
     * Prints the main Underscore template
     **/
    public function template()
    {
        ?>

        <p id="bookmarkthis-bookmarklet-description"><?php _e( "Drag the bookmarklet below to your bookmarks bar. Then, when you're on a page you want to bookmark, simply &#8220;bookmark&#8221; this",
                'bookmark-manager' ); ?></p>

        <p class="pressthis-bookmarklet-wrapper">

            <a class="pressthis-bookmarklet" onclick="return false;"
               href='<?php echo htmlspecialchars( $this->bookmarklet_code() ); ?>'>
                <span>
                    <?php _e( 'Bookmark This', 'bookmark-manager' ); ?>
                </span>
            </a>

            <button type="button" class="button pressthis-js-toggle js-show-pressthis-code-wrap" aria-expanded="false"
                    aria-controls="pressthis-code-wrap">
                <span class="dashicons dashicons-clipboard"></span>
                <span class="screen-reader-text">
                    <?php _e( 'Copy &#8220;Bookmark This&#8221; bookmarklet code', 'bookmark-manager' ) ?>
                </span>
            </button>
        </p>

        <div class="hidden js-pressthis-code-wrap clear" id="pressthis-code-wrap" style="display: none;">
            <p id="pressthis-code-desc">
                <?php _e( 'If you can&#8217;t drag the bookmarklet to your bookmarks, copy the following code and create a new bookmark. Paste the code into the new bookmark&#8217;s URL field.' ) ?>
            </p>
            <p>
                <textarea class="js-pressthis-code" rows="5" cols="120" readonly="readonly"
                          aria-labelledby="pressthis-code-desc"><?php echo htmlspecialchars( $this->bookmarklet_code() ); ?>
                </textarea>
            </p>
        </div>

        <script>

          jQuery(document).ready(function ($) {
            var $showPressThisWrap = $('.js-show-pressthis-code-wrap');
            var $pressthisCode = $('.js-pressthis-code');

            $showPressThisWrap.on('click', function (event) {
              var $this = $(this);

              $this.parent().next('.js-pressthis-code-wrap').slideToggle(200);
              $this.attr('aria-expanded', $this.attr('aria-expanded') === 'false' ? 'true' : 'false');
            });

            // Select Press This code when focusing (tabbing) or clicking the textarea.
            $pressthisCode.on('click focus', function () {
              var self = this;
              setTimeout(function () {
                self.select();
              }, 50);
            });

          });

        </script>
        <?php
    }


    public function bookmarklet_code()
    {
        $src = @file_get_contents( BookmarkManager::plugin_path() . 'public/js/bookmark-this.js' );
        //$src = @file_get_contents( BookmarkManager::plugin_path() . 'resources/js/bookmarklet/bookmark-this.min.js' );

        // WordPress base url can differ from plugins URL. We have to transmit it
        // to be able to call AJAX or the REST API
        $base = wp_json_encode( get_home_path() );

        if ( $src ) {
            $url  = wp_json_encode( BookmarkManager::plugin_url() . 'app/Bookmarklet/BookmarkThis.php?v=' . Bookmarklet::version() );
            $src = str_replace( 'window.bt_base', $base, $src );
            $link = 'javascript:void' . str_replace( 'window.bt_url', $url, $src );
        }

        $link = str_replace( [ "\r", "\n", "\t" ], '', $link );

        /**
         * Filters the Press This bookmarklet link.
         *
         * @since 1.0.0
         *
         * @param string $link The Bookmark This bookmarklet link.
         */
        return apply_filters( 'bookmark_this_link', $link );
    }
}
