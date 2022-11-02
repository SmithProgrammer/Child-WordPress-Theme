<?php 

$crb_adresses = carbon_get_theme_option( 'footer_bg_img' );
$src = wp_get_attachment_image_url($crb_adresses, 'full');

?>

<div class="blue-footer"  <? if ($src) { ?> style="background:url('<?=$src;?>') no-repeat center center/cover; background-attachment:fixed;" <? } ?>>

        <div class="blue-footer__block blue-footer__adresses">
            <h2>АДРЕСИ</h2>
            <div class="blue-footer__orange-line"></div>

            <?

            $crb_adresses = carbon_get_theme_option( 'crb_adresses' );

            if ( $crb_adresses ) {

                foreach ( $crb_adresses as $adres ) { ?>
                    <div class="blue-footer__text">
                        <p><?=$adres['text'];?></p>
                    </div>
                    <div class="blue-footer__orange-line"></div>
                <? } 
            }

            ?>


        </div>

        <div class="blue-footer__block blue-footer__contacts">
            <h2>КОНТАКТИ</h2>
            <div class="blue-footer__orange-line"></div>

            <?

            $crb_contacts = carbon_get_theme_option( 'crb_contacts' );

            if ( $crb_contacts ) {

                foreach ( $crb_contacts as $c ) { ?>
                    <div class="blue-footer__text">
                        <h3>Тел:</h3><a href="tel:<?=$c['text'];?>"><?=$c['text'];?></a>
                    </div>
                <? } 
            }


            $crb_post_adress = carbon_get_theme_option( 'crb_post_adress' );

            if ( $crb_post_adress ) {

                foreach ( $crb_post_adress as $c ) { ?>
                    <div class="blue-footer__text">
                        <h3>Пошта:</h3><a href="mail:<?=$c['text'];?>"><?=$c['text'];?></a>
                    </div>
                <? } 
            }

            ?>
            
        </div>

        <div class="blue-footer__block blue-footer__pages">
            <h2>СТОРІНКИ</h2>
            <div class="blue-footer__orange-line"></div>

            <div class="pages__nav">
                <? wp_nav_menu( [
                    'menu_location'   => 'nav_footer',
                    'menu'            => '',
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'menu_class'      => '',
                    'menu_id'         => '',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'before'          => '',
                    'after'           => '',
                    'link_before'     => '',
                    'link_after'      => '',
                    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'           => 0,
                    'walker'          => '',
                ] ); ?>
            </div>
            
        </div>

        <div class="blue-footer__block blue-footer__social-links">
            <h2>МЕРЕЖА</h2>
            <div class="blue-footer__orange-line"></div>
            
            <div class="social-links__links">
                <?

                $crb_social_links = carbon_get_theme_option( 'crb_social_links' );

                if ( $crb_social_links ) {

                    foreach ( $crb_social_links as $l ) { 
                        $src = wp_get_attachment_image_url($l['photo_link']);
                        ?>
                        <a class="links__link" href="<?=$l['text'];?>"><img src="<?=$src;?>"></a>
                    <? } 
                }

                ?> 
            </div>
        </div>  
</div>