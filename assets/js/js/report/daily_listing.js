$('#show_report').click(function() {
    var from_date = $('#from_date').val();
    $.ajax({
        url: 'assets/php/report.php',
        method: 'GET',
        data: {
            action: 'daily_report',
            from_date: from_date,
        },
        success: function(response) {
            var responseData = JSON.parse(response);
            var transactions = responseData.exchangeData;

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
            var count = 1; // متغير لتتبع عدد الصفوف

            transactions.forEach(function(transaction) {
                var type = '';
                if (transaction.type == 'buy') {
                    type = 'شراء';
                } else if (transaction.type == 'sell') {
                    type = 'بيع';
                }

                var row = [
                    count, 
                    transaction.order_id,
                    type,
                    transaction.currency_ex,
                    transaction.quantity + ' ' + transaction.quantitySymbol,
                    transaction.exchange_rate,
                    transaction.total + ' ' + transaction.totalSymbol,
                    transaction.created_date, // date
                ];

                table.row.add(row).draw();
                count++;
                
            });

            table.draw();
             // Display the totals in the card
             
             var financialData = responseData.financialData[0]; // إذا كانت النتيجة عبارة عن مصفوفة يتم اخذ القيمة الأولى

             $('#morning_total').text("الصندوق الإفتتاحي : " + parseFloat(financialData.morning_total).toFixed(2) + " ₪" );
             $('#evening_total').text("الصندوق النهائي : " + parseFloat(financialData.evening_total).toFixed(2) + " ₪" );
             $('#difference').text("الفرق : " + parseFloat(financialData.difference).toFixed(2) + " ₪" );
             $('#bonds_expense_total').text("مجموع المصروفات : " + parseFloat(financialData.bonds_expense_total).toFixed(2) + " ₪" );
             $('#bonds_receipt_total').text("مجموع المقبوضات : " + parseFloat(financialData.bonds_receipt_total).toFixed(2) + " ₪" ) ;
             $('#total_prof').text("الربح اليومي : " + parseFloat(financialData.total_prof).toFixed(2) + " ₪" );
             
             $('#ditales').removeClass('d-none');
        
        }
    });
});
