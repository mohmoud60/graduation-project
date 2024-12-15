function printData() {
    // Fetch data from the page
    var full_name = document.getElementById("full_name").innerText;
    var fromDate = document.getElementById("from_date").value;
    var toDate = document.getElementById("to_date").value;
    var balances = Array.from(document.querySelectorAll("#balunce"))
        .map(element => element.innerHTML)
        .join('<br>');
    var payments = Array.from(document.querySelectorAll("#kt_table_traders_payment tr"))
        .map(element => element.outerHTML);
    
    // Fetch data from the new table
    var newTableDataRows = Array.from(document.querySelectorAll("#kt_table_traders_show tr"));
    var newTableData = newTableDataRows.length > 0 ? newTableDataRows.map(element => element.outerHTML).join('') : '';
    
    // Create newTableSection only if there's data
    var newTableSection = '';
if (newTableDataRows.length > 1) { // assuming first row is always the header
    newTableSection = `
    <hr> <!-- This is the line -->
        <div class="row">
        <div class="col-xs-12 text-right"> <!-- Use 'text-end' to align the text to the right -->
            <h2>خلال الفترة: ${fromDate} - ${toDate}</h2>
        </div>
    </div>
        <div class="row">
            <div class="col-xs-12">
                <div>
                    <table class="table" id="kt_table_traders_show">
                        <tbody id="traders_show_tbody">
                        ${newTableData} 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}
    var paymentsHTML = '';
    // Fetch table headers
    var tableHeaders = document.querySelector("#kt_table_traders_payment tr").outerHTML;

    var paymentsPerPageFirstPage = 20;  // How many payments you want per the first page
    var paymentsPerPageOtherPages = 23;  // How many payments you want per other pages

    for (let i = 0; i < payments.length; i++) {
        if (i != 0 && (i == paymentsPerPageFirstPage || (i > paymentsPerPageFirstPage && (i - paymentsPerPageFirstPage) % paymentsPerPageOtherPages === 0))) {
            paymentsHTML += `</tbody></table></div><div class="new-page" style="page-break-inside: avoid;">${getHeader()}<table class="table" id="payment_table"><tbody id="payment_tbody">${tableHeaders}`;
		}
        paymentsHTML += payments[i];
    }


    function getFooter() {
        return `<footer class="mt-auto">
                    <div class="footer py-2">
                        <div class="footerline w-100"></div>
                        <div class="footer-content d-flex justify-content-between align-items-center px-4">
                            <p class="mb-0 fs-4">HK7 Group</p>
                            <p class="contact-info mb-0 fs-6">WhatsApp : +972597649797 - +972592444474  </p>
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
                <h2 style="color: #252525; font-size: 32px; margin-right: 10px;">HK7 GROUP</h2>
            </div>
            <div class="header-line">
                <div class="pline"></div>
                <h2 style="color: #ffcc32; font-size: 26px; margin-right: 10px;">Online Payment Service</h2>
            </div>
            <div class="line"></div>
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
            width: 100%;
            background: linear-gradient(to right, #ffcc32 35%, #252525 65%);
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
            background: linear-gradient(to right, #ffcc32 35%, #252525 65%);
        }

    .container, .row, .col-12 {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Ensure the table takes full width and adjusts columns based on content */
    .table-responsive {
        width: 100%;
        margin: 0;
        padding: 0;
    }

    /* Use auto layout to adjust column widths according to content */
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto; /* This makes columns adjust based on content */
    }

    th, td {
        text-align: center;
        padding: 8px;
        word-wrap: break-word; /* Ensures long text wraps instead of overflowing */
        white-space: nowrap;   /* Optional: Prevent text from breaking into multiple lines */
    }


    header, footer {
        width: 100%;
        padding: 0;
        margin: 0;
    }

    .new-page {
        page-break-before: always;
        page-break-inside: avoid;
    }
	
	
    </style>
</head>
            <body>
            <header>
            <div class="header py-3">
                <div class="header-line">
                    <div class="cline"></div>
                    <h2 style="color: #252525; font-size: 32px; margin-right: 10px;">HK7 GROUP</h2>
                </div>
                <div class="header-line">
                    <div class="pline"></div>
                    <h2 style="color: #ffcc32; font-size: 26px; margin-right: 10px;">Online Payment Service</h2>
                </div>
                <div class="line"></div>

            </div>
        </header>
                <main class="container-fluid">
                    <div class="text-center">
                        <h1>كشف حساب التاجر </h1>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <h2 class="text-dark" id="trader_name">${full_name}</h2>
                        </div>
                        <div class="col-auto">
                            <h2>${fromDate} - ${toDate}</h2>
                        </div>
                        <div class="col-auto">
                        <h2 id="date">التاريخ : ${new Date().toLocaleDateString('en-GB', {day: '2-digit', month: '2-digit', year: 'numeric'})}</h2>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h2>الرصيد الحالي:</h2>
                            <h2>${balances}</h2>
                        </div>
                    </div>
                    <hr> <!-- This is the line -->

                    <div class="row">
                    <div class="table-responsive">
                        <table cclass="table table-striped table-bordered" id="payment_table">
                            <tbody id="payment_tbody">
                                ${paymentsHTML}
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        ${newTableSection}
        
                </main>
                <footer class="mt-auto">
        <div class="footer py-2">
            <p class="footerline w-100">
            <div class="footer-content d-flex justify-content-between align-items-center px-4">
                <p class="mb-0 fs-4">HK7 Group</p>
                <p class="contact-info mb-0 fs-6">WhatsApp : +972597649797 - +972592444474  </p>
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

