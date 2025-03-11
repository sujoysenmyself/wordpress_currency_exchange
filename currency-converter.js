jQuery(document).ready(function ($) {

    $('#convert-currency').on('click', function () {
        var fromCurrency = $('#from-currency').val();
        var toCurrency = $('#to-currency').val();

        $.ajax({
            type: 'POST',
            url: currencyConverter.ajax_url,
            data: {
                action: 'currency_converter',
                from_currency: fromCurrency,
                to_currency: toCurrency
            },
            beforeSend: function () {
                $('#conversion-result').text('Fetching conversion rate...');
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.rate) {
                    $('#conversion-result').html('<strong>1 ' + fromCurrency + ' = ' + data.rate + ' ' + toCurrency + '</strong>');
                } else {
                    $('#conversion-result').text('Error fetching conversion rate.');
                }
            },
            error: function () {
                $('#conversion-result').text('An error occurred. Please try again.');
            }
        });
    });

});
