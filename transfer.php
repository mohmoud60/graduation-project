<?php
$required_permission = 'permission_5';
include 'session_check.php';
include 'assets/php/connection.php';



$query = $conn->prepare("SELECT * FROM currency");
$query->execute();
$main_currencies = $query->fetchAll(PDO::FETCH_ASSOC);


// Function to fetch data
function fetchData($conn, $sql, $params = []) {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch next id
function fetchNextId($conn, $table, $idColumn) {
    $stmt = $conn->prepare("SELECT MAX($idColumn) as max_id FROM $table");
    $stmt->execute();
    $max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];
    $next_id = $max_id !== null ? $max_id + 1 : 1;
    return str_pad($next_id, 10, "0", STR_PAD_LEFT);
}


// Get funds
// Get funds
$sub_funds = fetchData($conn, "
    SELECT 
        a.account_number, 
        a.account_Sname, 
        c.currency_symbole, 
        c.currency_sname 
    FROM 
        accounting a 
    INNER JOIN 
        currency c 
    ON 
        a.currency_id = c.currency_id 
    WHERE 
        a.Delete_Date IS NULL AND
        a.account_type IN (?) 
    ORDER BY 
        a.account_number ASC, 
        a.account_Sname ASC", [3100]);

$main_funds = fetchData($conn, "
        SELECT 
            a.account_number, 
            a.account_Sname, 
            c.currency_symbole, 
            c.currency_sname 
        FROM 
            accounting a 
        INNER JOIN 
            currency c 
        ON 
            a.currency_id = c.currency_id 
        WHERE 
            a.Delete_Date IS NULL AND
            a.account_type IN (?) 
        ORDER BY 
            a.account_number ASC, 
            a.account_Sname ASC", [3000]);

$customers = fetchData($conn, "SELECT customer_id, full_name FROM customer WHERE Delete_Date IS NULL");


$income = fetchData($conn, "SELECT account_number, account_Sname FROM accounting WHERE Delete_Date IS NULL AND account_type= ?", [3200]);

// Get next transfer_id
$next_id = fetchNextId($conn, 'transfers', 'transfer_id');
?>


<!DOCTYPE html>
<html lang="en" direction="rtl" dir="rtl" style="direction: rtl">
	<!--begin::Head-->
    <?php include 'head.php'; ?>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
					<!--begin::Main-->
                    <div class="app-main flex-column flex-row" id="kt_app_main">
	<!--begin::Content wrapper-->
	<div class="d-flex flex-column flex-column-fluid">
		<!--begin::Toolbar-->
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<!--begin::Toolbar container-->
			<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
				<!--begin::Page title-->
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<!--begin::Title-->
					<!--begin::Underline-->
                    <span class="d-inline-block position-relative ms-2">
                        <!--begin::Label-->
                        <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">
                            تحويلات
                        </span>
                        <!--end::Label-->

                        <!--begin::Line-->
                        <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                        <!--end::Line-->
                    </span>
                    <!--end::Underline-->
					<!--end::Title-->
				</div>
				<!--end::Page title-->
				<!--begin::Actions-->
				<div class="d-flex align-items-center gap-2 gap-lg-3">
					<!--begin::Secondary button-->
                    <a class="btn btn-info" id="serch_customer">البحث عن رصيد تاجر / زبون</a>
                    <a class="btn fw-bold btn-info" data-bs-toggle="modal" data-bs-target="#recent_transfer_modal" data-permission="permission_22">عرض سجل التحويلات</a>
            

					<!--end::Secondary button-->
				</div>
				<!--end::Actions-->
			</div>
			<!--end::Toolbar container-->
		</div>
        <main class="container">
    <div class="row mt-4">

            <div class="card">
            <div class="card-header d-flex align-items-center justify-content-center border-bottom py-3">
                    <h2 class="fs-4 fw-bold m-0">تحويلات</h2>
                </div>                
                <div class="card-body">
                    <form id="service">


<!--begin::from_account-->
    <div class="row"  data-clonable-row>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="from_account" class="form-label">من حساب :</label>
                <select id="from_account" name="from_account[]"  class="form-select " data-control="select2" data-placeholder="حدد حساب">
                <option></option>
                    <?php
                   foreach($sub_funds as $row) {
                    echo "<option value='fund_" . $row["account_number"] . "' data-currency-symbol='" . $row["currency_symbole"] . "' data-currency-sname='" . $row["currency_sname"] . "' data-accounts-name='صناديق فرعية - '> " . $row["account_Sname"] . "</option>";
                }
                
                    foreach($customers as $row) {
                        echo "<option value='customer_" . $row["customer_id"] . "' data-accounts-name='حساب عملاء - زبائن -'  > " . $row["full_name"] . "</option>";
                    }
                    foreach($main_funds as $row) {
                        echo "<option value='fund_" . $row["account_number"] . "' data-currency-symbol='" . $row["currency_symbole"] . "' data-accounts-name='' data-currency-sname='" . $row["currency_sname"] . "'> صناديق رئيسية - " . $row["account_Sname"] . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="from_amount" class="form-label">مبلغ و قدره:</label>
                <input type="number" id="from_amount" name="from_amount[]" class="form-control " step="0.00001" pattern="\d+(\.\d{1,5})?">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="from_type" class="form-label">فئة الحساب </label>
                <select class="form-control" id="from_type" name="from_type[]" data-control="select2" data-placeholder="حدد فئة الحساب">
                    <option></option>
                    <option value="deposit"> إيداع - دائن </option>
                    <option value="withdraw">سحب - مدين </option>
                </select>
            </div>
        </div>
        <div class="col-md-2 mb-3" style="display: none;" id="from_currency">
    <label for="from_currency" class="form-label">العملة:</label>
    <select id="from_currency" name="from_currency[]" class="form-select" data-control="select2" data-placeholder="حدد الخيار">
    <option></option>
	<?php
    foreach ($main_currencies as $currency) {
        echo "<option value=\"{$currency['currency_id']}\" data-currency-symbol=\"{$currency['currency_symbole']}\" data-currency-sname=\"{$currency['currency_sname']}\">{$currency['currency_sname']}</option>";

    }
    ?>
    </select>
</div>
        <div class="col-md-1 d-flex align-items-center">
            <div class="mb-3">
            <button type="button" class="btn btn-icon btn-sm btn-light btn-icon-gray-400" data-btn-clone="add">
                    <i class="ki-duotone ki-plus fs-3x"></i>
                </button>
            </div>
        </div>
    </div>
<!--end::from_account-->
	<!--begin::to_account-->
<div class="row" data-clonable-row-to>
    <div class="col-md-3"  >
        <div class="mb-3">
            <label for="to_account" class="form-label">إلى حساب :</label>
            <select  id="to_account"  name="to_account[]" class="form-select" data-control="select2" data-placeholder="حدد حساب">
            <option></option>
                <?php
                foreach($sub_funds as $row) {
                    echo "<option value='fund_" . $row["account_number"] . "' >" . $row["account_Sname"] . "</option>";
                }
                foreach($customers as $row) {
                    echo "<option value='customer_" . $row["customer_id"] . "'>" . $row["full_name"] . "</option>";
                }
                foreach($main_funds as $row) {
                    echo "<option value='fund_" . $row["account_number"] . "'> صناديق رئيسية - " . $row["account_Sname"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-3" >
        <div class="mb-3 ">
            <label for="to_amount" class="form-label">مبلغ و قدره:</label>
            <input type="number" id="to_amount" name="to_amount[]" class="form-control " step="0.00001" pattern="\d+(\.\d{1,5})?">
        </div>
    </div>
    
    <div class="col-md-3" >
        <div class="mb-3">
            <label for="to_type" class="form-label">فئة الحساب</label>
            <select class="form-control" id="to_type" name="to_type[]" data-control="select2" data-placeholder="حدد فئة الحساب">
                <option></option>
                <option value="deposit"> إيداع - دائن </option>
                <option value="withdraw">سحب - مدين </option>
            </select>
        </div>
    </div>
    <div class="col-md-2 " style="display: none;" id="to_currency">
    <label for="to_currency" class="form-label">العملة:</label>
    <select id="to_currency" name="to_currency[]" class="form-select" data-control="select2" data-placeholder="حدد الخيار">
    <option></option>
	<?php
    foreach ($main_currencies as $currency) {
        echo "<option value=\"{$currency['currency_id']}\">{$currency['currency_sname']}</option>";
    }
    ?>
    </select>
</div>
    <div class="col-md-1 d-flex align-items-center">
        <div class="mb-3">
        <button type="button" data-btn-clone-to="add" class="btn btn-icon btn-sm btn-light btn-icon-gray-400">
                <i class="ki-duotone ki-plus fs-3x"></i>
            </button>
        </div>
    </div>
</div>
<!--end::to_account-->
	<!--begin::income-->
                    <div class="row">
                    <div class="col-md-3">
                            <div class="mb-3">
                              <label for="income_fund" class="form-label">حساب إبراد:</label>
                              <select id="income_fund" name="income_fund" class="form-select" data-control="select2" data-placeholder="حدد حساب إيراد">
                              <option></option>
                              <?php
                                    foreach($income as $row) {
                                    echo "<option value='income_" . $row["account_number"] . "'> صناديق إيرادات - " . $row["account_Sname"] . "</option>";
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="income_amount" class="form-label">مبلغ و قدره:</label>
                              <input type="number" id="income_amount" name="income_amount" class="form-control" step="0.00001" pattern="\d+(\.\d{1,5})?"> 
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="mb-3 d-none">
                              <label for="Cut_Vodafone" class="form-label ">قص فودافون:</label>
                              <input type="number" id="Cut_Vodafone" name="Cut_Vodafone" class="form-control" step="0.00001" pattern="\d+(\.\d{1,5})?"> 
                            </div>
                          </div>
                          
	<!--end::income-->
    <!--begin::description-->
    <div class="col-md-6">
        <div class="mb-3">
            <label for="description" class="form-label">بيان التحويل:</label>
            <textarea id="description" name="description" class="form-control" rows="4"></textarea>
        </div>
    </div>

    </div>

<!--end::description-->

                       
                        <div class="d-flex justify-content-center align-items-center">
                          <button type="submit" class="btn btn-primary">طباعة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <iframe id="print_frame" name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>

        <div class="modal fade" id="recent_transfer_modal" tabindex="-1" aria-labelledby="recent_transfer_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recent_transfer_modalLabel">تحويلات إلكترونية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
        <div class="mb-3">
        <label for="search_input" class="form-label">البحث عن تحويل:</label>
        <input type="text" id="search_input" class="form-control" placeholder="البحث عن تحويل...">
    </div>
    <div class="d-flex justify-content-between align-items-end flex-wrap">
        <button id="search_button" class="btn btn-primary">بحث</button>
        <div class="d-flex align-items-center">
            <select id="selectNumberOfEntries" class="form-select form-select-sm" style="width:auto;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <button id="showLastEntriesBtn" class="btn btn-secondary ms-2">إظهار العمليات الأخيرة</button>
        </div>
    </div>
    <div class="table-responsive">
                <table id="recent_bonds_table" class="table table-striped">
                    <thead>
                        <tr>
                        <th>م.</th>
                            <th>إيصال رقم </th>
                            <th>من حساب</th>
                            <th>المبلغ</th>
                            <th>الفئة</th>
                            <th>إلى حساب</th>
                            <th>المبلغ</th>
                            <th>الفئة</th>
                            <th>حساب إيراد</th>
                            <th>المبلغ</th>
                            <th>البيان</th>
                            <th>التوقيت</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- سيتم إضافة البيانات هنا من خلال الجافا سكريبت -->
                    </tbody>
                </table>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلق</button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Search Modal -->
<div class="modal fade" id="customerSearchModal" tabindex="-1" aria-labelledby="customerSearchModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customerSearchModalLabel">البحث عن رصيد زبون / تاجر</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            <input type="text" id="customerSearchInput" class="form-control" placeholder="أدخل اسم العميل/رقم العميل">
            <div id="customerSearchResults" class="table-responsive">
            <!-- Results will be populated here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلق</button>
      </div>
    </div>
  </div>
</div>

					<!--end:::Main-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<script src="assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--end::Custom Javascript-->
		<script src="assets/js/js/employee_over.js"></script>
        <script src="assets/js/js/servise/service.js"></script>
		<!--end::Javascript-->
        <script>
$(document).ready(function () {
    $(document).on('click', '[data-btn-clone="add"]', function () {
        var row = $(this).closest('[data-clonable-row]');
        row.find('select').each(function(){
            $(this).select2('destroy');
        });

        var clonedRow = row.clone(true);
        clonedRow.find('input').val('');
        clonedRow.find('select').prop('selectedIndex',0);
        clonedRow.find('[data-btn-clone="add"] i').attr('class', 'ki-duotone ki-minus fs-3x');
        clonedRow.find('[data-btn-clone="add"]').attr('data-btn-clone', 'remove');
        clonedRow.insertAfter(row);

        clonedRow.find('select').each(function(){
            $(this).select2();
        });
    });

    $(document).on('click', '[data-btn-clone="remove"]', function () {
        $(this).closest('[data-clonable-row]').remove();
    });

    $(document).on('click', '[data-btn-clone-to="add"]', function () {
        var row = $(this).closest('[data-clonable-row-to]');
        row.find('select').each(function(){
            $(this).select2('destroy');
        });

        var clonedRow = row.clone(true);
        clonedRow.find('input').val('');
        clonedRow.find('select').prop('selectedIndex',0);
        clonedRow.find('[data-btn-clone-to="add"] i').attr('class', 'ki-duotone ki-minus fs-3x');
        clonedRow.find('[data-btn-clone-to="add"]').attr('data-btn-clone-to', 'remove');
        clonedRow.insertAfter(row);

        clonedRow.find('select').each(function(){
            $(this).select2();
        });
    });

    $(document).on('click', '[data-btn-clone-to="remove"]', function () {
        $(this).closest('[data-clonable-row-to]').remove();
    });

    $(document).on('change', 'select[name="from_account[]"]', function() {
        var selectedValue = $(this).val();
        if (selectedValue.startsWith("customer_") || selectedValue.startsWith("trader_")) {
            // If customer or trader is selected, show the currency field
            $(this).closest('.row').find('#from_currency').show();
        } else {
            // Otherwise, hide the currency field and reset its value
            var currencyDiv = $(this).closest('.row').find('#from_currency');
            currencyDiv.hide();
            currencyDiv.find('select').val('').trigger('change');
        }
    });

    // Listen to change event on 'to_account' select field
    $(document).on('change', 'select[name="to_account[]"]', function() {
        var selectedValue = $(this).val();
        if (selectedValue.startsWith("customer_") || selectedValue.startsWith("trader_")) {
            // If customer or trader is selected, show the currency field
            $(this).closest('.row').find('#to_currency').show();
        } else {
            // Otherwise, hide the currency field and reset its value
            var currencyDiv = $(this).closest('.row').find('#to_currency');
            currencyDiv.hide();
            currencyDiv.find('select').val('').trigger('change');
        }
    });

    
});

var createdBy = '<?php echo $_SESSION["username"]; ?>';

$(document).ready(function(){
    $('#income_fund').on('change', function(){
      var selectedValue = $(this).val();
      if(selectedValue === 'income_10300') {
        $('#Cut_Vodafone').parent().removeClass('d-none');
      } else {
        $('#Cut_Vodafone').parent().addClass('d-none');
      }
    });
  });


</script>
	</body>
	<!--end::Body-->

</html>