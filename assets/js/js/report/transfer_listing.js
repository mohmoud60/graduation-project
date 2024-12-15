$('#show_report').click(function() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $.ajax({
        url: 'assets/php/report.php',
        method: 'GET',
        data: {
            action: 'transfer_report',
            from_date: from_date,
            to_date: to_date,
        },
        success: function(response) {

            var transactions = response;
            var incomes = {}; // New object to hold sums of income amounts

            var table = $('#kt_table_company_report').DataTable({
                dom: 'lrtip',
                retrieve: true,
                destroy: true,
                "pageLength": -1,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "language": {
                    "lengthMenu": "عرض _MENU_ ",
                    "zeroRecords": "لم يتم العثور على نتائج",
                    "info": "عرض الصفحة _PAGE_ من _PAGES_",
                    "infoEmpty": "لا توجد نتائج متاحة",
                    "infoFiltered": "(تم التصفية من _MAX_ النتائج الكلية)",
                    "loadingRecords": "جارٍ التحميل...",
                    "processing":     "جارٍ المعالجة...",
                    "search":         "بحث:",
                    "paginate": {
                        "first":      "الأول",
                        "last":       "الأخير",
                        "next":       "التالي",
                        "previous":   "السابق"
                    },
                }  
            });

            // add the search functionality
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });

            table.clear();

            var incomes = {};
var processedTransfers = {}; 

transactions.forEach(function(transaction) {
    var row = [
        transaction.transfer_id,
        transaction.from_account_name,
        transaction.from_amount + ' ' + transaction.from_account_currency,
        transaction.from_type_converted,
        transaction.to_account_name,
        transaction.to_amount + ' ' + transaction.to_account_currency,
        transaction.to_type_converted,
        transaction.income_fund_converted,
        transaction.income_amount + ' ' + transaction.income_fund_currency,
        transaction.description,
        transaction.created_date + ' ' + transaction.created_at, // date
    ];

    table.row.add(row).draw();

    if (!processedTransfers[transaction.transfer_id]) {
        processedTransfers[transaction.transfer_id] = true;
        if (!incomes[transaction.income_fund_currency]) {
            incomes[transaction.income_fund_currency] = 0;
        }
        incomes[transaction.income_fund_currency] += parseFloat(transaction.income_amount);
    }
});

var summaryHtml = "";
for (var currency in incomes) {
    summaryHtml += "<h2>إجمالي الإيرادات بـ " + currency + ": " + incomes[currency].toFixed(2) + "</h2>"; 
}

$('#sum_total').html(summaryHtml);
table.draw();

        }
    });
});
