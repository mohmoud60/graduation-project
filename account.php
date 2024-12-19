<?php
$required_permission = 'permission_3';
include 'session_check.php';
include 'assets/php/connection.php';
$query = $conn->prepare("SELECT * FROM currency");
$query->execute();
$currencies = $query->fetchAll(PDO::FETCH_ASSOC);

$query1 = $conn->prepare("SELECT * FROM type WHERE type_id BETWEEN 3000 AND 3999");
$query1->execute();
$types = $query1->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en" direction="rtl" dir="rtl" style="direction: rtl">
	<!--begin::Head-->
    <?php include 'head.php'; ?>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
					<!--begin::Main-->
<div class="app-main flex-column flex-row" id="kt_app_main">
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
                                حسابات 
                                </span>
                            <!--end::Label-->

                            <!--begin::Line-->
                                <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                            <!--end::Line-->
                                     </span>
                        <!--end::Underline-->
    				</div>
    				<!--end::Page title-->
    			</div>
    			<!--end::Toolbar container-->
        </div>
    </div>
</div>

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl ">
									<!--begin::Card-->
									<div class="card">
										<!--begin::Card header-->
										<div class="card-header border-0 pt-6">
											<!--begin::Card title-->
											<div class="card-title">
												<!--begin::Search-->
												<div class="d-flex align-items-center position-relative my-1">
													<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													<input type="text" data-kt-main-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="البحث عن صندوق حساب" />
												</div>
												<!--end::Search-->
												<!--begin::Export buttons-->
												<div id="kt_datatable_example_1_export" class="d-none"></div>
												<!--end::Export buttons-->
											</div>
											<!--begin::Card title-->
											<!--begin::Card toolbar-->
											<div class="card-toolbar">
												<!--begin::Toolbar-->
												<div class="d-flex justify-content-end" data-kt-main-table-toolbar="base">
										
																<a  class="btn btn-flex btn-primary me-2" id="exp_print" onclick="printData();">
																<i class="ki-duotone ki-printer fs-3" >
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																	<span class="path4"></span>
																	<span class="path5"></span>
																</i>طباعة</a>
	
													<!--begin::Add customer-->
													<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_mainTable">أضف حساب جديد</button>
													<!--end::Add customer-->
												</div>
												<!--end::Toolbar-->
												<!--begin::Group actions-->
												<div class="d-flex justify-content-end align-items-center d-none" data-kt-main-table-toolbar="selected">
													<div class="fw-bold me-5">
													<span class="me-2" data-kt-main-table-select="selected_count"></span>المحدد</div>
													<button type="button" class="btn btn-danger" data-kt-main-table-select="delete_selected">احذف المحدد</button>
												</div>
												<!--end::Group actions-->
											</div>
											<!--end::Card toolbar-->
										</div>
										<!--end::Card header-->
										<!--begin::Card body-->
										<div class="card-body pt-0">
											<!--begin::Table-->
											<table class="table align-middle table-row-dashed fs-6 gy-5" id="mainTable">
												<thead>
													<tr class="text-start text-dark fw-bold fs-7 text-uppercase gs-0">
														<th id="checkboxColumn" class="w-10px pe-2">
															<div class="form-check form-check-sm form-check-custom form-check-solid me-3">
																<input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#mainTable .form-check-input" value="1" />
															</div>
														</th>
                                                        <th class="min-w-100px">رقم الحساب</th>
														<th class="min-w-125px">اسم الحساب</th>
                                                        <th class="min-w-50px">نوع الحساب</th>
														<th class="min-w-50px">قيمة الحساب</th>
                                                        <th class="min-w-50px">عملة الحساب</th>
														<th  id="actionsColumn" class="text-end min-w-70px">أجراءات</th>
													</tr>
												</thead>
												<tbody class="fw-semibold text-gray-600">
												</table>
	
                                        </div>
										<!--end::Card body-->
									</div>
									<!--end::Card-->
									<!--begin::Modals-->
									<!--begin::Modal - Customers - Add-->
									<div class="modal fade" id="add_mainTable" tabindex="-1" aria-hidden="true">
										<!--begin::Modal dialog-->
										<div class="modal-dialog modal-dialog-centered mw-650px">
											<!--begin::Modal content-->
											<div class="modal-content">
												<!--begin::Form-->
												<form class="form" action="#" id="add_mainTable_form" data-kt-redirect="account.php">
													<!--begin::Modal header-->
													<div class="modal-header" id="add_mainTable_header">
														<!--begin::Modal title-->
														<h2 class="fw-bold">إضافة صندوق حساب جديد</h2>
														<!--end::Modal title-->
														<!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" id='add_mainTable_close'>
                                                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                        </div>
														<!--end::Close-->
													</div>
													<!--end::Modal header-->
													<!--begin::Modal body-->
													<div class="modal-body py-10 px-lg-17">
														<!--begin::Scroll-->
														<div class="scroll-y me-n7 pe-7" id="add_mainTable_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#add_mainTable_header" data-kt-scroll-wrappers="#add_mainTable_scroll" data-kt-scroll-offset="300px">
															<!--begin::Input group-->
															<div class="fv-row mb-7">
																<!--begin::Label-->
																<label class="required fs-6 fw-semibold mb-2" for="account_Sname">اسم الحساب</label>
																<!--end::Label-->
																<!--begin::Input-->
																<input type="text" class="form-control " placeholder="" name="account_Sname" id="account_Sname"/>
																<!--end::Input-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="fv-row mb-15">
																<!--begin::Label-->
																<label class="required fs-6 fw-semibold mb-2" for="currency_id">عملة الحساب</label>
                                                                <select class="form-select" data-allow-clear="true" data-dropdown-parent="#add_mainTable" data-control="select2" data-placeholder="حدد خيارا" name="currency_id" id="currency_id">
                                                                <option></option>
                                                                    <?php
                                                                    foreach ($currencies as $currency) {
                                                                        echo "<option value=\"{$currency['currency_id']}\">{$currency['currency_sname']}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
																<!--end::Input-->
															</div>
															<!--end::Input group-->	
															<!--begin::Input group-->
															<div class="fv-row mb-15">
																<!--begin::Label-->
																<label class="required fs-6 fw-semibold mb-2" for="type_id">فئة الحساب</label>
                                                                <select class="form-select" data-allow-clear="true" data-dropdown-parent="#add_mainTable" data-control="select2" data-placeholder="حدد خيارا" name="type_id" id="type_id">
                                                                <option></option>
                                                                    <?php
                                                                    foreach ($types as $type) {
                                                                        echo "<option value=\"{$type['type_id']}\">{$type['type_sname']}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
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
														<button type="reset" id="add_mainTable_cancel" class="btn btn-light me-3">إلغاء</button>
														<!--end::Button-->
														<!--begin::Button-->
														<button type="submit" id="add_mainTable_submit" class="btn btn-primary">
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
									
									<!--end::Modals-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
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
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/js/main_account/add.js"></script>.
		<script src="assets/js/js/main_account/listing.js"></script>
		<script src="assets/js/js/main_account/print.js"></script>
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
        <script src="assets/js/js/employee_over.js"></script>
		<!--end::Custom Javascript-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>

		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>