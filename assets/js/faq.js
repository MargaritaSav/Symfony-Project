import '../css/faq.sass';
import $ from 'jquery';

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';
Routing.setRoutingData(routes);

let ajaxSubmit = require('./ajaxSubmit');
let url = Routing.generate('lc_faq');


$(document).ajaxStart(function(){
    $("#preloader").show();
    
});
$(document).ajaxComplete(function(){
    $("#preloader").hide();
});


$("#question-sf-form").submit((e)=>{
	e.preventDefault();
	ajaxSubmit("#question-sf-form", url, "#question-success", "#question_form_askedBy_email");
})



