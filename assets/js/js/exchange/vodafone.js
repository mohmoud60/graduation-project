const quantityInput = document.getElementById('sell-quantity');
const exchangeRateInput = document.getElementById('sell-exchange');
const totalDisplay = document.getElementById('sell-buy-total');
const currencySymbolDisplay = document.getElementById('sell-buy-total-symbol');
const EGPBtn = document.getElementById('EGP-btn');
const USDBtn = document.getElementById('USD-btn');
let currency_ex = 'EGP-VOD'; // Setting the value statically

EGPBtn.addEventListener('click', () => {
    EGPBtn.classList.add('selected', 'btn-success');
    EGPBtn.classList.remove('btn-dark');
    USDBtn.classList.remove('selected', 'btn-success');
    USDBtn.classList.add('btn-dark');
    calculateTotalEGP();
});

USDBtn.addEventListener('click', () => {
    USDBtn.classList.add('selected', 'btn-success');
    USDBtn.classList.remove('btn-dark');
    EGPBtn.classList.remove('selected', 'btn-success');
    EGPBtn.classList.add('btn-dark');
    calculateTotalUSD();
});


const currencySymbols = {
    'EGP-VOD': 'ج.م',
    'USD': '$',
};

quantityInput.addEventListener('input', updateTotal);
exchangeRateInput.addEventListener('input', updateTotal);

fetchExchangeRate();

function fetchExchangeRate() {
    fetch(`assets/php/process_currency.php?action=vodafone_cash`)
        .then(response => response.json())
        .then(data => {
            exchangeRateInput.value = String(data.vodafone_cash_price);
            $("#exupdate-buy-rate").val(data.vodafone_cash_price);
            updateTotal();
        })
        .catch(console.error);
}

function calculateTotalEGP() {
  const quantity = parseFloat(quantityInput.value);
  const exchangeRate = parseFloat(exchangeRateInput.value);
  if (isNumeric(quantity) && isNumeric(exchangeRate)) {
    totalDisplay.value = (quantity / exchangeRate).toFixed(2);
    currencySymbolDisplay.textContent = currencySymbols['USD'];
  } else {
    totalDisplay.value = "";
    currencySymbolDisplay.textContent = "";
  }
}

function calculateTotalUSD() {
  const quantity = parseFloat(quantityInput.value);
  const exchangeRate = parseFloat(exchangeRateInput.value);
  if (isNumeric(quantity) && isNumeric(exchangeRate)) {
    totalDisplay.value = (quantity * exchangeRate).toFixed(2);
    currencySymbolDisplay.textContent = currencySymbols['EGP-VOD'];
  } else {
    totalDisplay.value = "";
    currencySymbolDisplay.textContent = "";
  }
}

function isNumeric(value) {
  return /^\d+(\.\d{1,5})?$/.test(value);
}

function updateTotal() {
    if (EGPBtn.classList.contains('selected')) {
        calculateTotalEGP();
    } else if (USDBtn.classList.contains('selected')) {
        calculateTotalUSD();
    }
}

setCurrencySymbol();

function setCurrencySymbol() {
    const symbol = currencySymbols[currency_ex];
    currencySymbolDisplay.textContent = symbol;
}

const exupdateModal = new bootstrap.Modal(document.getElementById('exupdateModal'), {
    keyboard: false
});

const updateBtn = document.getElementById("exupdate-button");

$("#exupdate-button").on("click", function (event) {
    event.preventDefault();
    const buy_rate = $("#exupdate-buy-rate").val();
    $.ajax({
      url: "assets/php/process_currency.php?action=update_vodafone_cash",
      type: "POST",
      dataType: "json",
      data: {
        vodafone_cash_price: buy_rate,},
      success: function (response) {
          if (response.success) {
              Swal.fire({
                  icon: 'success',
                  title: 'نجاح',
                  text: response.success,
                  confirmButtonText: 'تأكيد'
              });
              exupdateModal.hide();
              fetchExchangeRate(); // Refresh exchange rate after update
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

$('#exupdateModal').on('show.bs.modal', function (event) {
    fetchExchangeRate();
});
/////////
document.addEventListener('input', function() {
    var vodafonePriceInput = document.getElementById('vodafone_price');
    var ustdPressInput = document.getElementById('ustd_press');
    var totalOutput = document.getElementById('sell-buy-total1');
    var totalSymbolOutput = document.getElementById('sell-buy-total-symbol1');
  
    var vodafonePrice = parseFloat(vodafonePriceInput.value);
    var ustdPress = parseFloat(ustdPressInput.value);
  
    if (!isNaN(vodafonePrice) && !isNaN(ustdPress)) {
      var result = vodafonePrice / (1 + ustdPress / 100);
      totalOutput.value = result.toFixed(2);
      totalSymbolOutput.textContent = "ج.م";
    } else {
      totalOutput.value = "";
      totalSymbolOutput.textContent = "";
    }
  });
  