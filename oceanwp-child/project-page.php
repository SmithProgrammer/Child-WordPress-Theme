<?php

/**
 * The template for displaying projects-page
 * Template Name: projects-page
 * Template Post Type: page
 *
 * @package az
 */

get_header(); 

$thumbnail = get_the_post_thumbnail_url($post->ID);
?>

        <style type="text/css">
            #footer-widgets {
                display: none;
            }
        </style>

<div class="projects-page">
	<div class="projects-page__main-banner" <? if ($thumbnail) { ?> style="background:url('<?=$thumbnail;?>') center top/cover;" <? } ?>>
           <h1><?=$post->post_title;?></h1>
    </div>
     <div class="projects-page__wrapper">
        <?
            $query = array(
                 'post_type' => 'project',
                 'posts_per_page' => 20,
            );

            $q = new WP_Query( $query );
         
            if( $q->have_posts() ) {
         
                while( $q->have_posts() ) {

                    $q->the_post();
                    $thumbnail = get_the_post_thumbnail_url($post->ID);
                    ?>
                        <a  href="<?=get_post_permalink( $post->ID );?>">
                            <div  class="project-post" >
                                <img class="project-post__image" src="<?=$thumbnail;?>">
                                
                                <div class="project-post__meta-data">
                                    <div class="meta-data__col-1">
                                        <h2><?=get_the_title();?></h2>
                                        <p>|</p>
                                        <div class="meta-data__meters"><?=get_post_meta($post->ID, 'project-meters', true);?></div>
                                    </div>

                                    <div class="meta-data__price">
                                        <p><?=get_post_meta($post->ID, 'project-price', true);?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?
                }

            } else {
                ?><div>No projects</div><?
            } 
        ?>
    </div>
</div>

<? 

get_footer('blue');

get_footer();