$('#show_report').click(function() {
    var customer_id = location.search.split('customer_id=')[1]
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var transaction_type = $('#tr-types').val();
	var fund_type = $('#fund-types').val();


    var columns_transactions = [
        { title: 'رقم التحويل' },
        { title: 'العملية' },
        { title: 'المبلغ' },
        { title: 'الرصيد' },
		{ title: 'الصندوق' },
        { title: 'البيان' },
        { title: 'التاريخ' },
    ];
    
    var columns_currency = [
        { title: 'رقم الفاتورة' },
        { title: 'العملية' },
        { title: 'العملة' },
        { title: 'الكمية' },
        { title: 'سعر التحويل' },
        { title: 'المجموع' },
        { title: 'التاريخ' },
    ];

    if ($.fn.DataTable.isDataTable('#kt_table_traders_payment')) {
        $('#kt_table_traders_payment').DataTable().clear().destroy();
        $("#kt_table_traders_payment tr").empty();

    }

    var table = $('#kt_table_traders_payment').DataTable({
        dom: 'lrtip',
        retrieve: true,
        destroy: true,
        autoWidth: false, // add this

        columns: transaction_type === 'currency' ? columns_currency : columns_transactions,             
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
            $('#kt_table_traders_show tbody').empty();

            table.clear();
        
            $.ajax({
                url: 'assets/php/customer_transaction.php',
                method: 'GET',
                data: {
                    customer_id: customer_id,
                    from_date: from_date,
                    to_date: to_date,
					fund_type: fund_type,
                    transaction_type: transaction_type
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var balances = {};  // متغير الرصيد
                    if (transaction_type !== 'currency') {
                        data.forEach(function(transaction) {
                            var colorClass = transaction.tr_type_label === 'إيداع' ? 'text-success' : 'text-danger';
                            var balanceColorClass = parseFloat(transaction.accumulated_balance) >= 0 ? 'text-success' : 'text-danger';

                            var tr_amountFormatted = Number(parseFloat(transaction.tr_amount).toFixed(2)).toLocaleString('en-US');
                            var balanceFormatted = Number(parseFloat(transaction.accumulated_balance).toFixed(2)).toLocaleString('en-US');
                            var row = [
                                transaction.id,
                                transaction.tr_type_label,
                                '<span class="' + colorClass + '">' + transaction.currency_symbole + ' ' + tr_amountFormatted + '</span>',
                                '<span class="' + balanceColorClass + '">' + transaction.currency_symbole + ' ' + balanceFormatted + '</span>',
								transaction.currency_sname,
                                transaction.tr_descripcion,
                                transaction.tr_timestamp,
                                
                            ];
                            
                            table.row.add(row);
                           // Clear existing rows
                        $('#kt_table_traders_show tbody').empty();
                                    
                        var currencies = {};
                        var currencyNames = {};
                    
                     
                                      // add the search functionality
            $('#search').on('keyup', function() {
                table.search(this.value).draw();
            });

                    
                    
                        data.forEach(function(transaction) {
                              // Initialize the currency if it doesn't exist yet
                              if (!(transaction.currency_name in currencies)) {
                                currencies[transaction.currency_name] = { deposit: 0, withdraw: 0 };
                                currencyNames[transaction.currency_name] = transaction.currency_sname;
                            }

                            if (transaction.tr_type === 'deposit') {
                                currencies[transaction.currency_name].deposit += parseFloat(transaction.tr_amount);
                            } else if (transaction.tr_type === 'withdraw') {
                                currencies[transaction.currency_name].withdraw += parseFloat(transaction.tr_amount);
                            }
                        });
                    
                    
                    
                        for (var currency in currencies) {
                            var balance = currencies[currency].deposit - currencies[currency].withdraw;
                            var colorClass = balance < 0 ? 'text-danger' : 'text-success';
                        
                            var depositFormatted = parseFloat(currencies[currency].deposit.toFixed(2)).toLocaleString();
                            var withdrawFormatted = parseFloat(currencies[currency].withdraw.toFixed(2)).toLocaleString();
                            var balanceFormatted = parseFloat(balance.toFixed(2)).toLocaleString();
                        
                            var row = $(
                                '<tr>' +
                                '<td>' + currencyNames[currency] + '</td>' + // Use the name from the currencyNames object
                                '<td class="text-success" >' + depositFormatted + '</td>' +
                                '<td class="text-danger" >' + withdrawFormatted + '</td>' +
                                '<td class="' + colorClass + '">' + balanceFormatted + '</td>' +
                                '</tr>'
                            );
                            $('#kt_table_traders_show tbody').append(row);
                        }
                        
                        
                        
                        $('#traders_card').show();

                        });
                    } else {
                        var currencyMatch = {
                            'USD-ILS': 'دولار  - شيكل',
                            'JOD-ILS': 'دينار أردني - شيكل',
                            'USD-JOD': 'دولار - دينار أردني',
                            'EUR-USD': 'دولار - يورو',
                            'EUR-ILS': 'شيكل - يورو',
                            'EGP-ILS': 'شيكل - جنيه مصري',
                            'SAR-ILS': 'شيكل - ريال سعودي',
                            'AED-ILS': 'شيكل - درهم إماراتي',
                            'USD-EGP': 'دولار - جنيه مصري'
                        };
                        $('#search').on('keyup', function() {
                            table.search(this.value).draw();
                        });
                        data.forEach(function(conversion) {
                            var type = (conversion.type === 'buy') ? 'شراء' : (conversion.type === 'sell' ? 'بيع' : conversion.type);
                            var currency_ex = currencyMatch[conversion.currency_ex] || conversion.currency_ex;
                
                            var row = [
                                conversion.order_id,
                                type,
                                currency_ex,
                                conversion.quantity + ' ' + conversion.quantitySymbol,
                                conversion.exchange_rate,
                                conversion.total + ' ' + conversion.totalSymbol,
                                conversion.date + ' ' + conversion.time,
                            ];
                            table.row.add(row);
                            $('#parent_of_traders_show').hide();
                            $('#traders_card').hide();
                        });
                    }
                
                    table.draw();
                }
            });
        });