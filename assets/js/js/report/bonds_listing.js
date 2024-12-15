$('#show_report').click(function() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $.ajax({
        url: 'assets/php/report.php',
        method: 'GET',
        data: {
            action: 'bonds_reports',
            from_date: from_date,
            to_date: to_date,
        },
        success: function(response) {
            var transactions = JSON.parse(response);

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

            table.clear();
            var totalExchanges = {};
            var totalReceipts = {};
            transactions.forEach(function(transaction) {
                var row = [
                    transaction.bond_number,
                    transaction.bond_type,
                    transaction.bond_name,
                    transaction.amount + ' ' + transaction.currency,
                    transaction.description,
                    transaction.created_date, // date
                    transaction.created_time  // time
                ]

                table.row.add(row).draw();
                if (transaction.bond_type === 'سند صرف' || transaction.bond_type === 'سند دفع راتب' || transaction.bond_type === 'سند دفع سلفة نقدية') {
                    if (!totalExchanges[transaction.currency]) {
                        totalExchanges[transaction.currency] = 0;
                    }
                    totalExchanges[transaction.currency] += parseFloat(transaction.amount);
                } else if (transaction.bond_type === 'سند قبض') {
                    if (!totalReceipts[transaction.currency]) {
                        totalReceipts[transaction.currency] = 0;
                    }
                    totalReceipts[transaction.currency] += parseFloat(transaction.amount);
                }
                
            });

            table.draw();
             // Display the totals in the card
             
             var currencyNames = {
                "$": "دولار أمريكي",
                "₪": "شيكل إسرائيلي",
                "د.أ": "دينار أردني",
                "ج.م": "جنيه مصري",
                "€": "يورو",
                "ر.س": "ريال سعودي",
                "د.إ": "درهم إماراتي"
            };
            
            var cardBody = $("#total");
            cardBody.empty();

            for (var currency in totalExchanges) {
                var currencyName = currencyNames[currency] || currency;
                cardBody.append('<h2>المجموع الصرف ' + currencyName + ' : ' + totalExchanges[currency] + ' ' +  currency +'</h2>');
            }

            for (var currency in totalReceipts) {
                var currencyName = currencyNames[currency] || currency;
                cardBody.append('<h2>المجموع القبض ' + currencyName + ' : ' + totalReceipts[currency] + ' ' +  currency +'</h2>');
            }
        }
    });
});
