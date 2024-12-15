<?php
include 'session_check.php';

include 'assets/php/connection.php';
// Function to fetch data
function fetchData($conn, $sql, $params = []) {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$funds = fetchData($conn, "
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
        a.account_type = (?) 
    ORDER BY 
        a.account_number ASC, 
        a.account_Sname ASC", [3100]);

        $currencySymbols = [];
        foreach ($funds as $fund) {
            $currencySymbols[] = $fund['currency_symbole'];
        }
        
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
                        <span class="d-inline-block mb-2 fs-2tx fw-bold"  id="page_titel">
                             سندات صرف / قبض 
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
          <a class="btn fw-bold btn-info" data-bs-toggle="modal" data-bs-target="#recent_bonds_modal">عرض السندات</a>
					<!--end::Secondary button-->
				</div>
				<!--end::Actions-->
			</div>
			<!--end::Toolbar container-->
		</div>
<main class="container">
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
            <div class="card-header d-flex align-items-center justify-content-center border-bottom py-3">
                    <h2 class="fs-4 fw-bold m-0">سندات الصرف</h2>
                </div>                <div class="card-body">
                    <form id="exchange_form">
                        <input type="hidden" name="bond_type" value="exchange">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="exchange_number" class="form-label">رقم السند:</label>
                                <input type="text" id="exchange_number" name="exchange_number" class="form-control" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="exchange_date" class="form-label">التاريخ:</label>
                                <input type="text" id="exchange_date" name="exchange_date" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exchange_name" class="form-label">إصرفوا الى السيد/ة:</label>
                            <input type="text" id="exchange_name" name="exchange_name" class="form-control" required>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="exchange_amount" class="form-label">مبلغ و قدره:</label>
                              <input type="number" id="exchange_amount" name="exchange_amount" class="form-control" step="0.00001" pattern="\d+(\.\d{1,5})?" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="exchange_fund" class="form-label">الصندوق:</label>
                              <select id="exchange_fund" name="currency" class="form-select"data-control="select2" data-dropdown-parent="#exchange_form" data-placeholder="حدد حساب">
                              <option></option>
                              <?php foreach ($funds as $fund): ?>
                                    <option value="<?= htmlspecialchars($fund['account_number']) ?>" data-currency-symbole="<?= htmlspecialchars($fund['currency_symbole']) ?>"><?= htmlspecialchars($fund['account_Sname']) ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="mb-3">
                            <label for="exchange_description" class="form-label">وذلك عن:</label>
                            <textarea id="exchange_description" name="exchange_description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" id="spical_bonds" name="is_special" class="form-check-input">
                            <label class="form-check-label" for="spical_bonds">سند خاص فالشركة</label>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                          <button type="submit" class="btn btn-primary">طباعة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
            <div class="card-header d-flex align-items-center justify-content-center border-bottom py-3">
                    <h2 class="fs-4 fw-bold m-0">سندات القبض</h2>
                </div>
                 <div class="card-body">
                    <form id="receipt_form">
                        <input type="hidden" name="bond_type" value="receipt">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="receipt_number" class="form-label">رقم السند:</label>
                                <input type="text" id="receipt_number" name="receipt_number" class="form-control" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="receipt_date" class="form-label">التاريخ:</label>
                                <input type="text" id="receipt_date" name="receipt_date" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="receipt_name" class="form-label">إستلمنا من السيد/ة:</label>
                            <input type="text" id="receipt_name" name="receipt_name" class="form-control" required>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="receipt_amount" class="form-label">مبلغ و قدره:</label>
                              <input type="number" id="receipt_amount" name="receipt_amount" class="form-control" step="0.00001" pattern="\d+(\.\d{1,5})?" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="receipt_fund" class="form-label">الصندوق:</label>
                              <select id="receipt_fund" name="currency" class="form-select" data-control="select2" data-dropdown-parent="#exchange_form" data-placeholder="حدد حساب">
                              <option></option>
                              <?php foreach ($funds as $fund): ?>
                                <option value="<?= htmlspecialchars($fund['account_number']) ?>" data-currency-symbole="<?= htmlspecialchars($fund['currency_symbole']) ?>"><?= htmlspecialchars($fund['account_Sname']) ?></option>
<?php endforeach; ?>

                              </select>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="mb-3">
                            <label for="receipt_description" class="form-label">وذلك عن:</label>
                            <textarea id="receipt_description" name="receipt_description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" id="spical_bonds" name="is_special" class="form-check-input">
                            <label class="form-check-label" for="spical_bonds">سند خاص فالشركة</label>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                          <button type="submit" class="btn btn-primary">طباعة</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <iframe id="print_frame" name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>

    <div class="modal fade" id="recent_bonds_modal" tabindex="-1" aria-labelledby="recent_bonds_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recent_bonds_modalLabel">سندات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
        <div class="mb-3">
        <label for="search_input" class="form-label">البحث عن سند:</label>
        <input type="text" id="search_input" class="form-control" placeholder="البحث عن سند...">
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
                            <th>رقم السند</th>
                            <th>الاسم</th>
                            <th>المبلغ</th>
                            <th>الوصف</th>
                            <th>الوقت</th>
                            <th>التاريخ</th>
                            <th>نوع السند</th>
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
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/new-target.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<script src="assets/js/js/employee_over.js"></script>
        <script src="assets/js/js/bonds/bonds.js"></script>
        <script type="text/javascript">
            var funds = <?php echo json_encode($funds); ?>;
            var createdBy = <?php echo json_encode($_SESSION["username"]); ?>; // استخدم json_encode هنا
            var currencySymbols = <?php echo json_encode($currency_symbols ?? []); ?>;
            </script>

		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>