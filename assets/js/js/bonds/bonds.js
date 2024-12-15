// Set current date
function setCurrentDate(inputId) {
    let today = new Date();
    let day = String(today.getDate()).padStart(2, '0');
    let month = String(today.getMonth() + 1).padStart(2, '0'); // Months start from 0
    let year = today.getFullYear();

    let currentDate = `${year}-${month}-${day}`;
    document.getElementById(inputId).value = currentDate;
}

// Async function to get the last bond number
async function getLastBondNumber(prefix) {
    let response = await fetch(`assets/php/proccess_bonds.php?action=check_number&prefix=${prefix}`);
    let data = await response.json();

    let maxNumber = 0;
    for (let row of data) {
        let currentNumber = Number(row.bond_number.split('-')[1]);
        if (currentNumber > maxNumber) {
            maxNumber = currentNumber;
        }
    }

    return `${prefix}-${maxNumber + 1}`;
}

function setBondNumbers() {
    getLastBondNumber("E").then(function(lastBondNumber) {
        document.getElementById("exchange_number").value = lastBondNumber;
    });

    getLastBondNumber("RB").then(function(lastBondNumber) {
        document.getElementById("receipt_number").value = lastBondNumber;
    });
}

// Initialize date picker
window.onload = function () {
    // Initialize date picker
    flatpickr("#exchange_date", {
        enableTime: false,
        dateFormat: "Y-m-d"
    });

    flatpickr("#receipt_date", {
        enableTime: false,
        dateFormat: "Y-m-d"
    });

    // Set current date
    setCurrentDate("exchange_date");
    setCurrentDate("receipt_date");

    // Set last bond number
    setBondNumbers();
};

function submitForm(form, data, funds) {
    console.log(data);

    var receiptFundSelect = $('#receipt_fund');
var exchangeFundSelect = $('#exchange_fund');
var currencySymbol = $('#receipt_fund option:selected').data('currency-symbole') || $('#exchange_fund option:selected').data('currency-symbole') || 'القيمة الافتراضية';

receiptFundSelect.change(function () {
    currencySymbol = $('option:selected', this).data('currency-symbole');
    // يمكنك هنا استخدام currencySymbol كما تريد.
});

exchangeFundSelect.change(function () {
    currencySymbol = $('option:selected', this).data('currency-symbole');
    // يمكنك هنا استخدام currencySymbol كما تريد.
});

    $.ajax({
        type: 'POST',
        url: 'assets/php/proccess_bonds.php?action=save_bonds',
        data: data.toString(),
        success: function (response) {
            setTimeout(() => {
                form.trigger("reset");
                setBondNumbers();
                setCurrentDate("exchange_date");
                setCurrentDate("receipt_date");
                let bond_type = data.get("bond_type");
                let receiptType = bond_type == "exchange" ? "سند صرف" : "سند قبض";
                let payee = bond_type == "exchange" ? `إصرفوا الى السيد/ة: ` : `إستلمنا من السيد/ة: `;
                let currentTime = new Date().toLocaleTimeString();
                let name = data.get(`${bond_type}_name`);
                let amount = data.get(`${bond_type}_amount`);
                let currencyCode = data.get(`currency`);
                let currency = currencySymbol;
                let description = data.get(`${bond_type}_description`);
                let receiptNumber = data.get(`${bond_type}_number`);
                let date = data.get(`${bond_type}_date`);

                let preparedData = {
                    bond_type: bond_type,
                    receiptType: receiptType,
                    payee: payee,
                    name: name,
                    amount: amount,
                    currency: currency,
                    description: description,
                    receiptNumber: receiptNumber,
                    date: date,
                    createdBy: createdBy,
                    currentTime: currentTime
                }

                // إعداد المحتوى للطباعة
                let contentToPrint = preparePrintContent(preparedData);

                // طباعة المحتوى
                printContent(contentToPrint);
            }, 1000);
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    setBondNumbers();
    setCurrentDate("exchange_date");
    setCurrentDate("receipt_date");
   
});

$('#exchange_form, #receipt_form').submit(function (event) {
    event.preventDefault();
    let form = $(this);
    let dataString = form.serialize();
    let data = new URLSearchParams(dataString);
    submitForm(form, data, funds);  // add funds as a parameter
});


/////////////////
/* Print Function */

function preparePrintContent(preparedData) {
    let printContentData = preparedData;

    var printContent = `<!DOCTYPE html>
    <html dir="rtl">
    
    <head>
    <!-- CSS only -->
    <link rel="preload" href="assets/font/Avenir-Black.ttf" as="font" type="font/ttf" crossorigin>
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="assets/plugins/global/plugins.bundle.rtl.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.rtl.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" rel="stylesheet">
    
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
        <div class="text-center">
                            <h2>${printContentData.receiptType}</h2>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-auto">
                            <h4>رقم السند: ${printContentData.receiptNumber} </h4>
                            </div>
                            <div class="col-auto">
                            <h5 id="date">التاريخ :  ${printContentData.date}</h5>
                            </div>
                        </div>
                    <div class="col mt-3">
                    <h5>${printContentData.payee} ${printContentData.name}</h5>
                    </div>
                    <div class="col mt-3">
                    <h5>مبلغ و قدره : ${printContentData.amount} ${printContentData.currency}</h5>
                    </div>
                    <div class="col mt-3 ">
                    <h5>ذلك عن : ${printContentData.description} </h5>
                    </div>
                 
            </div>
    </main>
        <footer class="mt-auto">
        <div class="row justify-content-between">
                            <div class="col-auto mr-3">
                            <p>طبعة : ${printContentData.createdBy}</p>
                            </div>
                            <div class="col-auto ml-3">
                            <p id="time">الساعة : ${printContentData.currentTime} </p>
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
    
        <!-- JS, Popper.js, and jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    
    </html>
    `;

    return printContent;
}

function printContent(content) {
    // تحديث الـiframe وطباعة المحتوى
    let printFrame = document.getElementById('print_frame');
    printFrame.onload = function() {
        printFrame.contentWindow.print();
    };
    printFrame.contentWindow.document.open();
    printFrame.contentWindow.document.write(content);
    printFrame.contentWindow.document.close();
}

///////////////

function populateRecentBondsTable(data) {
    let html = "";
    data.forEach((row, index) => {
        html += `<tr>
            <td>${index + 1}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.bond_number}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.bond_name}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.amount} ${row.currency}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.description}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.created_at}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.created_date}</td>
            <td class="text-gray-600 text-hover-primary mb-1">${row.bond_type_converted
            }</td>
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-light btn-active-light-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">إجراءات</button>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                        <a class="dropdown-item print-row-btn" href="#" data-order-id="${row.bond_number}">طباعة</a>
                        <a class="dropdown-item delete-btn" href="#" data-order-id="${row.bond_number}">حذف</a>
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
            let bondRow = data.find(row => row.bond_number === bondId);
            let preparedData = preparePrintData(bondRow);
            let contentToPrint = preparePrintContent(preparedData); // renaming the variable
            printContent(contentToPrint); // now this is invoking the printContent function correctly
            
        });
    });

}

function preparePrintData(row) {
    let payee = row.bond_type == "exchange" ? `إصرفوا الى السيد/ة: ` : `إستلمنا من السيد/ة: `;
    let receiptType = row.bond_type == "exchange" ? `سند صرف` : `سند قبض`;
    let currentTime = new Date().toLocaleTimeString();

    return {
        receiptType: receiptType,
        receiptNumber: row.bond_number,
        date: row.created_date,
        payee: payee,
        name:  row.bond_name,
        amount: row.amount,
        currency: row.currency,
        description: row.description,
        createdBy: createdBy,
        currentTime: currentTime
    };
}



document.getElementById("search_button").onclick = function () {
    const searchQuery = document.getElementById("search_input").value;
    fetch(`assets/php/proccess_bonds.php?action=serch_bonds&query=${encodeURIComponent(searchQuery)}`)
        .then(response => response.json())
        .then(data => {
            populateRecentBondsTable(data);
        });
};

document.getElementById("showLastEntriesBtn").onclick = function () {
    const numberOfEntries = document.getElementById("selectNumberOfEntries").value;
    fetch(`assets/php/proccess_bonds.php?action=showLastEntriesBtn&limit=${encodeURIComponent(numberOfEntries)}`)
        .then(response => response.json())
        .then(data => {
            populateRecentBondsTable(data);
        });
};


/////////////////

function deleteBond(bondId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف السند رقم: ${bondId}`,
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
            fetch('assets/php/proccess_bonds.php?action=delete_bonds', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `bond_number=${encodeURIComponent(bondId)}`
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
                            'تم حذف السند بنجاح.',
                            'success'
                        );
                        document.getElementById("showLastEntriesBtn").click();
                    } else {
                        Swal.fire(
                            'حدث خطأ!',
                            `حدث خطأ أثناء حذف السند: ${data.error}`,
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

//////////////////////


