function printData() {
    // Fetch data from the page
    var fromDate = document.getElementById("from_date").value;
    var toDate = document.getElementById("to_date").value;
    var balances = Array.from(document.querySelectorAll("#total"))
        .map(element => element.innerHTML)
        .join('<br>');
    var payments = Array.from(document.querySelectorAll("#kt_table_company_report tr"))
        .map(element => element.outerHTML);

    var paymentsHTML = '';
    // Fetch table headers
    var tableHeaders = document.querySelector("#kt_table_company_report tr").outerHTML;

    var paymentsPerPageFirstPage = 11;  // How many payments you want per the first page
    var paymentsPerPageOtherPages = 13;  // How many payments you want per other pages

    for (let i = 0; i < payments.length; i++) {
        if (i != 0 && (i == paymentsPerPageFirstPage || (i > paymentsPerPageFirstPage && (i - paymentsPerPageFirstPage) % paymentsPerPageOtherPages === 0))) {
            paymentsHTML += `</tbody></table>${getFooter()}</div><div class="new-page">${getHeader()}<table class="table" id="payment_table"><tbody id="payment_tbody">${tableHeaders}`;
        }
        paymentsHTML += payments[i];
    }


    function getFooter() {
        return `<footer class="mt-auto">
                    <div class="footer py-2">
                        <div class="footerline w-100"></div>
                        <div class="footer-content d-flex justify-content-between align-items-center px-4">
                            <p class="mb-0 fs-4">غزة-مفترق الجلاء الصفطاوي - الشارع العام</p>
                            <p class="contact-info mb-0 fs-6">رقم تواصل: جوال واتس : 0598201000  - وطنية واتس : 0567201000</p>
                        </div>
                    </div>
                </footer>`;
    }

    // function to return the HTML for the header
    function getHeader() {
        return `<header class="mb-3">
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
            <img src="assets/media/dollar/Company_logo.png" alt="Company Logo1">
            <div class="sline"></div>
        </div>
    </header>`;
    }
        // Send data to print page
        var printWindow = window.frames["print_frame"];
        printWindow.document.open();
        printWindow.document.write(`
            <!DOCTYPE html>
            <html dir="rtl">
            <head>
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

        .header-line {
            align-items: center;
            display: flex;
        }

        .header img {
            left: 20%;
            position: absolute;
            top: 0px;
            width: 150px;
            height: 150px;
            position: fixed;
        }

        .footer {
            position: fixed;
            bottom: 0;
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
            margin-top: 20px;
            position: fixed;
            left: 0%;
            top: 110px;
        }

        .footerline {
            height: 5px;
            width: 100%;
            background: linear-gradient(to right, #ffcc32 33.3%, #252525 33.3%);
        }
        .new-page {
            page-break-before: always;
        }
    </style>
</head>
            <body>
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
                <main class="container">
                    <div class="text-center">
                        <h1>كشف سندات صرف / قبض</h1>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <h2>${fromDate} - ${toDate}</h2>
                        </div>
                        <div class="col-auto">
                        <h2 id="date">التاريخ : ${new Date().toLocaleDateString('en-GB', {day: '2-digit', month: '2-digit', year: 'numeric'})}</h2>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                                                    <h2>${balances}</h2>
                        </div>
                    </div>
                    <hr> <!-- This is the line -->

                    <div class="row">
                <div class="col-12">
                    <div>
                        <table class="table" id="payment_table">
                            <tbody id="payment_tbody">
                                ${paymentsHTML}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
                </main>
                <footer class="mt-auto">
        <div class="footer py-2">
            <p class="footerline w-100">
            <div class="footer-content d-flex justify-content-between align-items-center px-4">
                <p class="mb-0 fs-4">غزة-مفترق الجلاء الصفطاوي - الشارع العام</p>
                <p class="contact-info mb-0 fs-6">رقم تواصل: جوال واتس : 0598201000  - وطنية واتس : 0567201000</p>
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
        `);
        printWindow.document.close();
        printWindow.onload = function() {
            setTimeout(function() {
                printWindow.print();
            }, 1000);
        };
        

    }

