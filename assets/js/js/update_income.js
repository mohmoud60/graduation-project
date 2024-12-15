"use strict";

var KTModalCustomersAdd = function () {
    var t, e, o, n, r, i;

    return {
        init: function () {
            
            i = new bootstrap.Modal(document.querySelector("#updateIncomeModal"));
                r = document.querySelector("#updateIncomeModal_form"),
                t = r.querySelector("#updateIncomeModal_submit"),
                e = r.querySelector("#updateIncomeModal_cancel"),
                o = r.querySelector("#updateIncomeModal_close");
                i._element.addEventListener('show.bs.modal', function (event) {
                    var incomeElements = document.querySelectorAll(".income_total input"); // get all income input elements
                    var allEmpty = true; // initially assume all inputs are empty or zero
    
                    for (var i = 0; i < incomeElements.length; i++) {
                        var income = parseFloat(incomeElements[i].value.replace(',', '')); // parse the income value as float after removing commas
                        if (!isNaN(income) && income > 0) {
                            allEmpty = false; // if any income value is valid and greater than zero, set allEmpty to false
                            break;
                        }
                    }
    
                    if (allEmpty) {
                        Swal.fire({
                            text: "لا يوجد إيرادات للترحيل",
                            icon: "warning",
                            buttonsStyling: !1,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        event.preventDefault(); // prevent the modal from showing
                    }
                });
            t.addEventListener("click", (function (e) {
                e.preventDefault();
                var formData = new FormData(r);

                // Get currency amounts
                var incomeElements = document.querySelectorAll(".income_total input"); // get all income input elements
                var amounts = {};
                            
                for (var j = 0; j < incomeElements.length; j++) {
                    var currencySymbol = incomeElements[j].previousElementSibling.innerText.trim(); // get the currency symbol
                    var amount = parseFloat(incomeElements[j].value.replace(',', '')); // parse the income value as float after removing commas
                
                    if (currencySymbol == '$') {
                        amounts['dollarAmount'] = amount;
                    } else if (currencySymbol == '₪') {
                        amounts['ilsAmount'] = amount;
                    }
                }
                
                // Add amounts to formData
                formData.append('dollarAmount', amounts['dollarAmount']);
                formData.append('ilsAmount', amounts['ilsAmount']);
                
                // Check if the form fields are not empty
                for (var pair of formData.entries()) {
                    if (pair[1].trim() === "") {
                        Swal.fire({
                            text: "معذرة، يبدو أنه تم اكتشاف بعض الأخطاء ، يرجى إدخال جميع الحقول.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        return;
                    }
                }

// Get modal elements
let modal = document.querySelector('#incomeConfirmModal');
let modalInstance = new bootstrap.Modal(modal);
let usdInput = modal.querySelector('#usd');
let ilsInput = modal.querySelector('#ils');
let confirmButton = modal.querySelector('.confirm-income-btn');
let cancelButton = modal.querySelector('.btn-secondary');

// Get the total amount
let total = document.getElementById('total').innerHTML;

// Set the values for the inputs
usdInput.value = amounts['dollarAmount'];
ilsInput.value = amounts['ilsAmount'];

// Show the modal
modalInstance.show();

// Attach click event handler to the confirm button
confirmButton.addEventListener('click', function() {
    let formData = new FormData();
    formData.append('dollarAmount', usdInput.value);
    formData.append('ilsAmount', ilsInput.value);

    t.setAttribute("data-kt-indicator", "on");
    t.disabled = 0;

    // AJAX request
    $.ajax({
        url: 'assets/php/process_fund.php?action=update_income',
        type: 'POST',
        data: formData,
        processData: false,  // Important!
        contentType: false,  // Important!
        success: function (response) {
            t.removeAttribute("data-kt-indicator");
            if (response === 'success') {
                let preparedData = {
                    date: new Date().toLocaleDateString(),
                    usd: usdInput.value,
                    ils: ilsInput.value,
                    currentTime: new Date().toLocaleTimeString()
                };

                // Prepare the print content
                let printContentData = preparePrintContent(preparedData);

                // Print the content
                printContent(printContentData);

                // Close the modal
                modalInstance.hide();

                i.hide();
                t.disabled = !1;
            } else {
                alert("معذرة ، يبدو أنه تم اكتشاف بعض الأخطاء ، يرجى المحاولة مرة أخرى.");
            }
        }
    });
});

// Attach click event handler to the cancel button
cancelButton.addEventListener('click', function() {
    // Just hide the modal
    modalInstance.hide();
});
        
            }));
           

            e.addEventListener("click", (function (t) {
                t.preventDefault(),
                    Swal.fire({
                        text: "هل أنت متأكد أنك تريد الإلغاء؟",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "نعم، ألغ!",
                        cancelButtonText: "لا، إبقاءه!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then((function (t) {
                        t.value ? i.hide() : "cancel" === t.dismiss && Swal.fire({
                            text: "تم تعليق النموذج الخاص بك.",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        })
                    }))
            }));

            o.addEventListener("click", (function (t) {
                t.preventDefault(),
                    Swal.fire({
                        text: "هل أنت متأكد أنك تريد الإغلاق؟",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "نعم، أغلقها!",
                        cancelButtonText: "لا، أبقها مفتوحة!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then((function (t) {
                        t.value ? i.hide() : "cancel" === t.dismiss && Swal.fire({
                            text: "لقد بقيت على الصفحة!",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        })
                    }))
            }));
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded((function () {
    KTModalCustomersAdd.init();
}));


function preparePrintContent(preparedData) {
    let printContentDataPart = {
        ...preparedData,
        createdBy: createdBy,
    };

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
        <h2>سند ترحيل رصيد إيرادات</h2>

    </div>
    <div class="text-end">
        <h5 id="date">التاريخ : ${printContentDataPart.date}</h5>
    </div>

    <div class="col mt-3 mb-8 ml-8">
    <h2>مبلغ و قدره دولار أمريكي  :  ${printContentDataPart.usd ? printContentDataPart.usd : 0} $ دولار أمريكي</h2>
</div>
<div class="col mt-3 mb-8 ml-8">
    <h2>مبلغ و قدره  شيكل إسرائيلي  :  ${printContentDataPart.ils ? printContentDataPart.ils : 0} ₪ شيكل إسرائيلي</h2>
</div>

                    <div class="col mt-3 mb-8 ml-8">
                    <h2>ذلك عن : ترحيل رصيد إيرادات من صناديق فرعية الى صناديق رئيسية بتاريخ ${printContentDataPart.date} </h2>
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
