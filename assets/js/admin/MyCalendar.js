import '@fullcalendar/core/main.css';
import '@fullcalendar/daygrid/main.css';
import '@fullcalendar/timegrid/main.css';
import timeGridPlugin from '@fullcalendar/timegrid';


const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';


import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import ruLocale from '@fullcalendar/core/locales/ru';


export default class MyCalendar {
    constructor(url, calendarElSelector) {
       
        this.url = url;
        this.calendar;
        this.data;
        this.calendarEl = document.getElementById(calendarElSelector);
         Routing.setRoutingData(routes);

    }

    calendarInit(){
        this.getData(this.url).then((data) => {
                this.data = data;
            }).then(() => {
                let eventSources = this.getEventSources(this.data);
                this.renderCalendar(eventSources);

            })
        }
    
    renderCalendar(eventSources) {
        this.calendar = new Calendar(this.calendarEl, {
            header: { center: 'dayGridMonth,timeGridWeek' },
            eventSources: eventSources,
            plugins: [dayGridPlugin, timeGridPlugin],
            locale: ruLocale,
            defaultView: 'dayGridMonth',
            eventClick: (info) => {
                this.showEventInfo(info);
            }

        });

        this.calendar.render();
    }

    rerender(eventSources) {
        this.calendar.destroy();
        this.renderCalendar(eventSources);
    }

    getEventSources(data){

    }

    //return Promise
    getData(url) {
        return fetch(url, {
                headers: {
                    'Content-Type': 'application/json'
                }
            },)
            .then((response) => {
                return response.json();
            });
    }

    filterItemsBySpecialist(arr, id) {
        if (id == "") {
            return this.data;
        } else {
            return arr.filter((item) => {
                return item.specialist.id == id;
            })
        }
    }

    showEventInfo(info, modalSelector) {
        $(modalSelector).modal('show');

    }

    formatDate(startDate, endDate) {

        let ddStart = this.addZero(startDate.getDate());
        let ddEnd = this.addZero(endDate.getDate());
        let mmStart = this.addZero(startDate.getMonth());
        let mmEnd = this.addZero(endDate.getMonth());
        let yearStart = startDate.getFullYear();
        let yearEnd = endDate.getFullYear();
        let hStart = this.addZero(startDate.getHours());
        let hEnd = this.addZero(endDate.getHours());
        let minStart = this.addZero(startDate.getMinutes());
        let minEnd = this.addZero(endDate.getMinutes());

        if (ddStart == ddEnd && mmStart == mmEnd && yearStart == yearEnd) {
            return ddStart + "/" + mmStart + "/" + yearStart + " " + hStart + ":" + minStart + " - " + hEnd + ":" + minEnd;
        } else {
            return ddStart + "/" + mmStart + "/" + yearStart + " " + hStart + ":" + minStart + " - " + ddEnd + "/" + mmEnd + "/" + yearEnd + hEnd + ":" + minEnd;
        }
    }

    addZero(number) {
        if (number < 10) {
            return "0" + number;
        } else {
            return number;
        }
    }

    addAction(eventButtonSelector, formSelector, formAction, message) {
        $(eventButtonSelector).show();
        $(eventButtonSelector).click((e) => {
            e.preventDefault();
            let confirmation = confirm(message);
            if (confirmation) {
                $(formSelector).attr('action', formAction);
                $(formSelector).submit();
            }
        })
    }


}