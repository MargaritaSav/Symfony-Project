import MyCalendar from './MyCalendar';

import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

export default class AdminCalendar extends MyCalendar {
    constructor(url, calendarSelector) {
        super(url, calendarSelector);
        this.specialistData;

        $("#specialist").change((e) => {
            let filteredData = this.filterItemsBySpecialist(this.data, e.target.value);
            this.specialistData = filteredData;
            this.rerender(this.getEventSources(filteredData));
        });
        $("#free-time").click(() => {
            this.filterSpecialistItems(this.filterFreeItems);
        });
        $("#booked-time").click(() => {
            this.filterSpecialistItems(this.filterBookedItems);
        })
        $("#not-confirmed").click(() => {
            this.filterSpecialistItems(this.filterUnconfirmedItems);
        })
        $("#all").click(() => {
            if (this.specialistData) {
                this.rerender(this.getEventSources(this.specialistData))
            } else this.rerender(this.getEventSources(this.data))
        })
        $("#client-block").hide();

    }

    getEventSources(data) {
        let bookedEvents = data.filter(this.filterBookedItems);
        let freeEvents = data.filter(this.filterFreeItems);
        let unconfirmedEvents = data.filter(this.filterUnconfirmedItems);
        return [{
                events: bookedEvents,
                textColor: 'white',
                backgroundColor: '#d97817',
                borderColor: 'gray'
            },
            {
                events: freeEvents,
                textColor: 'white',
                backgroundColor: '#1ea471'
            },
            {
                events: unconfirmedEvents,
                textColor: 'white',
                backgroundColor: '#cd3c63'
            },
        ]
    }

    filterBookedItems(item) {
        if (item.is_booked && item.is_confirmed) {
            return true;
        }
        return false;
    }

    filterFreeItems(item) {
        if (!item.is_booked) {
            return true;
        }
        return false;
    }

    filterUnconfirmedItems(item) {
        if (!item.is_confirmed && item.is_booked) {
            return true;
        }
        return false;
    }

    filterSpecialistItems(customFilter) {
        let filteredItems = null;
        if (this.specialistData) {
            filteredItems = this.specialistData.filter(customFilter);
        } else filteredItems = this.data.filter(customFilter)

        this.rerender(this.getEventSources(filteredItems));
    }



    showEventInfo(info) {
        super.showEventInfo(info, "#eventModal");

        let deleteUrl = Routing.generate('lc_agenda_delete');
        let cancelURL = Routing.generate('lc_agenda_cancel');
        let confirmURL = Routing.generate('lc_agenda_confirm');
        let modalForm = "#eventModal form";

        //we hide the buttons before initializing the event's actions 
        $("#cancel").hide();
        $("#delete").hide();
        $("#confirm").hide();
        $("#edit").hide();

        $("#eventId").val(info.event.id);


        //ading event descriptoon to the modal
        $("#modalTitle").text(info.event.title);
        $("#specialist-name").text(info.event._def.extendedProps.specialist.name + ", " + info.event._def.extendedProps.specialist.post);
        $("#time").text(this.formatDate(info.event.start, info.event.end));

        //adding client description if it is booked
        if (info.event._def.extendedProps.client) {
            let client = info.event._def.extendedProps.client;
            let clientHtml = '<div class="row"> <div class="col-12" id="client-name">' + '<p>' + client.name + '</p></div>' +
                '<div class="col" id="client-mail"><p>' + client.email + '</p></div>' +
                '<div class="col" id="client-phone"><p>' + client.phone + '</p></div>' +
                '<div class="col-12"><h6>Описание</h6></div>' +
                '<div class="col-12" id="client-description"><p>' + client.problem_description + '</p></div></div>';
            $("#client-block").show();
            $("#client").html(clientHtml);
        } else {
            $("#client-block").hide();
        }

        let cancelMessage = "Вы уверены, что хотите отменить " + info.event.title + "? Это действие нельзя будет предотвратить"
        let deleteMessage = "Вы уверены, что хотите удалить " + info.event.title + " Из Вашего расписания? Это действие нельзя будет отменить"
        // managing not booked events
        if (!info.event._def.extendedProps.is_booked) {
            //adding edit button
            let url = "/logoped-manager/?entity=Schedule&action=edit&id=" + info.event.id;
            $("#edit").show();
            $("#edit").attr('href', url);

            //adding delete button

            this.addAction("#delete", modalForm, deleteUrl, deleteMessage);

        } else if (info.event._def.extendedProps.is_booked && info.event._def.extendedProps.is_confirmed) {
            //adding cancel event button if this event is booked
            this.addAction("#cancel", modalForm, cancelURL, cancelMessage)
        }

        //managing unconfirmed events
        if (!info.event._def.extendedProps.is_confirmed && info.event._def.extendedProps.is_booked) {
            let confirmMessage = "Вы уверены, что хотите подтвердить запись на " + info.event.title + "? Это действие нельзя будет предотвратить"
            this.addAction("#cancel", modalForm, cancelURL, cancelMessage)
            this.addAction("#confirm", modalForm, confirmURL, confirmMessage);
        }
    }

}