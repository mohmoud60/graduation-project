$('#show_report').click(function() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $.ajax({
        url: 'assets/php/report.php',
        method: 'GET',
        data: {
            action: 'company_expenses',
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
            // add the search functionality
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });

            table.clear();
            console.log(transactions);
            var totalAmounts = {};

            transactions.forEach(function(transaction) {
                var bond_type = '';
                if (transaction.bond_type == 'exchange') {
                    bond_type = 'سند صرف';
                } else if (transaction.bond_type == 'receipt') {
                    bond_type = 'سند قبض';
                }

                var row = [
                    transaction.bond_number,
                    transaction.bond_type,
                    transaction.bond_name,
                    transaction.amount + ' ' + transaction.currency,
                    transaction.description,
                    transaction.created_date + ' ' + transaction.created_time, // date
                ]

                table.row.add(row).draw();
                if (!totalAmounts[transaction.currency]) {
                    totalAmounts[transaction.currency] = 0;
                }
            
                if (transaction.bond_type === 'سند صرف' || transaction.bond_type === 'سند دفع راتب' || transaction.bond_type === 'سند دفع سلفة نقدية') {
                    totalAmounts[transaction.currency] += parseFloat(transaction.amount);
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
            cardBody.empty(); // Clear the old totals

            for (var currency in totalAmounts) {
                var currencyName = currencyNames[currency] || currency; // use the currency symbol if the name is not found
                cardBody.append('<h2>المجموع ' + currencyName + ' : ' + totalAmounts[currency] + ' ' +  currency +'</h2>');
            }
        }
    });
});
