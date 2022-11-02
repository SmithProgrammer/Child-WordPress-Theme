export function single_project_page__slider () {

    let images_main_block = document.querySelector('.images_main-block');
    let imgs = images_main_block.querySelectorAll('.ec-post-feed-gallery-img');
    let current = 0;

    let col_1__all_images = document.querySelector('.col-1__all-images');
    let all_imgs = col_1__all_images.querySelectorAll('.ec-post-feed-gallery-img');
    let html = document.querySelector('html');


    // for popup
        let single_project__popup_image = document.querySelector('.single-project__popup-image');
        let popup_image__close_icon = document.querySelector('.popup-image__close-icon');
        let popup_image__main_img = document.querySelector('.popup-image__main-img');

        let arrow_left = document.querySelector('.slider__arrow-left');
        let arrow_right = document.querySelector('.slider__arrow-right');

        let popup_open = false;

        for (let i = 0; i < imgs.length; i++) {
            
           imgs[i].addEventListener('click',()=>{ 
               if (!popup_open) {
                   single_project__popup_image.style.display = "flex";
                   html.style.overflow = 'hidden';

                   let url_string = imgs[current].style.backgroundImage;
                   url_string = url_string.slice(5, -2);
                   popup_image__main_img.innerHTML = '<img class="ec-post-feed-gallery-img" src="'+url_string+'">';

                   let popup_img = single_project__popup_image.querySelector('.ec-post-feed-gallery-img');
                   popup_img.className += ' popup-image__thumbnail ';
                   popup_open = true;
               } 
           }); 
        }

        // close 
            popup_image__close_icon.addEventListener('click',()=>{
                if (popup_open) {
                    single_project__popup_image.style.display = "none";
                    html.style.overflow = 'scroll';
                    popup_open = false;
                } 
            });

            //if the menu is open and click on any part of the document
            single_project__popup_image.addEventListener('click', e => {  

                let self = e.target;  

                if ( popup_open && self.className === single_project__popup_image.className ) {

                    single_project__popup_image.style.display = 'none';
                    html.style.overflow = 'scroll';
                    popup_open = false;
                }    
            });

        // if one img, arrow none
            if (imgs.length == 1) {
                arrow_left.style.display = "none";
                arrow_right.style.display = "none";
            }

        arrow_left.addEventListener('click', ()=>{
            
            imgs[current].style.display = 'none';
            all_imgs[current].className = ' ec-post-feed-gallery-img ';

            current = current-1; 
            
            if (current < 0) {
                current = (imgs.length-1); 
                imgs[current].style.display = 'block';
                all_imgs[current].className += ' ec-post-feed-gallery-img-active ';
            } else {
                imgs[current].style.display = 'block';
                all_imgs[current].className += ' ec-post-feed-gallery-img-active ';
            } 

            let url_string = imgs[current].style.backgroundImage;
            url_string = url_string.slice(5, -2);
            popup_image__main_img.innerHTML = '<img class="ec-post-feed-gallery-img" src="'+url_string+'">';

            let popup_img = single_project__popup_image.querySelector('.ec-post-feed-gallery-img');
            popup_img.className += ' popup-image__thumbnail ';
            
        });

        arrow_right.addEventListener('click', ()=>{
            
            imgs[current].style.display = 'none';
            all_imgs[current].className = ' ec-post-feed-gallery-img ';
    
            current = current+1; 
    
            if (current > (imgs.length-1)) {
                current = 0;
                imgs[current].style.display = 'block';
                all_imgs[current].className += ' ec-post-feed-gallery-img-active ';
            } else {
                imgs[current].style.display = 'block';
                all_imgs[current].className += ' ec-post-feed-gallery-img-active ';
            } 

            let url_string = imgs[current].style.backgroundImage;
            url_string = url_string.slice(5, -2);
            popup_image__main_img.innerHTML = '<img class="ec-post-feed-gallery-img" src="'+url_string+'">';

            let popup_img = single_project__popup_image.querySelector('.ec-post-feed-gallery-img');
            popup_img.className += ' popup-image__thumbnail ';
            
        });

    // for all imgs 

        for (let i = 0; i < all_imgs.length; i++) {
            all_imgs[i].addEventListener('click',(e)=>{
                imgs[current].style.display = 'none';
                all_imgs[current].className = ' ec-post-feed-gallery-img ';

                current = i; 

                imgs[current].style.display = 'block';
                all_imgs[current].className += ' ec-post-feed-gallery-img-active ';
            });
          }
}