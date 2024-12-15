$(document).ready(function () {
    $('form').on('submit', function (e) {
        e.preventDefault();

        let isValid = true;
let emptyFields = "";

$('#service input[type="number"], #service textarea').each(function() {
    if ($(this).closest('.d-none').length > 0) {
        return true; // Skip hidden elements by class
    }

    if ($(this).val() == '') {
        isValid = false;
        emptyFields += $(this).prev('label').text() + ", ";
    }
});

$('#service select').each(function() {
    if ($(this).closest('.d-none').length > 0) {
        return true; // Skip hidden elements by class
    }

    if (!$(this).is(':visible')) {
        return true; // Skip hidden select elements
    }
    
    if ($(this).val() == '') {
        isValid = false;
        emptyFields += $(this).prev('label').text() + ", ";
    }
});

if (!isValid) {
    emptyFields = emptyFields.slice(0, -2);
    Swal.fire({
        title: 'خطأ',
        text: 'الرجاء ملء الحقول التالية: ' + emptyFields,
        icon: 'error',
        confirmButtonText: 'حسنًا',
        customClass: {
            confirmButton: 'btn btn-primary'
        }
    });
    return;
}

        // AJAX request
        $.ajax({
            type: 'POST',
            url: 'assets/php/service_process.php?action=save',
            data: $('form').serialize(),
            
            success: function (response) {
                let responseData = JSON.parse(response);
                if (responseData.success) {
                    let selectedOption = $('#from_account option:selected');
                    let sname, currency, payee;
            
                    let accountType = selectedOption.val().split('_')[0];
            
                    // Check if the selected option is a fund, customer or trader
                    switch(accountType) {
                        case 'fund':
                            sname = selectedOption.data('currency-sname');
                            currency = selectedOption.data('currency-symbol');
                            payee = 'صناديق النقد - ' + selectedOption.data('accounts-name');
                            break;
                        case 'customer':
                            sname = $('#from_currency option:selected').data('currency-sname');
                            currency = $('#from_currency option:selected').data('currency-symbol');
                            payee = $('#from_account option:selected').data('accounts-name');
                            break;
                        case 'trader':
                            sname = $('#from_currency option:selected').data('currency-sname');
                            currency = $('#from_currency option:selected').data('currency-symbol');
                            payee = $('#from_account option:selected').data('accounts-name');
                            break;
                        default:
                            sname = '';
                            currency = '';
                            payee = '';
                            break;
                    }
            
                    let preparedData = {
                        receiptType: $('#from_type').val(),
                        receiptNumber: responseData.transfer_id,
                        date: new Date().toLocaleDateString(),
                        name: selectedOption.text(),
                        amount: $('#from_amount').val(),
                        word_amount: responseData.from_amount_in_words,
                        sname: sname,
                        currency: currency,
                        payee: payee,
                        description: $('#description').val(),
                        currentTime: new Date().toLocaleTimeString()
                    };
            
                    // Prepare the print content
                    let printContentData = preparePrintContent(preparedData);
            
                    // Print the content
                    printContent(printContentData);
                }
             else {
                    Swal.fire({
                        title: responseData.message,
                        icon: 'error',
                        confirmButtonText: 'حسنًا',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    });
                }

            },
            error: function (response) {
                Swal.fire({
                    title: 'حدث خطأ أثناء الحفظ.',
                    icon: 'error',
                    confirmButtonText: 'حسنًا',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }
        });
    });
});

function preparePrintContent(preparedData) {
    let receiptType = preparedData.receiptType == 'deposit' ? 'إيصال قبض' : 'إيصال صرف';

    let printContentDataPart = {
        ...preparedData,
        receiptType: receiptType,
        createdBy: createdBy,
    };

    let printContentData = `
        <div class="col mt-3 mb-8 ml-8">
            <h2>الحساب : ${printContentDataPart.payee} ${printContentDataPart.name ? printContentDataPart.name : ''}</h2>
        </div>
        <div class="col mt-3 mb-8 ml-8">
            <h2>مبلغ و قدره : ${printContentDataPart.amount} ${printContentDataPart.currency} ${printContentDataPart.sname ? printContentDataPart.sname : ''} - * - ${printContentDataPart.word_amount ? printContentDataPart.word_amount : ''} ${printContentDataPart.sname ? printContentDataPart.sname : ''} فقط لا غير   - * -</h2>
        </div>`;

    var printContent = `<!DOCTYPE html>
    <html dir="rtl">
    
    <head>
    
        <!-- CSS only -->
        <link rel="preload" href="assets/font/Avenir-Black.ttf" as="font" type="font/ttf" crossorigin>
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href="assets/plugins/global/plugins.bundle.rtl.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.rtl.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <style>

        @font-face {
            font-family: 'Avenir';
            src: url('assets/font/Avenir-Black.ttf') format('truetype');
    }
          body{
              font-family: "Avenir", sans-serif, Helvetica;
              background-color: #fff;
          }

            @media print {
        @page {
            size: 210mm 148mm; /* landscape orientation */
            margin: auto;
            margin-left: auto;
        }
        .footer .fs-4 {
            font-size: 11px; /* Change the font size to fit your needs */
        }
    
        .footer .fs-6 {
            font-size: 11px; /* Change the font size to fit your needs */
        }
            }
    
    
            .header-line {
                align-items: center;
                display: flex;
            }
    
            .header img {
                left: 20%;
                position: absolute;
                top: 10px;
                width: 150px;
                height: 150px;
            }
    
            .footer {
                width: 100%;
                background-color: #fff;
                color: #252525;
              
            }
    
            .cline {
                border-top: 20px solid #252525;
                width: 10%;
            }
    
            .pline {
                border-top: 15px solid #ffcc32;
                width: 10%;
            }
    
            .line {
                height: 5px;
                width: 60%;
                background: linear-gradient(to right, #ffcc32 50%, #252525 50%);
                margin-top: 20px;
            }
    
            .sline {
                border-top: 5px solid #ffcc32;
                width: 20%;
                margin-top: 25px;
                position: absolute;
                left: 0%;
                top: 110px;
            }
    
            .footerline {
                height: 5px;
                width: 100%;
                background: linear-gradient(to right, #ffcc32 33.3%, #252525 33.3%);
            }

            @media print {
                @page {
                    size: 210mm 148mm; /* landscape orientation */
                    margin: auto;
                    margin-left: 10mm; /* Adjust this value to suit your needs */
                }
                .footer .fs-4 {
                    font-size: 11px; /* Change the font size to fit your needs */
                }
            
                .footer .fs-6 {
                    font-size: 11px; /* Change the font size to fit your needs */
                }
            }

            
        </style>
    </head>
    
    <body class="d-flex flex-column min-vh-100">
        <header>
            <div class="header py-3">
                <div class="header-line">
                    <div class="cline"></div>
                    <h2 style="color: #252525; font-size: 48px; margin-right: 10px;">شركة دولار</h2>
                </div>
                <div class="header-line">
                    <div class="pline"></div>
                    <h2 style="color: #ffcc32; font-size: 22px; margin-right: 10px;">للصرافة و الدفع الإلكتروني</h2>
                </div>
                <div class="line"></div>
                <img src="assets/media/dollar/Company_logo.png" alt="Company Logo">
                <div class="sline"></div>
            </div>
        </header>
    
        <main class="container-fluid">
        <div class="text-center mt-8">
        <h2>${printContentDataPart.receiptType} (${printContentDataPart.receiptNumber})</h2>

    </div>
    <div class="text-end">
        <h5 id="date">التاريخ : ${printContentDataPart.date}</h5>
    </div>

    ${printContentData}
                    <div class="col mt-3 mb-8 ml-8">
                    <h2>البيان : ${printContentDataPart.description} </h2>
                    </div>
                 
            </div>
    </main>
        <footer class="mt-auto">
        <div class="row justify-content-between">
                            <div class="col-auto mr-3">
                            <p>طبعة : ${printContentDataPart.createdBy}</p>
                            </div>
                            <div class="col-auto ml-3">
                            <p id="time">الساعة : ${printContentDataPart.currentTime} </p>
                            </div>
                        </div>
            <div class="footer py-2">
                <p class="footerline w-100">
                <div class="footer-content d-flex justify-content-between align-items-center px-4">
                    <p class="mb-0 fs-4">غزة-مفترق الجلاء الصفطاوي - الشارع العام</p>
                    <p class="contact-info mb-0 fs-6">جوال واتس : 0598201000  - وطنية واتس : 0567201000</p>
                </div>
            </div>
        </footer>
    
        <!--begin::Javascript-->
        <script>var hostUrl = "assets/";</script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <!--end::Global Javascript Bundle-->
    </body>
    
    </html>
    `;
    return printContent;
}


function printContent(content) {
    let printFrame = document.getElementById('print_frame');
    printFrame.onload = function() {
        printFrame.contentWindow.print();
        setTimeout(function () {
            window.location.reload(true);
        }, 10);
    };
    printFrame.contentWindow.document.open();
    printFrame.contentWindow.document.write(content);
    printFrame.contentWindow.document.close();
}


//////////

function populateRecentBondsTable(data) {
    let html = "";
    data.forEach((row, index) => {
        html += `<tr>
            <td>${index + 1}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.transfer_id}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.from_account_name}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.from_amount} ${row.from_account_currency}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.from_type_converted}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.to_account_name}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.to_amount} ${row.to_account_currency}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.to_type_converted}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.income_fund_converted}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.income_amount} ${row.income_fund_currency}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.description}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.created_date} ${row.created_at}</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-active-light-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">إجراءات</button>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                        <a class="dropdown-item print-row-btn" href="#" data-order-id="${row.transfer_id}">طباعة</a>
                        <a class="dropdown-item delete-btn" href="#" data-order-id="${row.transfer_id}">حذف</a>
                    </div>
                </div>
            </td>
        </tr>`;
    });

    let tbody = document.getElementById("recent_bonds_table").getElementsByTagName('tbody')[0];
    tbody.innerHTML = html;

    // Attach delete event listeners
    tbody.querySelectorAll('.delete-btn').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            let bondId = event.target.getAttribute('data-order-id');
            deleteBond(bondId);
        });
    });
   
    tbody.querySelectorAll('.print-row-btn').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            let bondId = event.target.getAttribute('data-order-id');
            let bondRow = data.find(row => String(row.transfer_id) === String(bondId));
            let preparedData = preparePrintData(bondRow);
            let contentToPrint = preparePrintContent(preparedData); // renaming the variable
            printContent(contentToPrint); // now this is invoking the printContent function correctly
            
        });
    });

}
function preparePrintData(row) {
    let receiptType = row.from_type_converted == 'إيداع' ? 'deposit' : 'withdraw';

    let currentTime = new Date().toLocaleTimeString();

    let description = row.description;
    if (description.includes('قص')) {
        description = description.split('قص')[0].trim();
    }

    let receiptNumber = row.transfer_id.toString().padStart(10, '0');
    
    let payee = row.from_account_name;

    // إعادة صياغة القيمة لـ payee استنادًا إلى قيمة row.from_account_type
    if (row.from_account_type == 'funds') {
        payee = "صناديق النقد - " + row.from_account_name;
    } else if (row.from_account_type == 'customer') {
        payee = "حساب عملاء - زبائن - " + row.from_account_name;
    } else if (row.from_account_type == 'trader') {
        payee = "حساب عملاء - تاجر - " + row.from_account_name;
    }

    return {
        receiptType: receiptType,
        receiptNumber: receiptNumber,
        date: row.created_date,
        payee: payee,
        amount: row.from_amount,
        currency: row.from_account_currency,
        sname: row.currency_sname,
        description: description,
        createdBy: createdBy,
        currentTime: currentTime,
        word_amount: row.word_amounts,
    };
}






document.getElementById("search_button").onclick = function () {
    const searchQuery = document.getElementById("search_input").value;
    fetch(`assets/php/service_process.php?action=search_transfers&query=${encodeURIComponent(searchQuery)}`)
        .then(response => response.json())
        .then(data => {
            populateRecentBondsTable(data);
        });
};

document.getElementById("showLastEntriesBtn").onclick = function () {
    const numberOfEntries = document.getElementById("selectNumberOfEntries").value;
    fetch(`assets/php/service_process.php?action=showLastEntriesBtn&limit=${encodeURIComponent(numberOfEntries)}`)
    .then(response => {
        if (!response.ok) throw new Error("HTTP error " + response.status);
        return response.json();
    })
    .then(data => {
        populateRecentBondsTable(data);
    })
    .catch(error => {
        console.log('Fetch error: ', error);
    });

};

////////////
function deleteBond(bondId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف إيصال رقم: ${bondId}`,
        icon: 'warning',
        showCancelButton: true,

        confirmButtonText: 'نعم، قم بالحذف!',
        cancelButtonText: 'إلغاء',
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-active-light'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('assets/php/service_process.php?action=delete_bonds', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `transfer_id=${encodeURIComponent(bondId)}`
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire(
                            'تم الحذف!',
                            'تم حذف الإيصال بنجاح.',
                            'success'
                        );
                        document.getElementById("showLastEntriesBtn").click();
                    } else {
                        Swal.fire(
                            'حدث خطأ!',
                            `حدث خطأ أثناء حذف الإيصال: ${data.error}`,
                            'error'
                        );
                    }
                })
                .catch(error => {
                    Swal.fire(
                        'حدث خطأ!',
                        `There was a problem with the fetch operation: ${error.message}`,
                        'error'
                    );
                });
        }
    });
}
///////////////

// Initialize the modal
var customerSearchModal = new bootstrap.Modal(document.getElementById('customerSearchModal'), {
    keyboard: false
  })
  
  // Show the search modal when the button is clicked
  document.getElementById('serch_customer').addEventListener('click', function() {
    customerSearchModal.show();
  });

  document.addEventListener('keydown', function(event) {
    // Check if the 'alt' key and 'a' key are pressed together
    if (event.altKey && (event.key === 'a' || event.key === 'A')) {
      // Show the modal
      customerSearchModal.show();
    }
  });
  
  // Fetch search results when input changes
  document.getElementById('customerSearchInput').addEventListener('input', function() {
    const searchQuery = this.value;
  
    if (!searchQuery) {
      document.getElementById('customerSearchResults').innerHTML = '';
      return;
    }
  
    fetch('assets/php/service_process.php?action=search_balance_customers', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ query: searchQuery })
    })
    .then(response => response.json())
    .then(data => {
        let html = "<table class='table'><thead><tr><th>اسم الزبون / التاجر</th><th>نوع الحساب</th><th>الرصيد الحالي</th></tr></thead><tbody>";
        data.forEach(entry => {
            let balanceColor = (parseFloat(entry.balance) >= 0) ? 'text-success' : 'text-danger';
            let entityType = entry.type === 'customer' ? 'زبون' : 'تاجر';
            html += `
                <tr class="customer-row">
                   
                    <td>${entry.name}</td>
                    <td>${entityType}</td>
                    <td class="${balanceColor}">${entry.balance}</td>
                </tr>
            `;
        });
        html += "</tbody></table>";
        document.getElementById('customerSearchResults').innerHTML = html;
    })
    
.catch(error => {
  Swal.fire({
      icon: 'error',
      title: 'خطأ',
      text: 'خطأ في البحث عن العملاء: ' + error,
      confirmButtonText: 'تأكيد'
  });
});

  });
  
  //////////