'use strict';
import '../../css/cal.sass';
const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

import AdminCalendar from './AdminCalendar';


document.addEventListener('DOMContentLoaded', function() {
    Routing.setRoutingData(routes);

    let formUrl = Routing.generate('lc_agenda_form'); 
    let url = Routing.generate('lc_agenda');
    $.ajax({
        url: formUrl,
        success: function(result) {
            $('#specialist').html(result);
            let calendar = new AdminCalendar(url, "calendar");
            calendar.calendarInit();
        }
    })

});