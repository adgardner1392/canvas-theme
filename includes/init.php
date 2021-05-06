<?php
/**
 * Load all required includes.
 *
 * @author Adam Gardner <hello@adgardner.co.uk>
 * @since  1.0
 */

/**
 * The base directory for the include files
 * @var string
 */
$include_dir = trailingslashit( get_template_directory() ) . 'includes/';

/**
 * Composer includes
 */
require trailingslashit( get_template_directory() ) . 'vendor/autoload.php';

/**
 * Helper Includes
 */
require_once $include_dir . 'helpers/bem-nav-walker.php';

/**
 * Theme Specific Includes
 */
require_once $include_dir . 'theme/asset-loader.php';
require_once $include_dir . 'theme/customizer.php';

// Only load the ACF helper if plugin is active
if ( class_exists( 'acf' ) ) {
    include_once $include_dir . 'theme/acf.php';
}

// Load any custom functions
require_once $include_dir . 'theme/functions.php';
