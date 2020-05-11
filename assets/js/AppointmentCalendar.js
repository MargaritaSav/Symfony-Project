import MyCalendar from './admin/MyCalendar';

import $ from 'jquery';

import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

export default class AppointmentCalendar extends MyCalendar {
    constructor(url, calendarSelector) {
        super(url, calendarSelector);

        $("#specialist").change((e) => {
            let filteredData = this.filterItemsBySpecialist(this.data, e.target.value);
            $(this.calendarEl).slideToggle(500);
            this.rerender(this.getEventSources(filteredData));
            $(this.calendarEl).slideToggle(500);
        });
    }

    getEventSources(data) {
        return [{
            events: data,
            textColor: 'white',
            backgroundColor: '#1ea471'
        }]
    }

    showEventInfo(info) {
        
        let formUrl = Routing.generate('lc_appointment_inscription', { id: info.event.id });

        $("#modalTitle").text(info.event.title);
        $("#time").text(this.formatDate(info.event.start, info.event.end));
        $("#specialist-name").text(info.event._def.extendedProps.specialist.name + ", " + info.event._def.extendedProps.specialist.post);
        $("#eventModal").modal('show');
        $.ajax({
            url: formUrl,
            success: function(result) {
                $('#client').html(result);
                $("#eventModal form").attr('action', formUrl)
            }
        });
    }
}