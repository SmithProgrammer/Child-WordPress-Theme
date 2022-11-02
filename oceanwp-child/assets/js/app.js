import { single_project_page__slider } from "./single-project-page__slider.js";
import { single_project_page_form } from "./single-project-page-form.js";

export function ec_init () {
	single_project_page__slider();
	single_project_page_form();
}

document.addEventListener('DOMContentLoaded', function(){ 
	ec_init();
});