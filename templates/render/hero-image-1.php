<?php
if ( function_exists( 'acf_register_block' ) ) {

    acf_register_block( array(
        'name'				=> 'hero-image-1',
        'title'				=> __( 'Hero Image (Full Height)', 'canvas' ),
        'description'		=> __( 'A block used to display a heading, content and a button over a background image. Choose between left and right content alignment.', 'canvas' ),
        'align'             => 'full',
        'render_callback'   => 'hero_image_one',
        'category'			=> 'heros',
        'icon'				=> 'megaphone',
        'keywords'			=> array( 'hero', 'image' ),
        'data'              => placeholder_content(
            array(
                'heading' => __( 'Hero Image (Full Height)', 'canvas' )
            )
        )
    ));
}

function hero_image_one( $block, $content = '', $is_preview = false, $post_id = 0 ) {

    // create id attribute for specific styling
    $block_id = 'hero-image-1--' . $block['id'];

    get_part( 'templates/blocks/hero-image-1/hero-image-1',
        array(
            'block_id'              => $block['id'],
            'additional_classes'    => $block_id,
            'bg_image'              => get_field( 'background_image' ),
            'image_size'            => full,
            'heading_type'          => get_field( 'heading_type' ),
            'heading'               => get_field( 'heading' ),
            'content_icon'          => get_field( 'content_icon' ),
            'content'               => get_field( 'content' ),
            'link_url'              => get_field( 'link_url' ),
            'link_label'            => get_field( 'link_label' ),
            'event_date_icon'       => get_field( 'event_date_icon' ),
            'event_date'            => get_field( 'event_date' ),
            'event_location_icon'   => get_field( 'event_location_icon' ),
            'event_location'        => get_field( 'event_location' ),
            'showcase_title'        => get_field( 'showcase_title' ),
            'price'                 => get_field( 'price' ),
        )
    );
}
?>
