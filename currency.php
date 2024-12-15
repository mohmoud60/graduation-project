<?php
include 'session_check.php';
include 'assets/php/connection.php';

$query = $conn->prepare("SELECT * FROM exchange_rates  WHERE Delete_Date IS NULL");
$query->execute();
$main_currency = $query->fetchAll(PDO::FETCH_ASSOC);

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
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
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
                            تحويل العملات 
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
					<a class="btn fw-bold btn-info" data-bs-toggle="modal" data-bs-target="#exupdateModal">تحديث أسعار الصرف</a>

					<!--end::Secondary button-->
					<!--begin::Primary button-->
                    <a class="btn fw-bold btn-info" data-bs-toggle="modal" data-bs-target="#returnModal">إرجاع فاتورة</a>
					<!--end::Primary button-->
				</div>
				<!--end::Actions-->
			</div>
			<!--end::Toolbar container-->
		</div>
		<!--end::Toolbar-->
		<!--begin::Content-->

		<div id="kt_app_content" class="app-content flex-column-fluid">
			<div id="kt_app_content_container" class="app-container container-xxl">
                <div class="container d-flex justify-content-center align-items-center me-md-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <input type="text" class="form-control me-2" id="ID" placeholder="رقم الزبون">
                        <input type="text" class="form-control me-2" id="customer_name" placeholder="اسم الزبون">
                        <button class="btn btn-info" id="serch_customer"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <div class="container d-flex justify-content-center align-items-center mt-3">
                    <div class="col-4 text-center">
                        <div class="input-group mb-3 ">
                            <input type="text" id="sell-buy-total" class="form-control bg-secondary" style="color:#45a049; font-size: 30px;" readonly>
                            <span class="input-group-text bg-secondary" id="sell-buy-total-symbol"  style="color:#45a049; font-size: 24px;"></span>
                        </div>
                    </div>
                </div> 

                <div class="row" >
                    <div class="col-lg-7">
                        <div class="card mb-4">
                            <div class="card-body">
                                <!-- Drop down currency selection and a sell button and next to it a buy button-->
                                <div class="mb-5 d-flex align-items-center flex-nowrap">
                                <label for="sell-buy-from"  class="me-3 flex-shrink-0">إختيار العملات</label>
                                    <select required id="sell-buy-from" class="form-select me-3" aria-label="Currency selection">
                                            <?php
                                              foreach ($main_currency as $currency) {
                                                  echo "<option name=\"{$currency['currency_ex']}\" value=\"{$currency['fund_sname']}\">{$currency['fund_sname']}</option>";
                                              }
                                              ?>
                                    </select>
                                </div>
                                <div class="mb-3 d-flex justify-content-center">
                                    <button type="button" id="sell-btn" class="btn btn-dark me-2" name="sell">بيع</button>
                                    <button type="button" id="buy-btn"  class="btn btn-dark" name="buy">شراء</button>
                                </div>
                                <!-- Quantity field -->
                                <div class="form-floating mb-3">
                                    <input required disabled type="number" class="form-control" id="sell-quantity" placeholder="الكمية">
                                    <label for="sell-quantity">الكمية</label>
                                </div>
                                <!-- Quantity butn -->
                                <div class="mb-3  d-flex justify-content-center">
                                <button  type="button" id="firstCurrencyBtn" class="btn btn-sm btn-dark me-2 d-none" ></button>
                                <button  type="button" id="secondCurrencyBtn" class="btn btn-sm btn-dark me-2 d-none"></button>
                                </div>
                                <!-- Exchange rate field -->
                                <div class="form-floating  mb-3">
                                    <input required disabled type="text" class="form-control" id="sell-exchange" placeholder="سعر الصرف">
                                    <label for="sell-exchange">سعر الصرف</label>
                                </div>
                                <!-- Add and Print buttons -->
                                <div class="mb-3 d-flex justify-content-center">
                                <button type="button" id="add-btn" class="btn btn-dark  me-2">إضافة</button>
                                <button type="button" id="print-btn" class="btn btn-dark">طباعة F3</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card mb-4">
                            <div class="card-body">
                                <table  id="transactions-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>م.</th>
                                            <th>نوع العملات</th>
                                            <th>الكمية</th>
                                            <th>سعر الصرف</th>
                                            <th>المجموع</th>
                                            <th>شراء - بيع</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table content here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>  


<!-- Modal -->
<div class="modal fade" id="exupdateModal" tabindex="-1" aria-labelledby="exupdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exupdateModalLabel">تحديث اسعار صرف الجملة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="exupdateForm">
                    <label for="exupdate-base" class="form-label">من العملة:</label>
                    <select id="exupdate-base" class="form-control">
                    <?php
                                              foreach ($main_currency as $currency) {
                                                  echo "<option value=\"{$currency['currency_ex']}\">{$currency['fund_sname']}</option>";
                                              }
                                              ?>
                    </select>
                    <label for="exupdate-buy-rate" class="form-label">سعر الشراء:</label>
                    <input type="number" id="exupdate-buy-rate" class="form-control" step="0.00001">
                    <label for="exupdate-sell-rate" class="form-label">سعر البيع:</label>
                    <input type="number" id="exupdate-sell-rate" class="form-control" step="0.00001">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">أغلق</button>
                <button id="exupdate-button" type="button" class="btn btn-primary">تحديث</button>
            </div>
        </div>
    </div>
</div>



<!-- Delete Confirm Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">تأكيد الحذف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="deleteConfirmForm">
          <div class="mb-3">
            <label for="deleteReason" class="form-label">سبب الحذف</label>
            <input type="text" class="form-control" id="deleteReason" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-danger confirm-delete-btn">حذف</button>
      </div>
    </div>
  </div>
</div>





<!-- Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"> <!-- modal-xl for larger modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="returnModalLabel">إرجاع فاتورة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
    <label for="invoiceNumber" class="form-label">رقم الفاتورة</label>
    <input type="number" class="form-control" id="invoiceNumber" name="invoiceNumber" required>

    <div class="d-flex justify-content-between align-items-end my-3 flex-wrap">
        <button id="searchInvoiceBtn" class="btn btn-primary">بحث</button>
        <div class="d-flex align-items-center">
            <select id="selectNumberOfEntries" class="form-select form-select-sm" style="width:auto;">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <button id="showLastEntriesBtn" class="btn btn-secondary ms-2">إظهار اخر فواتير</button>
        </div>
    </div>


    <div class="table-responsive">
        <table id="returnTable" class="table table-striped table-row-bordered gy-5 gs-7">
          <thead>
            <tr>
            <th>م.</th>
            <th>رقم الفاتورة</th>
            <th>نوع العملات التحويل</th>
            <th>الكمية</th>
            <th>سعر الصرف</th>
            <th>الإجمالي</th>
            <th> (شراء - بيع)</th>
            <th>الوقت</th>
            <th>التاريخ</th>
            <th>إجراءات</th>
            </tr>
          </thead>
          <tbody>
            <!-- Results will be added here -->
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
        <h5 class="modal-title" id="customerSearchModalLabel">البحث عن العملاء</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
  <input type="text" id="customerSearchInput" class="form-control" placeholder="أدخل اسم العميل/رقم هاتف العميل">
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
<div id="username" data-username="<?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : ''; ?>" style="display:none;"></div>

			<!--end::Content container-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Content wrapper-->
</div>


<!--end:::Main-->
<!--begin::Javascript-->
<script>var hostUrl = "assets/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="assets/plugins/global/plugins.bundle.js"></script>
<script src="assets/js/scripts.bundle.js"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="assets/js/js/currency.js"></script>
<script src="assets/js/js/employee_over.js"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
<iframe id="print-frame" style="display:none;"></iframe>
</body>
<!--end::Body-->
</html>
