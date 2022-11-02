<?php 

// scripts 
    
    add_action( 'wp_enqueue_scripts', 'ec_scripts' );
    function ec_scripts() {
        wp_enqueue_style( 'style-css-child', get_stylesheet_uri() );
        wp_enqueue_script( 'script-main-child', get_stylesheet_directory_uri() . '/assets/js/app.js', array(), '1.0.0', true );
    }

        // script connection using attribute type module
        function set_scripts_type_attribute( $tag, $handle, $src ) {
            if ( 'script-main-child' === $handle ) {
                $tag = '<script type="module" src="'. $src .'"></script>';
            }
            return $tag;
        }
        add_filter( 'script_loader_tag', 'set_scripts_type_attribute', 10, 3 );

// register post type 

        add_action( 'init', 'register_post_types_az' );

        function register_post_types_az(){

            register_post_type( 'project', [
                'label'  => null,
                'labels' => [
                    'name'               => 'Project', // основное название для типа записи
                    'singular_name'      => 'project', // название для одной записи этого типа
                    'add_new'            => 'Добавить Project', // для добавления новой записи
                    'add_new_item'       => 'Добавление Project', // заголовка у вновь создаваемой записи в админ-панели.
                    'edit_item'          => 'Редактирование Project', // для редактирования типа записи
                    'new_item'           => 'Новое Project', // текст новой записи
                    'view_item'          => 'Смотреть Project', // для просмотра записи этого типа.
                    'search_items'       => 'Искать Project', // для поиска по этим типам записи
                    'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
                    'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
                    'parent_item_colon'  => '', // для родителей (у древовидных типов)
                    'menu_name'          => 'Project', // название меню
                ],
                'description'         => '',
                'public'              => true,
                'show_in_menu'        => null, // показывать ли в меню адмнки
                'show_in_rest'        => null, // добавить в REST API. C WP 4.7
                'rest_base'           => 'project', // $post_type. C WP 4.7
                'menu_position'       => null,
                'menu_icon'           => null,
                'hierarchical'        => false,
                'supports'            => [ 'title', 'editor', 'thumbnail' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
                'taxonomies'          => [],
                'has_archive'         => true,
                'rewrite'             => true,
                'query_var'           => true,
            ] );
        }

// setup 

        if ( ! function_exists( 'az_setup' ) ) :
            function az_setup() {
                /*
                 * Enable support for Post Thumbnails on posts and pages.
                 *
                 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
                 */
                add_theme_support('post-thumbnails', ['post', 'page', 'project']);
                add_theme_support( 'post-formats', array( 'post' ) ); // add the ability to upload a thumbnail for post type pages
            }
        endif;
        add_action( 'after_setup_theme', 'az_setup' );


// add meta boxes for project_price


        add_action( 'add_meta_boxes', 'az_add_metabox_project_price' );
         
        function az_add_metabox_project_price() {
         
            add_meta_box(
                'az_add_metabox_project_price', // ID нашего метабокса
                'Price', // заголовок
                'az_metabox_project_price_content', // функция, которая будет выводить поля в мета боксе
                'project', // типы постов, для которых его подключим
                'normal', // расположение (normal, side, advanced)
                'default' // приоритет (default, low, high, core)
            );
        }
         
        function az_metabox_project_price_content( $post ) { 

                // одноразовые числа, кстати тут нет супер-большой необходимости их использовать
                wp_nonce_field( 'project_pricesettingsupdate-' . $post->ID, '_truenonce' );
        ?>
                <p>Price:</p>
                <? $val = get_post_meta( $post->ID, 'project-price', true ); ?>
                <input type="text" name="project-price" value="<?=$val;?>">

                <p>Meters:</p>
                <? $val = get_post_meta( $post->ID, 'project-meters', true ); ?>
                <input type="text" name="project-meters" value="<?=$val;?>">

                <p>Info:</p>
                <? $val = get_post_meta( $post->ID, 'project-info', true ); ?>
                <textarea name="project-info"><?=$val;?></textarea>

        <? }


        // save metaboxes 

        add_action( 'save_post', 'az_project_price_metabox', 10, 2 );
         
        function az_project_price_metabox( $post_id, $post ) { 


            // проверка одноразовых полей
            if ( ! isset( $_POST[ '_truenonce' ] ) || ! wp_verify_nonce( $_POST[ '_truenonce' ], 'project_pricesettingsupdate-' . $post->ID ) ) {
                return $post_id;
            }
         
            // проверяем, может ли текущий юзер редактировать пост
            $post_type = get_post_type_object( $post->post_type );
         
            if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
                return $post_id;
            }
         
            // ничего не делаем для автосохранений
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return $post_id;
            }
         
            // проверяем тип записи
            if( 'project' !== $post->post_type ) {
                return $post_id;
            }

            // for price
                if( isset( $_POST[ 'project-price' ] ) ) {
                    update_post_meta( $post_id, 'project-price', sanitize_text_field( $_POST[ 'project-price' ] ) );
                } else {
                    delete_post_meta( $post_id, 'project-price' );
                }

            // for meters
                if( isset( $_POST[ 'project-meters' ] ) ) {
                    update_post_meta( $post_id, 'project-meters', sanitize_text_field( $_POST[ 'project-meters' ] ) );
                } else {
                    delete_post_meta( $post_id, 'project-meters' );
                }

            // for info
                if( isset( $_POST[ 'project-info' ] ) ) {
                    update_post_meta( $post_id, 'project-info', sanitize_text_field( $_POST[ 'project-info' ] ) );
                } else {
                    delete_post_meta( $post_id, 'project-info' );
                }

                return $post_id;
        }


// single page project, form 
        
        add_action( 'wp_ajax_single_page_project_form', 'single_page_project_form' );
        add_action( 'wp_ajax_nopriv_single_page_project_form', 'single_page_project_form' );
        function single_page_project_form() {

            $response = array(
                'error' => false,
            );

          $to_mail = 'info@kebud.com.ua';
          $subject = 'Request'; 
           
          $name = $_POST['name'];
          $phone = $_POST['phone'];
          $msg = $_POST['message'];
          
          $message = 'Name: ' . $name . '<br> Phone: ' . $phone . '<br> Message: ' . $msg ;
          
          $headers =  'MIME-Version: 1.0' . "\r\n";
          $headers .= 'From: Your name <info@address.com>' . "\r\n";
          $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
          mail($to_mail, $subject, $message , $headers);
          wp_die('<p style="color:#fff;">Дякуємо за ваше повiдомлення, воно було надiслане!</p>');
        }

// single page project images 
        
        /*
          This file connects the theme with the Multiple Post Thumbnails plugin
        */

        #make sure plugin has been connected
        if ( class_exists('MultiPostThumbnails') ) 
        {

            new MultiPostThumbnails(array(
                'label' => 'Secondary Image',
                'id' => 'secondary-image',
                'post_type' => 'project',
            ) );
             
            new MultiPostThumbnails(array(
                'label' => 'Third Image',
                'id' => 'third-image',
                'post_type' => 'project',
            ) );

            new MultiPostThumbnails(array(
                'label' => 'Fourth Image',
                'id' => 'fourth-image',
                'post_type' => 'project',
            ) );

            new MultiPostThumbnails(array(
                'label' => 'Fifth Image',
                'id' => 'fifth-image',
                'post_type' => 'project',
            ) );

            new MultiPostThumbnails(array(
                'label' => 'Sixth Image',
                'id' => 'sixth-image',
                'post_type' => 'project',
            ) );

            new MultiPostThumbnails(array(
                'label' => 'Seventh Image',
                'id' => 'seventh-image',
                'post_type' => 'project',
            ) );

            new MultiPostThumbnails(array(
                'label' => 'Eighth Image',
                'id' => 'eighth-image',
                'post_type' => 'project',
            ) );

            new MultiPostThumbnails(array(
                'label' => 'Nineth Image',
                'id' => 'nineth-image',
                'post_type' => 'project',
            ) );

            new MultiPostThumbnails(array(
                'label' => 'tenth Image',
                'id' => 'tenth-image',
                'post_type' => 'project',
            ) );
        }

        function get_content_post_feed_gallery () {

            global $post;

            $res = "<div class='ec-post-feed-gallery'>"; 

                    if ( get_the_post_thumbnail_url( $post->ID ) ) { 

                        $url_image = get_the_post_thumbnail_url( $post->ID );

                        $res .= "
                            <div 
                                style='background:url(".$url_image.") center top/cover'
                                loading='lazy'
                                alt='".wp_get_attachment_caption( $post->ID )."'
                                class='ec-post-feed-gallery-img img-for-popup'
                                data-img-id='".$post->ID."'
                            ></div>
                        "; 
                    }

                    if (class_exists('MultiPostThumbnails')) {
                            
                        $secondary_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                            'project', 
                            'secondary-image'
                        );
                        if ($secondary_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style='background:url(".$secondary_image_thumbnail_url.") center top/cover'
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($secondary_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($secondary_image_thumbnail_url)."'
                                ></div>
                            ";
                        }

                        $third_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'third-image'
                        );
                        if ($third_image_thumbnail_url) {

                            $res .= "
                               <div
                                    style=' background:url(".$third_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($third_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($third_image_thumbnail_url)."'
                                ></div>
                            ";
                        }
                    
                        $fourth_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'fourth-image'
                        );
                        if ($fourth_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style=' background:url(".$fourth_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($fourth_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($fourth_image_thumbnail_url)."'
                                ></div>
                            ";
                        }

                        $fifth_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'fifth-image'
                        );
                        if ($fifth_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style=' background:url(".$fifth_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($fifth_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($fifth_image_thumbnail_url)."'
                                ></div>
                            ";
                        }
                        
                        $sixth_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'sixth-image'
                        );
                        if ($sixth_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style=' background:url(".$sixth_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($sixth_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($sixth_image_thumbnail_url)."'
                                ></div>
                            ";
                        }
                        
                        $seventh_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'seventh-image'
                        );
                        if ($seventh_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style=' background:url(".$seventh_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($seventh_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($seventh_image_thumbnail_url)."'
                                ></div>
                            ";
                        }

                        $eighth_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'eighth-image'
                        );
                        if ($eighth_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style=' background:url(".$eighth_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($eighth_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($eighth_image_thumbnail_url)."'
                                ></div>
                            ";
                        }

                        $nineth_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'nineth-image'
                        );
                        if ($nineth_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style=' background:url(".$nineth_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($nineth_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($nineth_image_thumbnail_url)."'
                                ></div>
                            ";
                        }

                        $tenth_image_thumbnail_url = MultiPostThumbnails::get_post_thumbnail_url(
                                'project', 
                                'tenth-image'
                        );
                        if ($tenth_image_thumbnail_url) {

                            $res .= "
                                <div
                                    style=' background:url(".$tenth_image_thumbnail_url.") center top/cover '
                                    loading='lazy'
                                    alt='".wp_get_attachment_caption( attachment_url_to_postid($tenth_image_thumbnail_url) )."'
                                    class='ec-post-feed-gallery-img img-for-popup'
                                    data-img-id='".attachment_url_to_postid($tenth_image_thumbnail_url)."'
                                ></div>
                            ";
                        }
                    
                    } 

            $res .= "</div>";

            return $res;
        }

// carbon fields 


    use Carbon_Fields\Container;
    use Carbon_Fields\Field;

    add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
    function crb_attach_theme_options() { 

        Container::make( 'theme_options', 'Site settings' )
            ->add_tab( __('Footer'), array(
                Field::make( 'image', 'footer_bg_img', __( 'Image background' ) ),

                Field::make( 'complex', 'crb_adresses', __( 'Adresses' ) )
                    ->add_fields( array(
                        Field::make( 'text', 'text', __( 'Adress text' ) ),
                    ) ),

                Field::make( 'complex', 'crb_contacts', __( 'Contacts' ) )
                    ->add_fields( array(
                        Field::make( 'text', 'text', __( 'Phone (Without spaces)' ) ),
                    ) ),

                Field::make( 'complex', 'crb_post_adress', __( 'Post adreses' ) )
                    ->add_fields( array(
                        Field::make( 'text', 'text', __( 'Email text' ) ),
                    ) ),

                Field::make( 'complex', 'crb_social_links', __( 'Social links' ) )
                    ->add_fields( array(
                        Field::make( 'text', 'text', __( 'Link' ) ),
                        Field::make( 'image', 'photo_link', __( 'Link icon' ) ),
                    ) )
            ));

        Container::make( 'post_meta', __( 'Project', 'crb' ) )
            ->where( 'post_type', '=', 'project' ) // only show our new fields on pages
            ->add_fields( array(
                Field::make( 'image', 'img_of_quality', 'Image of quality' ),
            ) );
    }

// register nav 
    register_nav_menus(array(
        'nav_footer' => 'Nav footer',
    ));

