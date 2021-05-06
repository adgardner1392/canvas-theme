<?php
/**
 * The default WordPress master functions file.
 *
 * @author Adam Gardner <hello@adgardner.co.uk>
 * @since  1.0
 */

/**
 * Load the master include file
 */
require_once trailingslashit( get_template_directory() ) . 'includes/init.php';

/**
 * Init hook for registering of post types etc
 */
new Canvas_ThemeFunctions;
class Canvas_ThemeFunctions {

    function __construct() {
        // Load theme text domain
        load_theme_textdomain( 'canvas' );

        // Post types & taxonomies hook
        add_action( 'init', array( $this, 'post_types_taxonomies' ) );

        // Menu hooks
        add_action( 'init', array( $this, 'register_menus' ) );

        // Theme support hook
        add_action( 'init', array( $this, 'theme_support' ) );

        // Remove standard WordPress blocks from Gutenberg
        add_filter( 'allowed_block_types', array( $this, 'remove_default_blocks' ) );
    }

    /**
     * Hook to register navigation menu positions
     * @return null
     */
    public function register_menus() {
        // Register the nav menus
        register_nav_menus(
            array(
                'main-nav'        => __( 'Main Header Navigation', 'canvas' ),
                'mobile-nav'      => __( 'Mobile Header Navigation', 'canvas' )
            )
        );
    }

    /**
     * Remove all core blocks from the Gutenberg editor view
     * @param array $allowed_blocks The array of all blocks
     * @return array $filtered_blocks The filtered list of blocks to show in the CMS
     */
    public function remove_default_blocks( $allowed_blocks ) {

        // Get all registered Gutenberg blocks to filter through
        $registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

        // This array will hold the blocks we want to keep/display
        $filtered_blocks = array();

        // Loop through each block
        foreach ( $registered_blocks as $block ) {

            // If the pattern does not match core/ (the default WordPress blocks)
            if ( strpos( $block->name , 'core/' ) === false ) {

                // Add this to the array of acceptable blocks
                array_push( $filtered_blocks, $block->name  );
            }
        }

        // Return the accepted blocks to display in the CMS!
        return $filtered_blocks;
    }
}
