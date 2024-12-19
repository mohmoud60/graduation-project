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
                <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">الصلاحيات - ROLES</span>
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
    				<!--begin::Row-->
    				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">

    				</div>
    				<!--end::Row-->
    				
    			</div>
    			<!--end::Content container-->
    		    </div>
    		    <!--end::Content-->
    	        </div>
    	        <!--end::Content wrapper-->
	            </div>
	            <!--end:::Main-->

        <!--begin::Modals-->
                <!--begin::Modal - Add role-->
                <div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-750px">
            <!--begin::Modal content-->
            <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <h2 class="fw-bold">إضافة دور جديد</h2>
                <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </button>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-lg-5 my-7">
                <form id="kt_modal_add_role_form" class="form">
                    <!-- اسم الدور -->
                    <div class="fv-row mb-10">
                        <label class="fs-5 fw-bold form-label mb-2">اسم الدور</label>
                        <input type="text" class="form-control form-control-solid" placeholder="أدخل اسم الدور" name="role_name" required />
                    </div>
                    <!-- الصلاحيات -->
                    <div class="fv-row">
                        <label class="fs-5 fw-bold form-label mb-2">الصلاحيات</label>
                        <div id="permissions_container" class="table-responsive">
                            <!-- سيتم تحميل الصلاحيات هنا ديناميكيًا -->
                        </div>
                    </div>
                    <!-- أزرار التحكم -->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">إضافة</span>
                            <span class="indicator-progress">يرجى الانتظار... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
                </div>
                <!--end::Modal - Add role-->

            <!--begin::Modal - Update Role-->
        <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-750px">
                <!--begin::Modal content-->
                <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                <h2 class="fw-bold">تحديث صلاحيات الدور</h2>
                <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"></i>
                </button>
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 my-7">
                <form id="kt_modal_update_role_form" class="form">
                    <!-- اسم الدور -->
                    <div class="fv-row mb-10">
                        <label class="fs-5 fw-bold form-label mb-2">اسم الدور</label>
                        <input type="text" class="form-control form-control-solid" name="role_name" placeholder="اسم الدور" readonly />
                    </div>

                    <!-- الصلاحيات -->
                    <div class="fv-row">
                        <label class="fs-5 fw-bold form-label mb-2">الصلاحيات</label>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <tbody class="text-gray-600 fw-semibold">
                                    <!-- سيتم إدخال الصلاحيات ديناميكيًا هنا -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">حفظ التعديلات</span>
                            <span class="indicator-progress">يرجى الانتظار... 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
                </div>
                <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
            </div>
            <!--end::Modal - Update Role-->

        <!--end::Modals-->

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
            <script src="assets/js/js/users/listing_rols.js"></script>
            <script src="assets/js/js/users/add_role.js"></script>
			<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>