<div class="mobile-menu" id="mobilemenu">

    <div class="mobile-menu__logo-wrap">

        <a class="mobile-menu__logo-link" href="<?php echo site_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
            <img src="<?php echo get_theme_mod( 'mobile-logo' ); ?>" alt="mobile-logo" width="150" class="mobile-menu__logo mobile-menu__logo--<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
        </a>

        <a class="mobile-menu__close" data-link="#mobilemenu" href="<?php echo site_url(); ?>">
            <?php echo get_svg('close', array(
                'role'  => 'presentation',
                'title' => __( 'Close Icon', 'canvas' ),
                'class' => 'mobile-menu__icon',
            )); ?>
        </a>

    </div>

    <div class="mobile-menu__container">

        <ul class="mobile-menu__menu">

            <?php
            wp_nav_menu(array(
                'container'         => '',
                'theme_location'    => 'mobile-nav',
                'walker'            => new BemNavWalker,
                'items_wrap'        => '%3$s',
                'bem_block'         => 'mobile-menu',
            ));
            ?>

        </ul>

    </div>
</div>
