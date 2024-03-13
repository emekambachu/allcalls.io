let DateService = {

    currentDateMDY() {
        const today = new Date();
        const yyyy = today.getFullYear();
        let mm = today.getMonth() + 1; // Months start at 0!
        let dd = today.getDate();

        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;

        return mm + '/' + dd + '/' + yyyy;
    },

    formatDate(date, separator = '/') {
        console.log('DateBefore', date);

        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;


        console.log('DateAfter', [month, day, year].join(separator));

        return [month, day, year].join(separator);
    },


    formatDateForInputRange(date, separator = '/') {
        console.log('DateBefore', date);

        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;


        console.log('DateAfter', [month, day, year].join(separator));

        return [month, day, year].join(separator);
    }

}

export default DateService;
