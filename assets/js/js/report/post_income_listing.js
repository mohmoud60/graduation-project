$('#show_report').click(function() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $.ajax({
        url: 'assets/php/report.php',
        method: 'GET',
        data: {
            action: 'posting_income_report',
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
            transactions.forEach(function(transaction) {

                var row = [
                    transaction.id,
                    transaction.ils_amount + ' ₪',
                    transaction.usd_amount + ' $',
                    transaction.created_date + ' ' + transaction.created_time, // date
                    transaction.created_by, // date
                ]

                table.row.add(row).draw();
                
            });

            table.draw();
             // Display the totals in the card
             
        
        }
    });
});
