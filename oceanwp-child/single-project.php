<?php

get_header();

if (have_posts()) : 

    while (have_posts()) : 

        the_post();

        $thumbnail = get_the_post_thumbnail_url($post->ID);
        ?>

		<style type="text/css">
			#footer-widgets {
				display: none;
			}
            .page-header{
                display: none;
            }
		</style>

        <div class="projects-page__main-banner" <? if ($thumbnail) { ?> style="background:url('<?=$thumbnail;?>') center top/cover; background-attachment:fixed;" <? } ?>>
           <h1><?=$post->post_title;?></h1>
        </div>

        <div class="page__single-project">

            <div class="single-project__popup-image">
                <div class="popup-image__main-img"></div> 

                <div class="slider__arrow-left">
                    <img src="<?=get_stylesheet_directory_uri()?>/assets/img/arrow-left.svg">
                </div>
                <img class="popup-image__close-icon" src="<?=get_stylesheet_directory_uri()?>/assets/img/close-popup.svg">
                <div class="slider__arrow-right">
                    <img src="<?=get_stylesheet_directory_uri()?>/assets/img/arrow-right.svg">
                </div>
            </div>

            <div class="single-project__col-1">
                <div class="col-1__images">
                    <div class="images_main-block">
                        <?=get_content_post_feed_gallery();?>                        
                    </div>

                    <div class="col-1__all-images">
                        <?=get_content_post_feed_gallery();?>
                    </div>
                </div>


                <div class="single-project__orange-line"><div></div></div>

                <div class="col-1__content"><?=the_content();?></div>

                <div class="single-project__orange-line"><div></div></div>
            </div>

            <div class="single-project__col-2">
                <h1><?=$post->post_title;?></h1>
                <div class="single-project__orange-line"><div></div></div>

                <div class="col-2__post-meta">
                    <div class="post-meta__meta_item">
                        <img src="<?=get_stylesheet_directory_uri()?>/assets/img/blueprint.svg">
                        <p><?=get_post_meta($post->ID, 'project-meters', true);?></p>
                    </div>

                    <div class="post-meta__meta_item">
                        <img src="<?=get_stylesheet_directory_uri()?>/assets/img/Price.png">
                        <p><?=get_post_meta($post->ID, 'project-price', true);?></p>
                    </div>
                </div>

                <div class="single-project__orange-line"><div></div></div>

                <?=get_post_meta($post->ID, 'project-info', true);?>

                <? 
                    $crb_img_of_quality = carbon_get_the_post_meta( 'img_of_quality' );
                    $src = wp_get_attachment_image_url($crb_img_of_quality, 'full');
                ?>

                <img src="<?=$src?>">

                <div class="col-2__form">
                    <h2>ЗАЛИШИТИ ЗАЯВКУ</h2>

                    <input class="form__input" type="text" name="name" placeholder="Iм'я" required="required">
                    <input class="form__input" type="tel" name="phone" placeholder="Телефон" required="required">
                    
                    <textarea name="message" class="form__textarea" placeholder="Повiдомлення" required="required"></textarea>

                    <div class="form__button">ВІДПРАВИТИ</div>
                </div>
            </div>
        </div> 

        <?
    endwhile;

endif;

get_footer('blue');

get_footer();