<?php

include 'session_check.php';
// Include database connection file
include 'assets/php/connection.php';
if (isset($_GET['account_id'])) {
    $account_id = $_GET['account_id'];

    $query = $conn->prepare("
        SELECT accounting.account_Sname, accounting.account_amount, currency.currency_sname ,currency.currency_symbole
        FROM accounting
        INNER JOIN currency ON accounting.currency_id = currency.currency_id
        WHERE accounting.account_number = :account_id
    ");
    $query->execute([':account_id' => $account_id]);
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if($result) {
        $account_Sname = $result['account_Sname'];
        $account_amount = $result['account_amount'];
        $currency_sname = $result['currency_sname'];
        $currency_symbole = $result['currency_symbole'];
    } else {
        // No results found, you can handle this case as you see fit
    }
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
										<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
    										<!--begin::Title-->
											
    										<!--begin::Underline-->
                    					        <span class="d-inline-block position-relative ms-2">
                    					        <!--begin::Label-->
                    					            <span class="d-inline-block mb-2 fs-2tx fw-bold" id="sub_page_titel">
                    					              كشف حساب  رئيسي
                    					            </span>
                    					        <!--end::Label-->

                    					        <!--begin::Line-->
                    					            <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                    					        <!--end::Line-->
                    					                 </span>
                    					    <!--end::Underline-->
    									</div>
										<!--end::Title-->
									</div>
									<!--end::Page title-->
									<!--begin::Actions-->
								
								<!--end::Actions-->
								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl">
									<!--begin::Layout-->
									<div class="d-flex flex-column flex-xl-row">
										<!--begin::Sidebar-->
										<div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
											<!--begin::Card-->
											<div class="card mb-5 mb-xl-8">
												<!--begin::Card body-->
												<div class="card-body pt-15">
													<!--begin::Summary-->
													<div class="d-flex flex-center flex-column mb-5">
                                                    <a  class="fs-1 text-dark fw-bold mb-5">بيانات الحساب</a>

                                                        <!--begin::id-->
														<a  class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1" id="account_id">رقم الحساب : <?php echo $account_id; ?></a>
														<!--end::id-->
														<!--begin::Name-->
														<a  class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1" id="account_Sname">اسم الحساب: <?php echo $account_Sname; ?></a>
														<!--end::Name-->
														<!--begin::amount-->
														<a  class="fs-3  text-gray-800 text-hover-primary fw-bold mb-1" id="account_amount">قيمة الحساب :<?php echo $account_amount . ' ' . $currency_symbole; ?></a>
														<!--end::amount-->
														<!--begin::currency-->
														<a  class="fs-3  text-gray-800 text-hover-primary fw-bold mb-1" id="currency_sname">عملة الحساب :<?php echo $currency_sname; ?></a>
														<!--end::currency-->
													</div>
													<!--end::Summary-->
												</div>
												<!--end::Card body-->
											</div>
											<!--end::Card-->
										</div>
										<!--end::Sidebar-->
										<!--begin::Content-->
										<div class="flex-lg-row-fluid ms-lg-15">
											
											<!--end:::Tabs-->
													<!--begin::Filters-->
													<div class="card mb-6 mb-xl-9">
													    <!--begin::Header-->
													    <div class="card-header border-0">
													      
													    </div>
													    <!--end::Header-->
													    <!--begin::Body-->
													    <div class="me-2 row ms-2">
                    										<div class=" col-md-6">
                    										    <label for="from_date" class="form-label fw-bold">من تاريخ</label>
                    										    <div class="input-group flex-nowrap">
                    										        <span class="input-group-text">
                    										            <i class="ki-duotone ki-calendar-2 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    										        </span>
                    										        <div class="overflow-hidden flex-grow-1">
																	<input class="form-control" placeholder="إختر من تاريخ" id="from_date"/>
                    										        </div>
                    										    </div>
                    										</div>
                    										<div class="col-md-6">
                    										    <label for="to_date" class="form-label fw-bold ">إلى تاريخ</label>
                    										    <div class="input-group flex-nowrap">
                    										        <span class="input-group-text">
                    										            <i class="ki-duotone ki-calendar-2 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    										        </span>
                    										        <input class="form-control" placeholder="إختر إلى تاريخ" id="to_date"/>

                    										</div>
                    										</div>
                    										
															</div>
													    <!--end::Body-->
													
													    <!--begin::Footer-->
													    <div class="card-footer text-center">
													        <button type="button" class="btn btn-primary" id="show_report">عرض التقرير</button>
													    </div>
													    <!--end::Footer-->
													</div>
													<!--end::Filters-->



													<!--begin::Card-->
													<div class="card pt-4 mb-6 mb-xl-9">
														<!--begin::Card header-->
														<div class="card-header border-0">
															<!--begin::Card title-->
															<div class="card-title">
        												    <h2>سجل التحويلات</h2>
        												</div>
															<!--end::Card title-->
															<div class="card-toolbar">
																<a  class="btn btn-sm btn-flex btn-primary me-2" id="exp_print" onclick="printData();">
																<i class="ki-duotone ki-printer fs-3" >
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																	<span class="path4"></span>
																	<span class="path5"></span>
																</i>طباعة</a>
															
															</div>
															
														</div>
														
														<!--end::Card header-->
														
														<!--begin::Card body-->
														<div class="card-body pt-0 pb-5">
														<div class="table-responsive">
															<!--begin::Table-->
															<table class="table align-middle table-row-dashed gy-5" id="kt_table_traders_payment">
																<thead class="border-bottom border-gray-200 fs-7 fw-bold">
																	<tr class="text-start text-muted text-uppercase gs-0">
																		
																	</tr>
																</thead>
																<tbody class="fs-6 fw-semibold text-gray-600">
																</tbody>
															</table>
															<!--end::Table-->
														</div>
														</div>
														<!--end::Card body-->
													</div>
													<!--end::Card-->
											</div>
											<!--end:::Tab content-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Layout-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
					</div>
					<!--end:::Main-->

										<!--begin::Modal - Customers - Add-->
										<div class="modal fade" id="add_transaction" tabindex="-1" aria-hidden="true">
										<!--begin::Modal dialog-->
										<div class="modal-dialog modal-dialog-centered mw-650px">
											<!--begin::Modal content-->
											<div class="modal-content">
												<!--begin::Form-->
												<form class="form" action="#" id="add_transaction_form" data-kt-redirect="<?php echo 'customer_veiw.php?customer_id=' . $_GET['customer_id']; ?>">
													<!--begin::Modal header-->
													<div class="modal-header" id="add_transaction_header">
														<!--begin::Modal title-->
														<h2 class="fw-bold">عملية تحويل جديدة</h2>
														<!--end::Modal title-->
														<!--begin::Close-->
														<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                										    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                										</div>
                										<!--end::Close-->
													</div>
													<!--end::Modal header-->
													<!--begin::Modal body-->
													<div class="modal-body py-10 px-lg-17">
														<!--begin::Scroll-->
														<div class="scroll-y me-n7 pe-7" id="add_transaction_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#add_transaction_header" data-kt-scroll-wrappers="#add_transaction_scroll" data-kt-scroll-offset="300px">
															<!--begin::Input group-->
															<div class="fv-row mb-7">
																<!--begin::Label-->
																<label class="required fs-6 fw-semibold mb-2" for="transaction_type">نوع العملية</label>
																<!--end::Label-->
																<!--begin::Input-->
																<select class="form-select" id="transaction_type" name="transaction_type">
            													  <option selected>إختر...</option>
            													  <option value="deposit">إيداع</option>
            													  <option value="withdraw">سحب</option>
            													</select>
																<!--end::Input-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="row">
																<div class="col-md-6 mb-3">
																	<!--begin::Label-->
																	<label class="fs-6 fw-semibold mb-2" for="amount">
																		<span class="required">المبلغ</span>
																	</label>
																	<!--begin::Input-->
																	<input type="number" class="form-control" placeholder="" name="amount" id="amount" />
																</div>
																	<div class="col-md-6 mb-3">
            														  <label for="currency" class="form-label required">صندوق العملات</label>
            														  <select class="form-select" id="currency" name="currency">
            														    <option selected>إختر...</option>
            														    <option value="USD">دولار أمريكي - USD</option>
            														    <option value="ILS">شيكل إسرائيلي - ILS</option>
            														    <option value="JOD">دينار أردني - JOD</option>
																		<option value="EGP">جنيه مصري - EGP</option>
																		<option value="EUR">يورو - EUR</option>
            														  </select>
            														</div>
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="fv-row mb-15">
																<!--begin::Label-->
																<label class="fs-6 fw-semibold mb-2 required" for="descripcion">بيان التحويل</label>
																<!--end::Label-->
																<!--begin::Input-->
																<textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
																<!--end::Input-->
															</div>
															<!--end::Input group-->	
														</div>
														<!--end::Scroll-->
													</div>
													<!--end::Modal body-->
													<!--begin::Modal footer-->
													<div class="modal-footer flex-center">
														<!--begin::Button-->
														<button type="reset" id="add_transaction_cancel" class="btn btn-light me-3">إلغاء</button>
														<!--end::Button-->
														<!--begin::Button-->
														<button type="submit" id="add_transaction_submit" class="btn btn-primary">
															<span class="indicator-label">حفظ</span>
															<span class="indicator-progress">انتظر من فضلك...
															<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
														</button>
														<!--end::Button-->
													</div>
													<!--end::Modal footer-->
												</form>
												<!--end::Form-->
											</div>
										</div>
									</div>
									<!--end::Modal - Customers - Add-->
									
									<iframe id="print_frame" name="print_frame" style="display:none;"></iframe>
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
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>

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
		<script src="assets/js/js/customer/customer_veiw.js"></script>
		<script src="assets/js/js/customer/transaction/add.js"></script>
		<script src="assets/js/js/customer/transaction/listing.js"></script>
		<script src="assets/js/js/customer/transaction/print.js"></script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>