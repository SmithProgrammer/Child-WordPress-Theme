export function single_project_page_form () {
	let form__button = document.querySelector('.form__button');

    let form__input_name = document.querySelector('.form__input[name="name"]');
    let form__input_phone = document.querySelector('.form__input[name=phone]');
    let form__textarea_message = document.querySelector('.form__textarea[name=message]');

    let data = new FormData();

    form__button.addEventListener('click',()=>{

        data.append('name', form__input_name.value );
        data.append('phone', form__input_phone.value);
        data.append('message', form__textarea_message.value);
        data.append('action', 'single_page_project_form');

        fetch('/wp-admin/admin-ajax.php', { 
          method: 'POST', 
          mode: 'cors',
          body: data
        }).then(response => { return response.text(); })
        .then(posts_res => {
			form__button.insertAdjacentHTML('afterend', posts_res);
		})
        .catch(error => { console.log(error); }) 
    });
}