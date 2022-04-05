import moment from 'moment';

const ClientTimezone = 7;

export default class DateTimeService {
    static getClientTimezone() {
        return ClientTimezone;
    }

    static getCurrentClientDateTime() {
        const tempdate = new Date();
        const utcdatestring = tempdate.toUTCString();
        return moment(utcdatestring).utcOffset(ClientTimezone);
    }

    static convertFromUTC(date) {
        const tempdate = new Date(date);
        const utcdate = new Date(Date.UTC(tempdate.getFullYear(),
            tempdate.getMonth(),
            tempdate.getDate(),
            tempdate.getHours(),
            tempdate.getMinutes(),
            tempdate.getSeconds()));
        const utcdatestring = utcdate.toUTCString();
        return moment(utcdatestring).utcOffset(ClientTimezone);
    }

    static formatDateTime(date, format) {
        return moment(date).format(format);
    }

    static toShortTimeFormat(date) {
        return moment(date).format('HH:mm');
    }
}