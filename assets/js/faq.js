import '../css/faq.sass';
import $ from 'jquery';
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
//Routing.generate('rep_log_list');

$(document).ajaxStart(function(){
    $("#preloader").show();
    
});
$(document).ajaxComplete(function(){
    $("#preloader").hide();
});

$("#question-sf-form").submit((e)=>{
	e.preventDefault();
	let url = Routing.generate('lc_faq');
	let questionForm = $("#question-sf-form");
	$(this).hide()
	let formSerialize = questionForm.serialize();
	let email = questionForm.find('#question_form_askedBy_email').val();
	
	
	$.post(url, formSerialize, function(response){
		if(response){
			$("#question-sf-form").hide(500);
			$("#question-success").append(" " + email + ".");
			$("#question-success").show(600);
		}
	})
})



