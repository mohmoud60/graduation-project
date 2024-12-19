<?php
$required_permission = 'permission_13';

include 'session_check.php';
include 'assets/php/connection.php';


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

		<div class="container mt-5">
            <div class="mb-5"> <!-- إضافة mb-5 لتوفير مسافة بين العنوان والكائنات -->
                <span class="d-inline-block position-relative ms-2">
                <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">قائمة الأذونات</span>
                <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
                </span>
            </div>
        
            <!--begin::Main-->
			<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Toolbar-->
							<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">

								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl">
									<!--begin::Card-->
									<div class="card card-flush">
										<!--begin::Card header-->
										<div class="card-header mt-6">
											<!--begin::Card title-->
											<div class="card-title">
												<!--begin::Search-->
												<div class="d-flex align-items-center position-relative my-1 me-5">
													<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
													<input type="text" data-kt-permissions-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="البحث عن أذونات" />
												</div>
												<!--end::Search-->
											</div>
											<!--end::Card title-->
											<!--begin::Card toolbar-->
											<div class="card-toolbar">
												<!--begin::Button-->
												<button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_permission">
												<i class="ki-duotone ki-plus-square fs-3">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
												</i>إضافة أذونات</button>
												<!--end::Button-->
											</div>
											<!--end::Card toolbar-->
										</div>
										<!--end::Card header-->
										<!--begin::Card body-->
										<div class="card-body pt-0">
											<!--begin::Table-->
											<table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_permissions_table">
												<thead>
													<tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
														<th class="min-w-125px">الأسم</th>
														<th class="min-w-250px">مُخصص لـ</th>
														<th class="min-w-125px">تاريخ الإنشاء</th>
														<th class="text-end min-w-100px">الإجراءات</th>
													</tr>
												</thead>
												<tbody class="fw-semibold text-gray-600">

												</tbody>
											</table>
											<!--end::Table-->
										</div>
										<!--end::Card body-->
									</div>
									<!--end::Card-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
			</div>
			<!--end:::Main-->


            <!--begin::Modals-->
			<!--begin::Modal - Add permissions-->
            <div class="modal fade" id="kt_modal_add_permission" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">إضافة إذن</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
                <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_permission_form" class="form" action="#">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Label-->
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span class="required">اسم الإذن</span>
                            <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="يجب أن تكون أسماء الأذونات فريدة.">
                                <i class="ki-duotone ki-information fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input class="form-control form-control-solid" placeholder="أدخل اسم الإذن" name="permission_name" required />
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <!--begin::Checkbox-->
                        <label class="form-check form-check-custom form-check-solid me-9">
                            <input class="form-check-input" type="checkbox" value="1" name="permissions_core" id="kt_permissions_core" />
                            <span class="form-check-label" for="kt_permissions_core">تحديد كإذن أساسي</span>
                        </label>
                        <!--end::Checkbox-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Disclaimer-->
                    <div class="text-gray-600">الإذن المحدد كـ 
                        <strong class="me-1">إذن أساسي</strong> سيكون مقفلًا ولن يمكن تعديله لاحقًا.
                    </div>
                    <!--end::Disclaimer-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">إضافة</span>
                            <span class="indicator-progress">يرجى الانتظار... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
                </div>
                <!--end::Modal body-->
                </div>
                 <!--end::Modal content-->
                </div>
                 <!--end::Modal dialog-->
            </div>
            <!--end::Modal - Add permissions-->

			<!--begin::Modal - Update permissions-->
            <!--begin::Modal - Update Permission-->
<div class="modal fade" id="kt_modal_update_permission" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h2 class="fw-bold">تحديث الإذن</h2>
                <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!-- تحذير إذا كان الإذن أساسيًا -->
                <div id="core_permission_warning" class="alert alert-warning d-none">
                    هذا الإذن هو إذن أساسي ولا يمكن تعديله.
                </div>
                <!--begin::Form-->
                <form id="kt_modal_update_permission_form" class="form">
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span class="required">اسم الإذن</span>
                            <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="يجب أن تكون أسماء الأذونات فريدة.">
                                <i class="ki-duotone ki-information fs-7">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </label>
                        <input class="form-control form-control-solid" placeholder="أدخل اسم الإذن" name="permission_name" required />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary" id="save_permission_button">
                            <span class="indicator-label">حفظ</span>
                            <span class="indicator-progress">انتظر من فضلك... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Update Permission-->

            <!--end::Modal - Update permissions-->

			<!--end::Modals-->


        </div>

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
			<!--end::Custom Javascript-->
			<script src="assets/js/js/employee_over.js"></script>
            <script src="assets/js/js/users/listing_permissions.js"></script>
			<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>