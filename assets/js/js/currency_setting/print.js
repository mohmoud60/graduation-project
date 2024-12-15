function printData() {
    var payments = Array.from(document.querySelectorAll("#mainTable tr"))
        .map((element, index) => {
            var cells = Array.from(element.querySelectorAll('td, th'));

            if (cells.length > 0) {
                cells.pop();
                cells.shift();
            }

            // إضافة عنوان العمود أو الرقم المتسلسل
         

            return `<tr>${cells.map(cell => cell.outerHTML).join('')}</tr>`;
        });
    
    var tradersHTML = '';

    var paymentsPerPageFirstPage = 18; 
    var paymentsPerPageOtherPages = 20;

    for (let i = 0; i < payments.length; i++) {
        if (i != 0 && (i == paymentsPerPageFirstPage || (i > paymentsPerPageFirstPage && (i - paymentsPerPageFirstPage) % paymentsPerPageOtherPages === 0))) {
            tradersHTML += `</tbody></table>${getFooter()}</div><div class="new-page">${getHeader()}<table class="table" id="payment_table"><tbody id="payment_tbody" class="text-dark">${tableHeaders}`;
        }
        tradersHTML += payments[i];
    }


    function getFooter() {
        return `<footer class="mt-auto">
                    <div class="footer py-2">
                        <div class="footerline w-100"></div>
                        <div class="footer-content d-flex justify-content-between align-items-center px-4">
                            <p class="mb-0 fs-4">غزة-مفترق الجلاء الصفطاوي - الشارع العام</p>
                            <p class="contact-info mb-0 fs-6">جوال واتس : 0598201000  - وطنية واتس : 0567201000</p>
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
                    <div class="text-center mt-8">
                        <h1>أسعار العملات</h1>
                    </div>
                    <div class="row">
                    <div class="col-auto">
                    <h2 id="date">التاريخ : ${new Date().toLocaleDateString('en-GB', {day: '2-digit', month: '2-digit', year: 'numeric'})}</h2>

                    </div>
                </div>
                    <div class="row">
                <div class="col-12">
                    <div>
                        <table class="table" id="payment_table">
                            <tbody id="payment_tbody">
                                ${tradersHTML}
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
                <p class="contact-info mb-0 fs-6"> جوال واتس : 0598201000  - وطنية واتس : 0567201000</p>
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

