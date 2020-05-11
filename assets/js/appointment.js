'use strict';

import '../css/appointment.sass';
import AppointmentCalendar from './AppointmentCalendar';

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import $ from 'jquery';


Routing.setRoutingData(routes);
let dataUrl = Routing.generate('lc_appointment_specialist');
let filter = Routing.generate('lc_agenda_form');


$.ajax({
    url: filter,
    success: function(result) {
        $('#specialistFilter').html(result);
        let calendar = new AppointmentCalendar(dataUrl, "calendar");
        calendar.calendarInit();
        $("#calendar").show();
        $("#preloader").hide();    
    }
});

$("#inscriptionLink").click((e)=>{
    e.preventDefault();
    $("#client").toggle(400);
    
})
