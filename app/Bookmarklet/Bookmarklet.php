<?php

namespace BookmarkManager\Bookmarklet;

use BookmarkManager\BookmarkManager;

class Bookmarklet
{

    protected static $version = 1;


    public function __construct()
    {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    }


    /**
     * Enqueue these scripts and styles only in the bookmarklet.
     *
     * @param $hook_suffix page we are on
     */
    public function enqueue_scripts( $hook_suffix )
    {
        wp_localize_script( 'bookmark-manager-bookmarklet', 'bookmarkManager', array(
            'rest_endpoint' => esc_url_raw( rest_url() ),
            'rest_nonce' => wp_create_nonce( 'wp_rest' ),
            'user_id' => get_current_user_id(),
            'version' => self::$version,
            'i18n' => [
                    'bookmarkSaved' => __( 'The bookmark has been successfully saved.', 'bookmark-manager' ),
                'error' => __( 'An error occured. Please try again.', 'bookmark-manager' ),
            ]
        ) );

        if ( 'bookmark-this.php' !== $hook_suffix ) {
            return;
        }

        wp_enqueue_style( 'bookmark-manager-bookmarklet-style' );
        wp_enqueue_script( 'bookmark-manager-bookmarklet' );
    }


    /**
     * Return bookmarklet version.
     *
     * @return int
     */
    public static function version()
    {
        return self::$version;
    }


    /**
     * Do some checks and render HTML for bookmarklet.
     *
     * Firstly, we check if the user is logged in, if not the login screen
     * is rendered. If user is logged in the capabilities are checked to see
     * if user has the right to add new bookmarks. If that is also good the
     * actual form is rendered to save bookmarks.
     */
    public function html()
    {

        // Render Login Screen if user is not logged in
        $this->login();

        // Check capabilities
        $this->capability_check();

        // Render actual bookmark save form
        $this->render_form();

    }


    private function login()
    {
        // If no user is logged in display login form
        if ( 0 == get_current_user_id() ) {
            echo $this->render_login();
            die;
        }
    }


    private function render_login()
    { ?>
<!DOCTYPE html>
<html>
<head>
    <?php
    $base            = get_home_url();
    $login_css_url   = $base . '/wp-admin/css/login.css';
    $l10n_css_url    = $base . '/wp-admin/css/l10n.css';
    $buttons_css_url = $base . '/wp-includes/css/buttons.css';

    ?>

    <link rel='stylesheet' id='tags-css' href='<?php echo $login_css_url; ?>' type='text/css' media='all'/>
    <link rel='stylesheet' id='tags-css' href='<?php echo $l10n_css_url; ?>' type='text/css' media='all'/>
    <link rel='stylesheet' id='tags-css' href='<?php echo $buttons_css_url; ?>' type='text/css' media='all'/>

</head>
<body class="login login-action-login wp-core-ui  locale-de-de">
<div id="login">
    <?php wp_login_form(); ?>
</div>
</body>
</html>
    <?php }


    private function capability_check()
    {
        if ( ! current_user_can( 'edit_posts' ) || ! current_user_can( get_post_type_object( 'post' )->cap->create_posts ) ) {
            wp_die( '<h1>' . __( 'Cheatin&#8217; uh?' ) . '</h1>' . '<p>' . __( 'Sorry, you are not allowed to create bookmarks as this user.' ) . '</p>',
                403 );
        }
    }


    private function render_form()
    { ?>
<!DOCTYPE html>
<html>
<head>
    <?php

    /** This action is documented in wp-admin/admin-header.php */
    do_action( 'admin_enqueue_scripts', 'bookmark-this.php' );

    /** This action is documented in wp-admin/admin-header.php */
    do_action( 'admin_print_styles' );

    /** This action is documented in wp-admin/admin-header.php */
    do_action( 'admin_print_scripts' );

    /** This action is documented in wp-admin/admin-header.php */
    do_action( 'admin_head' );
    ?>
</head>
<body>

<div id="app">

    <p v-if="updateBookmarklet">
        <?php echo sprintf( __( "You're using an old bookmarklet. Grab the new one on <a href='%s' target='_blank' titel='Settings'>Settings</a>.", 'bookmark-manager' ), admin_url( 'edit.php?post_type=bookmarks&page=crbn-settings.php' ) ); ?>
    </p>

    <p id="flash" :class="flash" v-if="notice">{{ notice }}</p>

    <label for="title"><?php _e( 'Title', 'bookmark-manager' ); ?></label>
    <input type="text" name="title" v-model="title">

    <label for="url"><?php _e( 'URL', 'bookmark-manager' ); ?></label>
    <input type="text" name="url" v-model="url">

    <label for="description"><?php _e( 'Description', 'bookmark-manger' ); ?></label>
    <textarea name="description" id="description" cols="30" rows="10" v-model="description"></textarea>

    <label for="tags"><?php _e( 'Tags', 'bookmark-manager' ); ?></label>
    <input type="text" name="tags" v-model="tags">


    <label for="private"><?php _e( 'Private', 'bookmark-manager' ); ?></label>
    <input type="checkbox" name="private" v-model="private">

    <button v-on:click="saveBookmark" :disabled="notice !== ''"><?php _e( 'Save Bookmark', 'bookmark-manager' ); ?></button>
</div>

<?php
/** This action is documented in wp-admin/admin-footer.php */
do_action( 'admin_footer' );

/** This action is documented in wp-admin/admin-footer.php */
do_action( 'admin_print_footer_scripts' );
?>
</body>
</html><?php
        die();
    }
}
