
//////////////

const elements = {
  sellBuyFrom: document.getElementById("sell-buy-from"),
  sellQuantity: document.getElementById("sell-quantity"),
  sellExchange: document.getElementById("sell-exchange"),
  sellBuyTotal: document.getElementById("sell-buy-total"),
  sellBtn: document.getElementById("sell-btn"),
  buyBtn: document.getElementById("buy-btn"),
  addButton: document.getElementById("add-btn"),
  transactionsTable: document.getElementById("transactions-table")
};
let operationType = null;
let currencySymbolForQuantity, currencySymbolForTotal , first_account, second_account;
let selectedOperationButton = null;


// Event Listeners
elements.sellBuyFrom.addEventListener("change", updateExchangeRate);
elements.sellExchange.addEventListener("input", calculateTotal);
elements.sellQuantity.addEventListener("input", calculateTotal);
elements.sellBtn.addEventListener("click", event => setOperationType(event, "بيع"));
elements.buyBtn.addEventListener("click", event => setOperationType(event, "شراء"));
elements.addButton.addEventListener("click", addTransaction);

elements.transactionsTable.tBodies[0].addEventListener("click", function(event) {
  if (event.target.classList.contains("delete-icon")) {
      const row = event.target.closest("tr");
      row.remove();
      updateRowNumbers();
  }
});

function hideCurrencyButtons() {
  const firstCurrencyBtn = document.getElementById('firstCurrencyBtn');
  const secondCurrencyBtn = document.getElementById('secondCurrencyBtn');
  
  firstCurrencyBtn.classList.add('d-none');
  secondCurrencyBtn.classList.add('d-none');
}



function setOperationType(event, type) {
  event.preventDefault();
  operationType = type;

  elements.sellQuantity.disabled = false;
  elements.sellExchange.disabled = false;

  toggleButtons(type);
  updateExchangeRate();
  
  if (type === "بيع") {
    updateCurrencyButtons();
  } else if (type === "شراء") {
    hideCurrencyButtons();
    resetButtonColors(); // إذا كنت تريد إعادة الألوان إلى الافتراضية أيضًا
  }
}



function toggleButtons(type) {
  if (type === null) {
    elements.sellBtn.classList.remove('btn-success');
    elements.sellBtn.classList.add('btn-dark');
    elements.buyBtn.classList.remove('btn-success');
    elements.buyBtn.classList.add('btn-dark');
    return;
  }
  
  const [activeButton, inactiveButton] = type === "بيع"
      ? [elements.sellBtn, elements.buyBtn]
      : [elements.buyBtn, elements.sellBtn];

  activeButton.classList.replace('btn-dark', 'btn-success');
  inactiveButton.classList.replace('btn-success', 'btn-dark');
}



function resetButtonColors() {
  const firstCurrencyBtn = document.getElementById('firstCurrencyBtn');
  const secondCurrencyBtn = document.getElementById('secondCurrencyBtn');
  
  firstCurrencyBtn.classList.remove('btn-success');
  firstCurrencyBtn.classList.add('btn-dark');

  secondCurrencyBtn.classList.remove('btn-success');
  secondCurrencyBtn.classList.add('btn-dark');
}


function updateCurrencyButtons() {
  const selectedCurrencyValue = elements.sellBuyFrom.value;

  const [firstCurrency, secondCurrency] = selectedCurrencyValue.split('-').map(s => s.trim());

  const firstCurrencyBtn = document.getElementById('firstCurrencyBtn');
  const secondCurrencyBtn = document.getElementById('secondCurrencyBtn');

  firstCurrencyBtn.textContent = firstCurrency;
  firstCurrencyBtn.classList.remove('d-none');
  firstCurrencyBtn.classList.add('d-inline-block');

  secondCurrencyBtn.textContent = secondCurrency;
  secondCurrencyBtn.classList.remove('d-none');
  secondCurrencyBtn.classList.add('d-inline-block');

  firstCurrencyBtn.addEventListener('click', () => {
    resetButtonColors();
    selectedOperationButton = firstCurrencyBtn;
    firstCurrencyBtn.classList.remove('btn-dark');
    firstCurrencyBtn.classList.add('btn-success');
    calculateTotal();
  });

  secondCurrencyBtn.addEventListener('click', () => {
    resetButtonColors();
    selectedOperationButton = secondCurrencyBtn;
    secondCurrencyBtn.classList.remove('btn-dark');
    secondCurrencyBtn.classList.add('btn-success');
    calculateTotal();
  });
}


function updateExchangeRate() {
  const selectedCurrencyName = elements.sellBuyFrom.selectedOptions[0].getAttribute("name");

  if (selectedCurrencyName && operationType) {
      const transactionType = operationType === "شراء" ? "buy" : "sell";
      fetch(`assets/php/process_currency.php?action=fetch_exchange_rate&currency_ex=${selectedCurrencyName}`)
          .then(response => response.json())
          .then(data => {
              const rate = transactionType === "buy" ? data.buy_rate : data.sell_rate;
              elements.sellExchange.value = rate;
              first_account = data.first_account;
              second_account = data.second_account;
              calculateTotal();
          })
          .catch(error => {
              console.error("Error fetching exchange rate:", error);
          });
  }
}


function calculateTotal() {
  let total;
  let currentFirstAccount = first_account;
  let currentSecondAccount = second_account;

  if (selectedOperationButton === document.getElementById('firstCurrencyBtn')) {
      total = elements.sellQuantity.value * elements.sellExchange.value;
      
      
      if (operationType === "بيع") {
          currentFirstAccount = second_account;
          currentSecondAccount = first_account;
      }
  } else if (selectedOperationButton === document.getElementById('secondCurrencyBtn')) {
      total = elements.sellQuantity.value / elements.sellExchange.value;
  } else {
      if (operationType === "شراء") {
          total = elements.sellQuantity.value * elements.sellExchange.value;
      } else {
          total = 0;
      }
  }

  currencySymbolForQuantity = operationType === "بيع" ? currentSecondAccount : currentFirstAccount;
  currencySymbolForTotal = operationType === "بيع" ? currentFirstAccount : currentSecondAccount;
  
  document.getElementById("sell-buy-total-symbol").textContent = currencySymbolForTotal;
  elements.sellBuyTotal.value = total.toFixed(2);
}


function showAlert(type, title, message) {
  Swal.fire({
      icon: type,
      title: title,
      text: message,
      confirmButtonText: 'تأكيد'
  });
}

function addTransaction(event) {
  event.preventDefault();

  elements.sellQuantity.disabled = true;
  elements.sellExchange.disabled = true;

  if (!operationType) {
      showAlert('warning', 'تنبيه', 'الرجاء اختيار نوع العملية (بيع أو شراء)');
      return;
  }

  if (!elements.sellBuyFrom.value || !elements.sellQuantity.value || !elements.sellExchange.value || !elements.sellBuyTotal.value) {
      showAlert('warning', 'تنبيه', 'الرجاء تعبئة جميع الحقول المطلوبة');
      return;
  }

  toggleButtons(null); // Reset buttons to default
  hideCurrencyButtons(); // Hide the additional buttons
  resetButtonColors(); // Reset the colors of the buttons
  addToTable();
  clearInputs();
  operationType = null;
}


function addToTable() {
  const row = document.createElement("tr");
  
  let isSwapNeeded = operationType === "بيع" && selectedOperationButton === document.getElementById('firstCurrencyBtn');
  
  const cellsData = isSwapNeeded ? [ 
      { text: elements.transactionsTable.tBodies[0].rows.length + 1 },
      { text: elements.sellBuyFrom.value },
      { text: `${elements.sellBuyTotal.value} ${currencySymbolForTotal}` },
      { text: elements.sellExchange.value },
      { text: `${elements.sellQuantity.value} ${currencySymbolForQuantity}` },
      { text: operationType },
  ] : [
      { text: elements.transactionsTable.tBodies[0].rows.length + 1 },
      { text: elements.sellBuyFrom.value },
      { text: `${elements.sellQuantity.value} ${currencySymbolForQuantity}` },
      { text: elements.sellExchange.value },
      { text: `${elements.sellBuyTotal.value} ${currencySymbolForTotal}` },
      { text: operationType },
  ];

  for (const cellData of cellsData) {
      const cell = document.createElement("td");
      cell.textContent = cellData.text;
      row.appendChild(cell);
  }

  // Create a new cell for the delete icon
  const deleteCell = document.createElement("td");
  
  // Create the delete icon
  const deleteIcon = document.createElement("i");
  deleteIcon.classList.add("bi-dash", "text-danger", "delete-icon");

  deleteCell.appendChild(deleteIcon);
  row.appendChild(deleteCell);

  elements.transactionsTable.tBodies[0].appendChild(row);
}
function addToTable() {
  const row = document.createElement("tr");

  let isSwapNeeded = operationType === "بيع" && selectedOperationButton === document.getElementById('firstCurrencyBtn');

  const selectedOption = elements.sellBuyFrom.selectedOptions[0];
  const selectedName = selectedOption.getAttribute('name');  // جلب قيمة الخاصية name

  const cellsData = isSwapNeeded ? [ 
      { text: elements.transactionsTable.tBodies[0].rows.length + 1 },
      { text: elements.sellBuyFrom.value },
      { text: `${elements.sellBuyTotal.value} ${currencySymbolForTotal}` },
      { text: elements.sellExchange.value },
      { text: `${elements.sellQuantity.value} ${currencySymbolForQuantity}` },
      { text: operationType },
  ] : [
      { text: elements.transactionsTable.tBodies[0].rows.length + 1 },
      { text: elements.sellBuyFrom.value },
      { text: `${elements.sellQuantity.value} ${currencySymbolForQuantity}` },
      { text: elements.sellExchange.value },
      { text: `${elements.sellBuyTotal.value} ${currencySymbolForTotal}` },
      { text: operationType },
  ];

  for (const cellData of cellsData) {
      const cell = document.createElement("td");
      cell.textContent = cellData.text;
      row.appendChild(cell);
  }

  // Create a new cell for the name attribute (hidden column)
  const nameCell = document.createElement("td");
  nameCell.textContent = selectedName;
  nameCell.style.display = 'none';  // إخفاء العمود
  row.appendChild(nameCell);

  // Create a new cell for the delete icon
  const deleteCell = document.createElement("td");
  
  // Create the delete icon
  const deleteIcon = document.createElement("i");
  deleteIcon.classList.add("bi-dash", "text-danger", "delete-icon");

  deleteCell.appendChild(deleteIcon);
  row.appendChild(deleteCell);

  elements.transactionsTable.tBodies[0].appendChild(row);
}





function clearInputs() {
  elements.sellQuantity.value = "";
  elements.sellExchange.value = "";
  elements.sellBuyTotal.value = "";
  document.getElementById("sell-buy-total-symbol").textContent = "";  // إفراغ قيمة الرمز
}


function updateRowNumbers() {
  let rows = elements.transactionsTable.tBodies[0].rows;
  for (let i = 0; i < rows.length; i++) {
      rows[i].cells[0].textContent = i + 1;
  }
}




///////////////////////////////////////

   /* */
   const transactionsTable = document.getElementById("transactions-table");
   const printBtn = document.getElementById("print-btn");
  
  const username = document.getElementById('username').dataset.username;
  function extractSymbolFromText(text) {
    const parts = text.trim().split(" ");
    return parts[parts.length - 1]; // العنصر الأخير في المصفوفة هو الرمز
}
  function getTransactionData() {
    const transactions = [];
    const rows = transactionsTable.tBodies[0].rows;

    for (const row of rows) {
      const transaction = {
        type: row.cells[5].textContent === "بيع" ? "sell" : "buy",
        currency_ex: row.cells[6].textContent,
        original_currency_ex: row.cells[1].textContent,
        quantity: parseFloat(row.cells[2].textContent),
        quantitySymbol: extractSymbolFromText(row.cells[2].textContent),
        exchange_rate: parseFloat(row.cells[3].textContent),
        total: parseFloat(row.cells[4].textContent),
        totalSymbol: extractSymbolFromText(row.cells[4].textContent),
        created_by: username,
    };
  
      if (document.getElementById("ID").value) {
        transaction.customer_id = document.getElementById("ID").value;
      }
  
      transactions.push(transaction);
    }
  
    return transactions;
  }

function clearSellingSectionInputs() {
    sellBuyFrom.selectedIndex = 0;
    sellQuantity.value = "";
    sellExchange.value = "";
    sellBuyTotal.value = "";
    operationType = null;
}

async function getCompanyInfo() {
  const response = await fetch('assets/php/process_currency.php?action=company_info');
  
  if (!response.ok) {
      console.log('Error fetching company info');
      return;
  }

  const companyInfo = await response.json();
  return companyInfo;
}

async function saveAndPrintTransactions() {


  const transactions = getTransactionData();
  
  const response = await fetch("assets/php/process_currency.php?action=save_transactions", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(transactions)
  });

  if (!response.ok) {
    Swal.fire({
        icon: 'error',
        title: 'خطأ',
        text: ' عدم اتصال بالسيرفر حدث خطأ أثناء حفظ البيانات.',
        confirmButtonText: 'تأكيد'
    });
    return;
}

  const companyInfo = await getCompanyInfo();
  const responseJson = await response.json();
  const orderId = responseJson.order_id;
  const printFrame = document.getElementById("print-frame");
  const printWindow = printFrame.contentWindow;
  const customerID = document.getElementById("ID").value;
  const customername = document.getElementById("customer_name").value;
  const createdBy = username;
  const date = new Date();
  const dateString = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;
  let hours = date.getHours();
  const minutes = String(date.getMinutes()).padStart(2, "0");
  const seconds = String(date.getSeconds()).padStart(2, "0");
  const amPm = hours >= 12 ? "PM" : "AM";
  hours = hours % 12;
  hours = hours ? hours : 12;
  const timeString = `${hours}:${minutes}:${seconds} ${amPm}`;

  
  const createReceiptRow = (transaction, index) => {
    return `
        <tr>
            <td>${index + 1}</td>
            <td>${transaction.original_currency_ex}</td>
            <td>${transaction.quantitySymbol}${transaction.quantity}</td>
            <td>${transaction.exchange_rate}</td>
            <td>${transaction.totalSymbol}${transaction.total}</td>
        </tr>
    `;
};


  const receiptRows = transactions.map(createReceiptRow).join("");

  printWindow.document.write(`
  <!DOCTYPE html>
  <html lang="ar" dir="rtl">
  <head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Print Receipt</title>
<link rel="preload" href="assets/font/cairo-arabic.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/font/cairo-latin.woff2" as="font" type="font/woff2" crossorigin>

<style>
@font-face {
  font-family: 'Cairo';
  src: url('assets/font/cairo-arabic.woff2') format('woff2');
  }
  /* latin */
  @font-face {
  font-family: 'Cairo';
  src: url('assets/font/cairo-latin.woff2') format('woff2');
  }
  body {
    font-family: "Cairo", sans-serif; /* changed to Cairo */
    font-size: 10px;
    width: 300px;
  }

  .header {
display: flex;
justify-content: center;
align-items: center;
margin-bottom: 10px;
}

.header-content {
display: flex;
flex-direction: column;
justify-content: center;
align-items: center;

}

.date-time {
display: flex;
justify-content: space-between;
align-items: center;
}

.date-time h2 {
margin: 0;
}

.date-time span {
display: block;
}


  .logo {
    max-width: 100px;
    height: auto;
  }

  table {
      font-weight: bold;
    width: 100%;
    font-size: 14px;
    border-collapse: collapse;
  }

  th, td {
    font-family: Arial
      font-size: 14px;
    font-weight: bold;
    border: 1px solid black;
    padding: 4px;
    text-align: center;
  }

  .info {
    margin: 1px;
  }
  .print-time {
display: flex;
justify-content: space-between;
align-items: center;
}

.print-time h3 {
margin: 0;
}
.address{
  
  font-family: 'Cairo';
  font-size: 8px;
  margin: 1px;
  border: solid;
  border-width: 1px;
  border-radius: 20px;
}

.address h2{
  
  align-items: center;
}

  .whatsapp{
      display: flex;
justify-content: space-between;
align-items: center;
  }
</style>
</head>
<body>
<div class="header">
  <img src="assets/media/dollar/Company_logo.png" alt="Company Logo" class="logo">
  <div class="header-content">
  <h1 style="  font-family: 'Cairo';"></h1>
</div>
</div>
<div class="date-time">
<h2 name="Date">التاريخ:</h2>
<h2 name="time">الوقت:</h2>
</div>
<h2 name= "orderId" style="font-family: 'Cairo';">رقم الفاتورة:</h2>
<div class="info">
  
  <h2 name="customerName">الإسم:</h2>
</div>
<table>
  <thead>
    <tr>
      <th>م.</th>
      <th>نوع العملات التحويل	</th>
      <th>الكمية</th>
      <th>سعر الصرف</th>
      <th>الإجمالي</th>
    </tr>
  </thead>
  
  <tbody id="receipt-items">
    <!-- Receipt items will be added here -->
  </tbody>
</table>
<div>
<div class="print-time">
<h3 name="createdBy">طبعة : </h3>
<h3 name="ctime">الساعة :</h3>
</div>
</div>

<div class="whatsapp">
<h3>**  للمراجعة و الإستفسار إحضار الفاتورة ** </h3>
<span>
<h3>إنظم الينا على تطبيق WhatsApp</h3>
<img src="assets/media/dollar/Whatsapp_code.png" alt="Company Logo" class="logo">
</div>
<h3>**  الرجاء عد النقود قبل المغادرة** </h3>
<div class="address">
  <h2 ></h2> 
  <h2 ></h2>  
</div>
</body>
  </html>
  `);

  printWindow.document.querySelector(".header-content h1").innerText = companyInfo.companyName;
  printWindow.document.querySelector(".address h2:nth-child(1)").innerText = companyInfo.companyAddress;
  printWindow.document.querySelector(".address h2:nth-child(2)").innerText =  companyInfo.mobileNumber;
  printWindow.document.querySelector("[name='Date']").innerText += ` ${dateString}`;
  printWindow.document.querySelector("[name='time']").innerText += ` ${timeString}`;
  printWindow.document.querySelector("[name='orderId']").innerText += ` ${orderId}`;
  let lastTransaction = transactions[transactions.length - 1];
  let element = printWindow.document.querySelector("[name='customerName']");
  let transactionTypeText;
  
  switch (lastTransaction.type) {
      case 'buy':
          transactionTypeText = "فاتورة شراء";
          break;
      case 'sell':
          transactionTypeText = "فاتورة بيع";
          break;
      default:
          transactionTypeText = ""; // Or any default value you'd like
  }
  
  if(element) {
      if (!customername || customername.trim() === '') {
          element.innerText = `السيد: صندوق النقد / ${transactionTypeText}`;
      } else {
          element.innerText = `السيد: ${customername.trim()} / ${transactionTypeText}`;
      }
  }
  


  
    printWindow.document.querySelector("[name='createdBy']").innerText += ` ${createdBy}`;
  printWindow.document.querySelector("[name='ctime']").innerText += ` ${timeString}`;
  printWindow.document.querySelector("#receipt-items").innerHTML = receiptRows;

  printWindow.document.close();
  printWindow.onload = () => {
    printWindow.print();
    printWindow.close();
  };

  transactionsTable.tBodies[0].innerHTML = "";
  document.getElementById("ID").value = '';
  document.getElementById("customer_name").value = '';

}

document.addEventListener("keydown", (event) => {
  if (event.key === "F3") {
    event.preventDefault();
    saveAndPrintTransactions();
  }
});

printBtn.addEventListener("click", (event) => {
  event.preventDefault();
  saveAndPrintTransactions();
});
///////////////////////




function fetchAndDisplayData(params) {
    $.getJSON("assets/php/process_currency.php", params)
        .done(function(data) {
            let html = "";
            data.forEach((row, index) => {
                html += `<tr>
                    <td>${index + 1}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.order_id}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.currency_ex}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.quantity} ${row.quantitySymbol}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.exchange_rate}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.total} ${row.totalSymbol}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.type}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.time}</td>
                    <td class="text-gray-600 text-hover-primary fs-5 mb-1">${row.date}</td>
                    <td>
  <div class="btn-group">
    <button type="button" class="btn btn-light btn-active-light-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
      إجراءات
    </button>
    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
      <a class="dropdown-item print-row-btn" href="#" data-order-id="${row.order_id}">طباعة</a>
      <a class="dropdown-item delete-btn" href="#" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-order-id="${row.order_id}">حذف</a>
    </div>
  </div>
</td>


                </tr>`;
            });

            $("#returnTable tbody").html(html);
            $("#returnTable").removeClass("thidden");
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
              icon: 'error',
              title: 'خطأ',
              text: 'خطأ في جلب البيانات: ' + errorThrown,
              confirmButtonText: 'تأكيد'
          });
      });
      
}



$(document).ready(function() {
    $("#return-invoice-btn").on("click", function() {
        $("#returnModal").show();
    });

    $(".reclose-modal").on("click", function() {
      $("#invoiceNumber").val('');
      $("#returnModal").hide();
      $("#returnTable tbody").empty();
      $("#returnTable").addClass("thidden");
    });

    $("#searchInvoiceBtn").on("click", function() {
      const invoiceDigits = $("#invoiceNumber").val();
      const invoiceNumber = "O-" + invoiceDigits;
        
      fetchAndDisplayData({ action: 'fetch_data', invoice_number: invoiceNumber });
    });
});
$("#returnTable").on("click", ".delete-btn", function() {
  const orderId = $(this).data("orderId");
  $('#deleteConfirmModal').data('order-id', orderId);
  $('#deleteConfirmModal').modal('show');
});

$("#deleteConfirmModal").on('show.bs.modal', function(e) {
  const orderId = $(e.relatedTarget).data('order-id');
  $(e.currentTarget).find('.confirm-delete-btn').data('order-id', orderId);
});

$("#returnModal").on("hide.bs.modal", function() {
    $("#returnTable tbody").empty();
    $("#returnTable").addClass("thidden");
});


$("#deleteConfirmModal").on("click", ".confirm-delete-btn", function() {
  const orderId = $(this).data('order-id');
  const deleteReason = $("#deleteReason").val();

  if (deleteReason.trim() === '') {
      Swal.fire({
          icon: 'warning',
          title: 'تحذير',
          text: 'يجب تعبئة سبب الحذف.',
          confirmButtonText: 'تأكيد'
      });
      return;
  }

  Swal.fire({
      title: 'هل أنت متأكد أنك تريد حذف هذا السجل؟',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'نعم، احذفه!',
      cancelButtonText: 'لا، ألغي!',
  }).then((result) => {
      if (result.isConfirmed) {
        $('#deleteConfirmModal').modal('hide');
        $.post("assets/php/process_currency.php?action=fetch_data_remove", { order_id: orderId, description: deleteReason })
        .done(function(data) {
              Swal.fire({
                  icon: 'success',
                  title: 'تم',
                  text: 'تم حذف السجل بنجاح',
                  confirmButtonText: 'تأكيد'
              });
              $("#invoiceNumber").val('');
              $("#returnModal").hide();
              $("#returnTable tbody").empty();
              $("#returnTable").addClass("thidden");
          })
          .fail(function(jqXHR, textStatus, errorThrown) {
              Swal.fire({
                  icon: 'error',
                  title: 'خطأ',
                  text: 'خطأ في حذف البيانات: ' + errorThrown,
                  confirmButtonText: 'تأكيد'
              });
          });
      }
  });
});





$("#showLastEntriesBtn").on("click", function() {
    const numberOfEntries = $("#selectNumberOfEntries").val();
    fetchAndDisplayData({ action: 'fetch_data', last_n: numberOfEntries });
});



async function printRowData(data) {
  const companyInfo = await getCompanyInfo();
const parsedRowData = $(data);
const columns = parsedRowData.find("td");
const index = data.index;
const orderId = data.orderId;
const currencyEx = data.currencyEx;
const quantity = data.quantity;
const exchangeRate = data.exchangeRate;
const total = data.total;
const type = data.type;
const time = data.time;
const date = data.date;
const timeString = new Date().toLocaleTimeString();
const createdBy = username;

  const receiptRows = `
    <tr>
      <td>${index}</td>
      <td>${currencyEx}</td>
      <td>${quantity}</td>
      <td>${exchangeRate}</td>
      <td>${total}</td>
    </tr>`;

  let printIframe = document.createElement('iframe');
  printIframe.style.display = 'none';
  document.body.appendChild(printIframe);

  let printDocument = printIframe.contentDocument;
  printDocument.open();
  printDocument.write(`
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Receipt</title>
  <link rel="preload" href="assets/font/cairo-arabic.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="assets/font/cairo-latin.woff2" as="font" type="font/woff2" crossorigin>

<style>
@font-face {
  font-family: 'Cairo';
  src: url('assets/font/cairo-arabic.woff2') format('woff2');
  }
  /* latin */
  @font-face {
  font-family: 'Cairo';
  src: url('assets/font/cairo-latin.woff2') format('woff2');
  }
  body {
    font-family: "Cairo", sans-serif; /* changed to Cairo */
    font-size: 12px;
    width: 300px;
  }

    .header {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 10px;
}

.header-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
 
}

.date-time {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.date-time h2 {
  margin: 0;
}

.date-time span {
  display: block;
}


    .logo {
      max-width: 100px;
      height: auto;
    }

    table {
        font-weight: bold;
      width: 100%;
      font-size: 14px;
      border-collapse: collapse;
    }

    th, td {
      font-family: Arial
        font-size: 16px;
      font-weight: bold;
      border: 1px solid black;
      padding: 4px;
      text-align: center;
    }

    .info {
      margin: 1px;
    }
    .print-time {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.print-time h3 {
  margin: 0;
}
.address{
    
    font-family: 'Cairo';
    font-size: 8px;
    margin: 1px;
    border: solid;
    border-width: 1px;
    border-radius: 20px;
}

.address h2{
    
    align-items: center;
}

    .whatsapp{
        display: flex;
  justify-content: space-between;
  align-items: center;
    }
  </style>
</head>
<body>
  <div class="header">
    <img src="assets/media/dollar/Company_logo.png" alt="Company Logo" class="logo">
    <div class="header-content">
    <h1></h1>
  </div>
  </div>
<div class="date-time">
<h2 name="Date">التاريخ:</h2>
<h2 name="time">الوقت:</h2>
</div>
<h2 name= "orderId" style="font-family: 'Cairo';">رقم الفاتورة:</h2>
  <div class="info">
    
    <h2 name="customerID">الإسم:</h2>
  </div>
  <table>
    <thead>
      <tr>
        <th>م.</th>
        <th>نوع العملات التحويل	</th>
        <th>الكمية</th>
        <th>سعر الصرف</th>
        <th>الإجمالي</th>
      </tr>
    </thead>
    
    <tbody id="receipt-items">
      <!-- Receipt items will be added here -->
    </tbody>
  </table>
  <div>
  <div class="print-time">
  <h3 name="createdBy">طبعة : </h3>
  <h3 name="ctime">الساعة :</h3>
  </div>
</div>

<div class="whatsapp">
<h3>**  للمراجعة و الإستفسار إحضار الفاتورة ** </h3>
<span>
<h3>إنظم الينا على تطبيق WhatsApp</h3>
<img src="assets/media/dollar/Whatsapp_code.png" alt="Company Logo" class="logo">
</div>
<h3>**  الرجاء عد النقود قبل المغادرة** </h3>
<div class="address">
    <h2 ></h2> 
    <h2 ></h2>  
</div>
</body>
    </html>
  `);
  printDocument.close();
  printDocument.querySelector(".header-content h1").innerText = companyInfo.companyName;
  printDocument.querySelector(".address h2:nth-child(1)").innerText = companyInfo.companyAddress;
  printDocument.querySelector(".address h2:nth-child(2)").innerText =  companyInfo.mobileNumber;
  printDocument.querySelector("[name='Date']").innerText += ` ${date}`;
  printDocument.querySelector("[name='time']").innerText += ` ${time}`;
  printDocument.querySelector("[name='orderId']").innerText += ` ${orderId}`;
  printDocument.querySelector("[name='createdBy']").innerText += ` ${createdBy}`;
  printDocument.querySelector("[name='ctime']").innerText += ` ${timeString}`;
  printDocument.querySelector("#receipt-items").innerHTML = receiptRows;

  setTimeout(function() {
    printIframe.contentWindow.print();
    document.body.removeChild(printIframe);
  }, 100); // تأخير 2000 مللي ثانية (2 ثانية)
}

$("#returnTable").on("click", ".print-row-btn", function() {
  const rowElement = $(this).closest('tr');
  const rowData = {
    index: rowElement.find("td:eq(0)").text(),
    orderId: rowElement.find("td:eq(1)").text(),
    currencyEx: rowElement.find("td:eq(2)").text(),
    quantity: rowElement.find("td:eq(3)").text(),
    exchangeRate: rowElement.find("td:eq(4)").text(),
    total: rowElement.find("td:eq(5)").text(),
    type: rowElement.find("td:eq(6)").text(),
    time: rowElement.find("td:eq(7)").text(),
    date: rowElement.find("td:eq(8)").text()
  };
  printRowData(rowData);
});


//////////////////////
// New way to trigger the modal
const exupdateModal = new bootstrap.Modal(document.getElementById('exupdateModal'), {
    keyboard: false
});


// Your update button
const updateBtn = document.getElementById("exupdate-button");

$("#exupdate-button").on("click", function (event) {
    event.preventDefault();
    const currency_ex = $("#exupdate-base").val();
    const buy_rate = $("#exupdate-buy-rate").val();
    const sell_rate = $("#exupdate-sell-rate").val();

    $.ajax({
      url: "assets/php/process_currency.php?action=update_exchange_rate",
      type: "POST",
      dataType: "json",
      data: {
          currency_ex: currency_ex,
          buy_rate: buy_rate,
          sell_rate: sell_rate,
      },
      success: function (response) {
          if (response.success) {
              Swal.fire({
                  icon: 'success',
                  title: 'نجاح',
                  text: response.success,
                  confirmButtonText: 'تأكيد'
              });
              exupdateModal.hide(); // Hide modal with Bootstrap method
          } else {
              Swal.fire({
                  icon: 'error',
                  title: 'خطأ',
                  text: response.error,
                  confirmButtonText: 'تأكيد'
              });
          }
      },
      error: function () {
          Swal.fire({
              icon: 'error',
              title: 'خطأ',
              text: 'حدث خطأ أثناء تحديث سعر الصرف.',
              confirmButtonText: 'تأكيد'
          });
      },
  });
  
});

function fetchExchangeRate(currency_ex) {
    $.ajax({
      url: "assets/php/process_currency.php",
      type: "GET",
      dataType: "json",
      data: {
        action : "fetch_exchange_rate",
        currency_ex: currency_ex
      },
      success: function (response) {
        if (response) {
          $("#exupdate-buy-rate").val(response.buy_rate);
          $("#exupdate-sell-rate").val(response.sell_rate);
        } else {
          Swal.fire({
              icon: 'error',
              title: 'خطأ',
              text: 'فشل في تحميل أسعار العملة.',
              confirmButtonText: 'تأكيد'
          });
      }
      },
      error: function () {
          Swal.fire({
              icon: 'error',
              title: 'خطأ',
              text: 'حدث خطأ أثناء تحميل أسعار العملة.',
              confirmButtonText: 'تأكيد'
          });
      },
      });
      }
      

$('#exupdateModal').on('show.bs.modal', function (event) {
    const selectedCurrency = $("#exupdate-base").val();
    fetchExchangeRate(selectedCurrency);
});
  
$("#exupdate-base").on("change", function () {
    const selectedCurrency = $(this).val();
    fetchExchangeRate(selectedCurrency);
});

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
  
    fetch('assets/php/process_currency.php?action=search_customers', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ query: searchQuery })
    })
    .then(response => response.json())
.then(data => {
  let html = "<table class='table'><thead><tr><th>رقم الزبون</th><th>اسم الزبون</th><th>رقم الجوال</th></tr></thead><tbody>";
  data.forEach(customer => {
    html += `
      <tr class="customer-row" data-id="${customer.customer_id}" data-name="${customer.full_name}">
        <td>${customer.customer_id}</td>
        <td>${customer.full_name}</td>
        <td>${customer.customer_phone}</td>
      </tr>
    `;
  });
  html += "</tbody></table>";
  document.getElementById('customerSearchResults').innerHTML = html;

  // Add event listener to table rows
  document.querySelectorAll('.customer-row').forEach(row => {
    row.addEventListener('click', function() {
      document.getElementById('ID').value = this.getAttribute('data-id');
      document.getElementById('customer_name').value = this.getAttribute('data-name');
      customerSearchModal.hide();
    });
  });
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
  
  ///////////////////
     
   /* fetch_customer_name */
   $(document).ready(function () {
    $("#ID").on("input", function () {
        const customerID = $(this).val();

        if (customerID.length > 0) {
            $.getJSON("assets/php/process_currency.php", { action: 'fetch_customer_name', customer_id: customerID }, function (response) {
                if (response.error) {
                    console.error(response.error);
                    $("#customer_name").val("");
                } else {
                    $("#customer_name").val(response.full_name);
                }
            });
        } else {
            $("#customer_name").val("");
        }
    });
});

$("#customer_name").on("input", function () {
  const fullName = $(this).val();
  if (fullName.trim() !== "") {
    $.getJSON("assets/php/process_currency.php", { action: 'fetch_customer_name', full_name: fullName }, function (data) {
      if (!data.error) {
        $("#ID").val(data.customer_id);
      } else {
        console.error(data.error);
        $("#ID").val("");
      }
    });
  } else {
    $("#ID").val("");
  }
});


   ///////////////////////////////////////