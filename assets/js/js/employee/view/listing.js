$('#show_report').click(function() {
    var employee_id = location.search.split('employee_id=')[1];
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();

    $.ajax({
        url: 'assets/php/employee_transaction.php?action=show_report',
        method: 'POST',
        data: {
            employee_id: employee_id,
            from_date: from_date,
            to_date: to_date,
        },
        success: function(response) {
            var transactions = response; // the response might already be a JavaScript object

            var table = $('#kt_table_employee_payment').DataTable({
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

            transactions.forEach(function(transaction, index) {
                var type;
                var monthYear;
                var formattedDate = transaction.date.split("-").reverse().join("-");
                var date = new Date(formattedDate);
            
                if (transaction.transaction_type == 'Advances') {
                    monthYear = (date.getMonth() + 1).toString().padStart(2, '0') + '/' + date.getFullYear();
                    type = 'سلفة نقدية عن شهر ' + monthYear;
                } else if (transaction.transaction_type == 'Salary') {
                    monthYear = (date.getMonth() + 1).toString().padStart(2, '0') + '/' + date.getFullYear();
                    type = 'دفع راتب شهر ' + monthYear;
                } else {
                    type = transaction.transaction_type;
                }
            
                var row = [
                    index + 1,
                    type,
                    transaction.amount + ' ₪',
                    transaction.date,
                    transaction.time,
                ];
            
                table.row.add(row);
            });
            
            table.draw();
            
            
            table.draw();
            
        }
    });
});
