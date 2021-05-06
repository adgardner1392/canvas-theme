<div class="hero-image-1<?php echo $additional_classes ? ' ' . $additional_classes : null; echo $image_overalay ? ' hero-image-1__overlay--' . $image_overlay : null; echo $content_colour ? ' hero-image-1--' . $content_colour : null; ?>">
    <div class="container hero-image-1__container">
        <div class="hero-image-1__wrap hero-image-1__wrap--left" data-aos="fade-right" data-aos-delay="300" data-aos-duration="700">
            <?php if ( $heading ) : ?>
                <span class="hero-image-1__icon hero-image-1__icon--heading">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.6704 16.3714L27.9898 13.0521L29.6495 11.3924L21.3511 3.09402L19.6914 4.75371L16.3721 8.07306L13.0527 4.75371L11.393 3.09403L3.09462 11.3924L4.7543 13.0521L8.07365 16.3715L4.7543 19.6908L3.09464 21.3505L11.393 29.6489L13.0527 27.9892L16.372 24.6699L19.6914 27.9892L21.3511 29.6489L29.6495 21.3505L27.9898 19.6908L24.6704 16.3714Z" stroke="white" stroke-width="3"/>
                    </svg>
                </span>
                <<?php echo $heading_type; ?> class="hero-image-1__heading">
                    <?php echo strip_tags( $heading, '<br>,</br>,<strong>,</strong>' ); ?>
                </<?php echo $heading_type; ?>>
            <?php endif; ?>
            <?php if ( $content ) : ?>
                <div class="hero-image-1__content hero-image-1__content--main">
                    <?php echo $content; ?>
                </div>
            <?php endif; ?>
            <div class="hero-image-1__content">
            <?php if ( $event_date && $event_date_icon ) :?>
                <div class="hero-image-1__date">
                    <span class="hero-image-1__icon">
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M23.5697 22.7188C23.5697 23.2122 23.1684 23.6145 22.6745 23.6145H3.06419C2.57027 23.6145 2.16896 23.2122 2.16896 22.7188V5.84695H23.5697V22.7188ZM7.56022 2.34422C8.10841 2.34422 8.55304 2.78935 8.55304 3.33753C8.55304 3.88622 8.10841 4.33035 7.56022 4.33035C7.01154 4.33035 6.56691 3.88622 6.56691 3.33753C6.56691 2.78935 7.01154 2.34422 7.56022 2.34422ZM18.2317 2.35219C18.7799 2.35219 19.2245 2.79681 19.2245 3.3455C19.2245 3.89369 18.7799 4.33831 18.2317 4.33831C17.683 4.33831 17.2384 3.89369 17.2384 3.3455C17.2384 2.79681 17.683 2.35219 18.2317 2.35219ZM22.6745 0.5H3.06419C1.65015 0.5 0.5 1.65065 0.5 3.06468V22.7188C0.5 24.1328 1.65015 25.2835 3.06419 25.2835H22.6745C24.0885 25.2835 25.2392 24.1328 25.2392 22.7188V3.06468C25.2392 1.65065 24.0885 0.5 22.6745 0.5Z" fill="#FEFEFE"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12.8733 20.2687C15.3824 20.2687 17.7467 19.2393 17.7467 16.8429C17.7467 15.701 17.039 14.8164 15.9453 14.4143C16.7977 14.0443 17.4893 13.2241 17.4893 12.1304C17.4893 9.87869 15.3502 8.83325 12.8733 8.83325C10.3965 8.83325 8.25734 9.87869 8.25734 12.1304C8.25734 13.2241 8.93285 14.0443 9.78528 14.4143C8.6916 14.8164 8 15.701 8 16.8429C8 19.2393 10.3643 20.2687 12.8733 20.2687ZM12.8733 13.208C12.3426 13.208 12.0692 12.9185 12.0692 12.4521C12.0692 11.9856 12.3426 11.6961 12.8733 11.6961C13.4041 11.6961 13.6775 11.9856 13.6775 12.4521C13.6775 12.9185 13.4041 13.208 12.8733 13.208ZM11.9083 16.4569C11.9083 17.0359 12.3426 17.3254 12.8733 17.3254C13.4041 17.3254 13.8383 17.0359 13.8383 16.4569C13.8383 15.8618 13.4041 15.6045 12.8733 15.6045C12.3426 15.6045 11.9083 15.8618 11.9083 16.4569Z" fill="white"/>
                        </svg>
                    </span>
                    <?php echo date('jS F Y', strtotime( $event_date ));?>
                </div>
            <?php endif ;?>
            <?php if ( $event_location ) :?>
                <div class="hero-image-1__location">
                    <span class="hero-image-1__icon">
                        <svg width="18" height="26" viewBox="0 0 18 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.32109 11.7218C7.31401 11.7218 5.6855 10.0944 5.6855 8.08733C5.6855 6.07912 7.31401 4.45061 9.32109 4.45061C11.3304 4.45061 12.9578 6.07912 12.9578 8.08733C12.9578 10.0944 11.3304 11.7218 9.32109 11.7218ZM17.9767 9.15331C17.9767 4.37411 14.1003 0.5 9.32222 0.5C4.54301 0.5 0.666656 4.37411 0.666656 9.15331C0.666656 10.7306 1.09755 12.2061 1.83221 13.4803H1.82602L9.31884 25.2477L16.8162 13.4803H16.8077C17.5469 12.2061 17.9767 10.7306 17.9767 9.15331Z" fill="white"/>
                        </svg>
                    </span>
                    <?php echo strip_tags( $event_location, '<br>,</br>,<strong>,</strong>' ); ?>
                </div>
            <?php endif ;?>
            </div>
        </div>
        <div class="hero-image-1__wrap hero-image-1__wrap--right" data-aos="fade-left" data-aos-delay="500" data-aos-duration="700">
            <div class="hero-image-1__content hero-image-1__content--showcase">
                <div class="heading"><?php echo $showcase_title ;?></div>
                <div class="pricing">
                    <p>From</p>
                    <p class="price"><span>&pound;</span><?php echo $price ;?></p>
                    <p>(Excl. VAT)</p>
                    <?php if ( $link_url && $link_label ):?>
                        <a href="<?php echo $link_url;?>" class="btn btn--white"><?php echo $link_label ;?></a>
                    <?php endif ;?>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-image-1__img" <?php if ( $bg_image ) { echo bg_image( $bg_image, $image_size ); } ?>></div>
</div>
