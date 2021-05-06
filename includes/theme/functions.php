<?php

/**
 * Load an item from the spritesheet as an SVG
 * @param string $name ID reference for SVG, typically path/filename
 * @param array $atts Additional HTML attributes for SVG element
 * @param string $method Choice of 'use', 'inline', or 'img'
 * @return string SVG with use reference
 */
function get_svg( $name, $atts = array(), $method = 'use' ) {
    // Default to role of presentation (icon)
    if ( ! isset( $atts['role'] ) ) {
        $atts['role'] = 'presentation';
    }

    // Handle title independently of attribute if method is use
    if ( isset( $atts['title'] ) && 'use' == $method ) {
        $title = $atts['title'];
        unset( $atts['title'] );
    }

    // Build attributes up
    $attributes = '';
    foreach ( $atts as $attr => $value ) {
        if ( ! empty( $value ) ) {
            $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
            $attributes .= ' ' . $attr . '="' . $value . '"';
        }
    }

    // Create output for each different method
    switch ( $method ) {
        case 'inline': {
            // Output the SVG element inline
            $path = get_template_directory() . '/assets/dist/vectors/';
            $output = file_get_contents( $path . $name . '.svg' );
            break;
        }
        case 'img': {
            // Output an image tag
            $path = get_template_directory_uri() . '/assets/dist/vectors/';
            $output = '<img src="' . esc_attr( $path . $name . '.svg' ) . '"' . $attributes . '/>';
            break;
        }
        default: {

            // Alternatively here we could reference the spritesheet as url before #.

            $output = '<svg' . $attributes . '>';
            $output .= ( isset( $title ) ? '<title>' . $title . '</title>' : '' );
            $output .= '<use xlink:href="#' . esc_attr( trim( $name ) ) . '"/>';
            $output .= '</svg>';
            break;
        }
    }

    return $output;
}

/**
 * Load a template and safely pass variables into it
 * @deprecated Use get_part instead.
 * @param string $template Template path without extension
 * @param array $variables Variables to pass into part
 * @return boolean True on success, false on failure
 */
function load_part( $template, $variables = array() ) {
    return get_part( $template, $variables );
}

/**
 * Get a template and safely pass variables into it
 * @param string $template Template path without extension
 * @param array $variables Variables to pass into part
 * @param array|string $cache Cache key as string or key & ttl in array
 * @param boolean $echo Echo output when true, return when false
 * @return boolean True on success, false on failure
 */
function get_part( $template, $variables = array(), $cache = false, $echo = true, $ttl = 0 ) {
    // If variables aren't an array then fail
    if ( ! is_array( $variables ) ) {
        return false;
    }

    // Check cache options
    if ( $cache ) {
        // Handle different $cache formats
        if ( is_string( $cache ) ) {
            $key = $cache;
            $ttl = DAY_IN_SECONDS;
        } elseif ( is_array( $cache ) ) {
            $key = isset( $cache['key'] ) ? $cache['key'] : null;
            $ttl = isset( $cache['ttl'] ) ? $cache['ttl'] : DAY_IN_SECONDS;
            $cache_users = isset( $cache['cache_users'] ) ? $cache['cache_users'] : true;
        }

        if ( ! $key ) {
            throw new Exception( 'Invalid cache arguments supplied to ' . __FUNCTION__ );
        }

        // Prepend the cache key
        $key = apply_filters( 'ld_part_fragment_prefix', 'ld_part_fragment_' ) . $key;

        // If not logged or cache users is set to true attempt cache
        if ( ! is_user_logged_in() || $cache_users ) {
            $output = get_transient( $key );

            // Cache is not empty so let's fetch it.
            if ( ! empty( $output ) ) {
                if ( $echo ) {
                    echo $output;
                    return true;
                } else {
                    return $output;
                }
            }
        }
    } // Cache block for fragment caching. End if().

    // Find the requested template
    $template = locate_template( $template . '.php' );

    // If template couldn't be found then fail
    if ( ! $template ) {
        return false;
    }

    // Store each key value pair in actual variables
    foreach ( $variables as $var => $val ) {
        $$var = $val;
    }

    // Start output buffering
    ob_start();

    // Include the template
    include( $template );

    // Get the contents of the buffer
    $output = ob_get_clean();

    // If caching is enabled ($key is set) then save in cache
    if ( isset( $key ) ) {
        set_transient( $key, $output, $ttl );
    }

    // Echo the output and return true
    if ( $echo ) {
        echo $output;
        return true;
    } else {
        return $output;
    }
}

/**
 * Creates resized background image HTML
 * @param integer $image Image ID
 * @param array $size Size of image resizing parameters
 * @param array $options Options array used to set additional css properties
 * @return string Background image style HTML
 */
function bg_image( $image, $size, $options = array() ) {
    // Return if no ID is found
    if ( ! isset( $image ) || ! $image ) {
        return false;
    }

    // Default options
    $options['size']     = ( isset( $options['size'] ) ? $options['size'] : 'cover' );
    $options['repeat']   = ( isset( $options['repeat'] ) ? $options['repeat'] : 'no-repeat' );
    $options['position'] = ( isset( $options['position'] ) ? $options['position'] : 'center' );

    // Get dynamic image url
    $url = wp_get_attachment_image_url( $image, $size );

    // Output background image style HTML code
    $output = sprintf(
        ' style="background-image: url(\'%1$s\'); background-size: %2$s; background-repeat: %3$s; background-position: %4$s;"',
        $url, $options['size'], $options['repeat'], $options['position']
    );

    return $output;
}

function placeholder_content( $custom_placeholders = array() ) {

    // Define placeholder content for our block Renders, key = ACF field name, value = default value
    $placeholders = array(
        'heading_type'      => 'h2',
        'heading'           => __( 'This is a Heading', 'canvas' ),
        'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        'button_colour'     => 'primary',
        'link_url'          => '/home/',
        'link_label'        => 'Button',
        'ad_button_colour'  => 'secondary',
        'ad_link_url'       => '/home/',
        'ad_link_label'     => 'Button',
        'form_shortcode'    => '[ninja_form id=1]'
    );

    // If we have passed any custom placeholders that need to be used instead or in addition to
    if ( $custom_placeholders && is_array( $custom_placeholders ) ) {
        // Merge these into the returned placeholders array
        $placeholders = array_merge( $placeholders, $custom_placeholders );
    }

    // Return this for use within render functions (could return in one go but this is simply easier to read)
    return $placeholders;

}
