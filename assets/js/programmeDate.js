import  $ from 'jquery';
const datepicker = require('bootstrap-datepicker/dist/js/bootstrap-datepicker.min');

$(document).ready(function () {
   $('#booking_startDate, #booking_endDate').datepicker({
        format: 'dd/mm/yyyy',
        datesDisabled : tableauDate,
        startDate: new Date()
});

    $('#booking_startDate, #booking_endDate').on('change', calculateAmount);
});

function calculateAmount() {
    //On chope les dates
    const endDate = new Date($('#booking_endDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/,'$3-$2-$1' ));

    const startDate = new Date($('#booking_startDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/,'$3-$2-$1' ));

    if (startDate &&endDate && startDate < endDate){
        const DAY_TIME = 24 * 60 * 60 * 1000;
        const interval = endDate.getTime()- startDate.getTime();
        const  days = interval / DAY_TIME;
        const amount = days * price;

        $('#days').text(days);
        $('#amount').text(amount.toLocaleString('fr-FR'));
    }

}