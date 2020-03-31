/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import '../css/app.sass';


import $ from 'jquery';
import 'bootstrap';
import 'mmenu-js';
import 'mburger-css';

$(document).ready(function() {
    $('#elem-1').hover((e) => {
        e.preventDefault()
        $('#elem-1 ul').toggle(500);
    });

    $('#elem-2').hover(() => {

        $('#elem-2 ul').toggle(500);
    });

    let btn = $('#button-up');

    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });

    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, '300');
    });

    $('#nav li a').each(function () {
        let location = window.location.href;
        let link = this.href; 
        if(location == link) {
            $(this).addClass('active');
        }
    });

    new Mmenu("#my-menu", {
        "extensions": [
            "pagedim-black",
            "position-right"
        ]
    });

});


