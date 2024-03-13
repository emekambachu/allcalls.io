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

    // formatDate(date, separator = '/') {
    //     console.log('DateBefore', date);

    //     let d = new Date(date),
    //         month = '' + (d.getMonth() + 1),
    //         day = '' + d.getDate(),
    //         year = d.getFullYear();

    //     if (month.length < 2)
    //         month = '0' + month;
    //     if (day.length < 2)
    //         day = '0' + day;


    //     console.log('DateAfter', [month, day, year].join(separator));

    //     return [month, day, year].join(separator);
    // }


    formatDate(date, separator = '/') {
        // Split the input date string into components
        const [year, month, day] = date.split('-').map(part => parseInt(part, 10));

        // Create a new Date object using the local time zone
        const d = new Date(year, month - 1, day);

        // Format the date components, ensuring two digits
        const formattedMonth = ('0' + (d.getMonth() + 1)).slice(-2);
        const formattedDay = ('0' + d.getDate()).slice(-2);
        const formattedYear = d.getFullYear();

        // Join the components with the separator
        return [formattedMonth, formattedDay, formattedYear].join(separator);

    }

}

export default DateService;
