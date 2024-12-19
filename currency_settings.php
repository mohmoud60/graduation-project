<?php
include 'session_check.php';

$required_permission = 'permission_4';

if (isset($_SESSION['role'])) {
    echo "<script>
            localStorage.setItem('userRole', '" . $_SESSION['role'] . "');
          </script>";
}

include 'assets/php/connection.php';
$query = $conn->prepare("SELECT * FROM accounting WHERE account_type = 3000");
$query->execute();
$currencies = $query->fetchAll(PDO::FETCH_ASSOC);

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
                                    إدارة العملات   
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
													<input type="text" data-kt-main-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="البحث عن عملات" />
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
																<?php if ($_SESSION['role'] === 'Admin'): ?>
													<!--begin::Add -->
													<button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#add_mainTable">أضف عملات جديد</button>
													<!--end::Add -->
													<?php endif; ?>
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
                                                        <th class="min-w-80px">رقم العملة</th>
														<th class="min-w-150px">اسم العملات</th>
                                                        <th class="min-w-80px">شعار العملة</th>
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
												<form class="form" action="#" id="add_mainTable_form" data-kt-redirect="currency_settings.php">
													<!--begin::Modal header-->
													<div class="modal-header" id="add_mainTable_header">
														<!--begin::Modal title-->
														<h2 class="fw-bold">إضافة فئة عملات  جديد</h2>
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
															<!--begin::Input group-->
															<div class="fv-row mb-15">
																<!--begin::Label-->
																<label class="required fs-6 fw-semibold mb-2" for="currency_sname">اسم العملة</label>
                                                                <input type="text" class="form-control " placeholder="" name="currency_sname" id="currency_sname"/>
																<!--end::Input-->
															</div>
															<!--end::Input group-->	
                                                            <!--begin::Input group-->
															<div class="fv-row mb-15">
																<!--begin::Label-->
																<label class="required fs-6 fw-semibold mb-2" for="currency_symbole">شعار العملة</label>
                                                                <input type="text" class="form-control " placeholder="" name="currency_symbole" id="currency_symbole"/>

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
		<script src="assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
		<script src="assets/js/js/currency_setting/currency/add.js"></script>.
		<script src="assets/js/js/currency_setting/currency/listing.js"></script>
		<script src="assets/js/js/currency_setting/currency/print.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/new-target.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<script src="assets/js/js/employee_over.js">

		</script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>