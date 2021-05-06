<?php
/**
 * Add theme settings to the customiser
 *
 * @author Adam Gardner <hello@adgardner.co.uk>
 * @since  1.0
 */

new Canvas_Customizer;
class Canvas_Customizer {

    /**
     * Called on class initialisation
     */
    function __construct() {

        /* Add the WordPress actions to register customizer components */
        add_action( 'customize_register', array( $this, 'site_logo' ) );
    }

    /**
     * Register site logo field in customizer
     * @return null
     */
    public function site_logo( $customizer ) {
        // Add our setting
        $customizer->add_setting( 'site-logo' );

        // Add control for our logo option
        $customizer->add_control(
            new WP_Customize_Image_Control(
                $customizer, 'site-logo', array(
                    'label'          => __( 'Site Logo', 'canvas' ),
                    'description'    => __( 'This is your main logo which exists in the header. Recommended 300px wide.', 'canvas' ),
                    'section'        => 'title_tagline',
                    'settings'       => 'site-logo',
                )
            )
        );

        // Add our setting
        $customizer->add_setting( 'mobile-logo' );

        // Add control for our logo option
        $customizer->add_control(
            new WP_Customize_Image_Control(
                $customizer, 'mobile-logo', array(
                    'label'          => __( 'Mobile Menu Logo', 'canvas' ),
                    'description'    => __( 'This logo is displaid at the top of the mobile navigation menu.', 'canvas' ),
                    'section'        => 'title_tagline',
                    'settings'       => 'mobile-logo',
                )
            )
        );
    }

}
