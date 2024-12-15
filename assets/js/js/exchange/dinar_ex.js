$(document).ready(function() {
    // العملة الأولى
    fetchExchangeRate('10200_10201');

    // العملة الثانية
    fetchExchangeRate('10202_10201');

    // إعادة الحساب عند تغير قيمة الحقول
    $('#10200_10201, #10202_10201').on('input', calculateValue);
});

function fetchExchangeRate(inputId) {
    var url = 'assets/php/process_currency.php?action=fetch_exchange_rate&currency_ex=' + inputId;
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var sellRate = data.sell_rate;
            
            // تعبئة القيمة بمعدل الشراء
            $('#' + inputId).val(sellRate);
            
            // تحسيب القيمة
            calculateValue();
        },
        error: function(error) {
            console.log('Error: ' + error);
        }
    });
}

function calculateValue() {
    var usdILS = parseFloat($('#10200_10201').val());
    var jodILS = parseFloat($('#10202_10201').val());
    
    if (!isNaN(usdILS) && !isNaN(jodILS) && jodILS !== 0) {
        var total = (usdILS / jodILS).toFixed(4);
        $('#sell-buy-total').val(total);
    }
}