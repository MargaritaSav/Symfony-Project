import '../css/contact.sass';
import $ from 'jquery';
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);


let ajaxSubmit = require('./ajaxSubmit');

let url = Routing.generate('lc_contact');

$("#contact-sf-form").submit((e)=>{
	e.preventDefault();
	ajaxSubmit("#contact-sf-form", url, "#contact-success", "#client_email");

})