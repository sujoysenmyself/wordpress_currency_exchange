jQuery(document).ready(function($) {
    $('#convert-currency').on('click', function() {
        var fromCurrency = $('#from-currency').val();
        var toCurrency = $('#to-currency').val();
        var amount = $('#amount').val();

        if (amount <= 0) {
            alert("Please enter a valid amount.");
            return;
        }

        $.ajax({
            url: currencyConverter.ajax_url,
            type: 'POST',
            data: {
                action: 'currency_converter',
                from_currency: fromCurrency,
                to_currency: toCurrency,
                amount: amount
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.error) {
                    $('#conversion-result').html('<span class="error">' + data.error + '</span>');
                } else {
                    $('#conversion-result').html(
                        'Exchange Rate: 1 ' + fromCurrency + ' = ' + data.rate + ' ' + toCurrency + '<br>' +
                        'Converted Amount: ' + amount + ' ' + fromCurrency + ' = ' + data.converted_value.toFixed(2) + ' ' + toCurrency
                    );
                }
            }
        });
    });
});
