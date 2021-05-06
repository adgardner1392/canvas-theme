<?php
/**
 * Add Advanced Custom Fields option pages & load fields
 *
 * @author Adam Gardner <hello@adgardner.co.uk>
 * @since  1.0
 */

new Canvas_ACF;
class Canvas_ACF {

    // A value only array of field groups we don't want to auto-load for some reason, pop the name of the file
    // here (without the extension), e.g. four-col-blogs to exclude the four-col-blogs.json fields and four-col-blogs.php
    CONST EXCLUDE_BLOCKS = array();

    /**
     * Called on class initialisation
     */
    function __construct() {

        /* Add the WordPress actions to register ACF pages */
        add_action( 'acf/init', array( $this, 'add_options_pages' ) );
        /* Add a category to the Gutenberg editor for our custom blocks to appear under */
        add_filter( 'block_categories', array( $this, 'gutenberg_custom_block_category' ), 10, 2 );
        /* Programmatically load all Gutenberh block field groups from the blocks folders */
        add_action( 'acf/init', array( $this, 'load_gutenberg_groups' ) );
        /* And then load all render templates for where the blocks are used */
        add_action( 'acf/init', array( $this, 'load_gutenberg_blocks' ) );
        /* Legacy function to allow JSON files to be loaded for fixed Classic Editor templates */
        add_action( 'acf/init', array( $this, 'load_json_groups' ) );
    }

    /**
     * Register new category for all of our custom blocks to sit under
     * @param array $categories The original array of Gutenberg categories
     * @param object $post The current WordPress post being viewed
     * @return array $categories The modifieed array of Gutenberg categories
     */
    function gutenberg_custom_block_category( $categories, $post ) {

        // Return the new array, with the original categories merged with our new
        // category - Lightbox Digital Blocks
        return array_merge( $categories, array(
            array(
                'slug'  => 'lightbox-digital',
                'title' => __( 'Lightbox Digital Blocks', 'lightbox' ),
            )
        ));
    }

    /**
     * Register a global options page - for any fields which need to show site-wide
     * @return null
     */
    function add_options_pages() {
        acf_add_options_sub_page( array(
            'title'         => 'Edit Global Content',
            'parent'        => 'edit.php?post_type=page',
            'capability'    => 'manage_options',
        ) );
    }

    /**
     * Load ACF field groups for each Gutenberg block from JSON files within
     * the templates/blocks/BLOCK-NAME/ folder path
     * @return null
     */
    function load_gutenberg_groups() {

        // Set the parth of the initial directory we want to loop over
        $fields_path = get_template_directory() . '/templates/blocks/';

        // Create a recursive iterator over the iterator, that is, loop through the top level folders,
        // and for each folder found, then loop through any child folders
        $folders = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $fields_path ) );

        // For each folder (subfolder) found
        foreach ( $folders as $file ) {

            // If this is a file and not another folder, which includes . (current dir) and .. (parent dir) paths, proceed
            if ( ! $file->isDir() && $file->isFile() ) {

                // If the file is JSON, assume this is a field file
                if ( 'json' === $file->getExtension() ) {

                    // If the file isn't in the global ignore list, proceed
                    if ( ! in_array( $file->getBasename( '.json' ), Canvas_ACF::EXCLUDE_BLOCKS ) ) {

                        // Get the files contents and decode them (true means load as array)
                        $array = json_decode( file_get_contents( $file->getPathname() ), true );

                        // Register this field group
                        register_field_group( $array );

                    }
                }
            }
        }
    }

    /**
     * Register and render ACF blocks in Gutenberg from the
     * /templates/render/BLOCK=NAME.php file
     * @return null
     */
    function load_gutenberg_blocks() {

        // Set the folder we want to loop through each file of - our render folder
        $fields_path = get_template_directory() . '/templates/render/';

        // Create a new directory iterator to Programmatically loop through
        $files = new DirectoryIterator( $fields_path );

        // For each file found, proceed
        foreach ( $files as $file ) {

            // Just to be safe, check this isn't another folder
            if ( ! $file->isDir() && $file->isFile() ) {

                // If it's a PHP file, assume that it's our render file
                if ( 'php' === $file->getExtension() ) {

                    // Include the render file to call the contents of the file
                    include_once $fields_path . $file->getBasename();
                }

            }
        }
    }


}
