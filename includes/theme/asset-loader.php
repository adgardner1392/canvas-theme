<?php
/**
 * Load the relevant assets
 *
 * @author Adam Gardner <hello@adgardner.co.uk>
 * @since  1.0
 */

new AssetLoader;
class AssetLoader {
    /**
     * Called on class initialisation
     */
    function __construct() {
        /* Setup wp global for removing query vars */
        global $wp;

        /* Add the WordPress action to enqueue the scripts */
        add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

        /* Load only certain scripts in the CMS editor when using Gutenberg */
        add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );

        /* Add conditional tags around scripts that require them */
        add_filter( 'script_loader_tag', array( $this, 'conditional_scripts' ), 10, 2 );

        /* Add color meta, compatibility and viewport tags for browsers */
        add_action( 'wp_head', array( $this, 'add_head_color_tags' ) );
        add_action( 'wp_head', array( $this, 'compatibility_tags' ) );
        add_action( 'wp_head', array( $this, 'add_viewport_tag' ) );

        /* Load the spritesheet for front-end display */
        add_action( 'wp_footer', array( $this, 'include_spritesheet' ) );

        /* Add our function which allows for site-wide uploading of SVG's to the media library */
        add_filter( 'upload_mimes', array( $this, 'allow_svg_uploads' ) );

        /* Remove emoji actions introduced in WordPress 4.2 */
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

        /* Remove jQuery migrate code from queue */
        add_filter( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );

        /* Remove the embed query var */
        $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
            'embed',
        ) );

        /* Remove wp_oembed actions and filters introduced in WordPress 4.4 */
        remove_action( 'rest_api_init', 'wp_oembed_register_route' );
        add_filter( 'embed_oembed_discover', '__return_false' );
        remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    }

    /**
     * Enqueue all of our frontend assets
     * @return null
     */
    public function load_assets() {
        // Use location variable to avoid unnecessary function calls
        $theme = get_template_directory_uri();

        // Get current gulp-generated cache buster value (Version number)
        $ver = file_get_contents( get_template_directory() . '/assets/dist/cache-buster' );

        /**
         * External stylesheets/fonts (Google Fonts, TypeKit etc)
         */
        wp_enqueue_style( 'roboto', '//fonts.googleapis.com/css2?family=Roboto:ital,wght@400,700&display=swap' );

        /**
         * Load the compiled and minified components stylesheet
         * (See gulpfile.babel.js 'packageStyles' task)
         */
        wp_enqueue_style( 'components', $theme . '/assets/dist/css/components.min.css', false, $ver );

        /**
         * Load the compiled and minified stylesheet
         * (See gulpfile.babel.js 'styles' task)
         */
        wp_enqueue_style( 'theme', $theme . '/assets/dist/css/theme.min.css', false, $ver );

        /**
         * Load WordPress' jQuery version
         */
        wp_enqueue_script( 'jquery', null, false, false, true );

        /**
         * Load minified and concatenated Node Modules file (See gulpfile.babel.js 'packageScripts' task)
         */
        wp_enqueue_script( 'components', $theme . '/assets/dist/js/components.min.js', false, $ver, true );

        /**
         * Load minified and concatenated script file (See gulpfile.babel.js 'scripts' task)
         */
        wp_enqueue_script( 'theme', $theme . '/assets/dist/js/theme.min.js', false, $ver, true );

        // Add the theme site URL to available Jquery arguments in case this is needed (e.g. WP AJAX call URLS)
        $theme = array( 'siteurl' => get_option( 'siteurl' ) );
        wp_localize_script( 'theme', 'LD', $theme );
    }

    /**
     * Selectively add conditional tags around assets
     * @param string $tag Full <link> or <script> tag
     * @param string $handle The handle/slug passed to enqueue function
     * @return string Modified $tag
     */
    public function conditional_scripts( $tag, $handle ) {
        // Add lower than ie9 wrapper around html5shiv
        if ( 'html5shiv' == $handle ) {
            $tag = '<!--[if lt IE 9]>' . $tag . '<![endif]-->';
        }

        return $tag;
    }

    /**
     * Add tags required for best compatibility
     */
    public function compatibility_tags() {
        echo '<meta charset="utf-8">';
    }

    /**
     * Add meta viewport tag to <head> for effective responsive
     */
    public function add_viewport_tag() {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />';
    }

    /**
    * Remove jQuery migrate scripts from queue
    * @param Object $scripts Scripts queue object
    * @return null
    */
    public function remove_jquery_migrate( &$scripts) {
        // If this is not the admin area, remove the migrate files.
        if ( ! is_admin() ) {
            $scripts->remove( 'jquery' );
            $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
        }
    }

    /**
    * Allow SVG's to be uploaded to the WordPress media library,
    * used for adding the logo to the site through the customiser
    */
    public function allow_svg_uploads( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    public function block_editor_assets() {

        // Use location variable to avoid unnecessary function calls
        $theme = get_template_directory_uri();

        // Get current gulp-generated cache buster value (Version number)
        $ver = file_get_contents( get_template_directory() . '/assets/dist/cache-buster' );

        // Fetch our theme styles to display in Gutebnerg and enqueue these into the header
        wp_enqueue_style( 'admin-components', $theme . '/assets/dist/css/components.min.css', false, $ver );
        wp_enqueue_style( 'admin-theme', $theme . '/assets/dist/css/gutenberg.min.css', false, $ver );

        // Now enqueue the scripts to load in the CMS - Note these are also loaded in the wp_head as wp_footer
        // is never loaded in Gutenberg
        wp_enqueue_script( 'admin-components', $theme . '/assets/dist/js/components.min.js', false, $ver );
        wp_enqueue_script( 'admin-theme', $theme . '/assets/dist/js/theme.min.js', false, $ver );

        /**
         * External stylesheets/fonts (Google Fonts, TypeKit etc)
         */
        wp_enqueue_style( 'roboto', '//fonts.googleapis.com/css2?family=Roboto:ital,wght@400,700&display=swap' );

        // Trigger the inclusion of the Spritesheet in the header for the block-editor view
        $this->include_spritesheet();
    }

    /**
     * Action function to include the SVG spritesheet
     * @return null
     */
    public function include_spritesheet() {
        echo '<div class="svg-sprites" style="display: none;">';
        include_once( get_template_directory() . '/assets/dist/spritesheet.svg' );
        echo '</div>';
    }
}
