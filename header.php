<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>

        <?php do_action( 'ld_opening_body' ); ?>

        <header>

            <div class="header" data-aos="fade-up" data-aos-delay="100" data-aos-duration="400">
                <div class="container header__container">

                        <div class="header__col header__col--left">

                            <a class="header__logo-link" href="<?php echo site_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                                <img src="<?php echo get_theme_mod( 'site-logo' ); ?>" alt="site-logo" width="195" class="header__logo">
                            </a>

                        </div>

                        <div class="header__col header__col--right">

                            <nav class="header__navigation">

                                <ul class="header__menu">
                                    <?php
                                    wp_nav_menu(array(
                                        'container'         => '',
                                        'theme_location'    => 'main-nav',
                                        'walker'            => new BemNavWalker,
                                        'items_wrap'        => '%3$s',
                                        'bem_block'         => 'header',
                                        'before_ul'         => '<div class="header__submenu-wrap">',
                                        'after_ul'          => '</div>',
                                    ));
                                    ?>
                                </ul>

                            </nav>

                            <a class="header__menu-link" href="<?php echo site_url(); ?>" data-link="#mobilemenu">
                                <?php echo get_svg( 'bars', array(
                                    'role'  => 'presentation',
                                    'title' => __( 'Menu Bars Icon', 'canvas' ),
                                    'class' => 'header__bars'
                                )); ?>
                            </a>

                        </div>

                </div>
            </div>

        </header>

        <main class="main-content">
