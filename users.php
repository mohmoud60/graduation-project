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
        <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">المستخدمون</span>
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
	    							<input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="بحث عن مستخدم" />
	    						</div>
	    						<!--end::Search-->
	    					</div>
	    					<!--begin::Card title-->
	    					<!--begin::Card toolbar-->
	    					<div class="card-toolbar">
	    					

	    					</div>
	    					<!--end::Card toolbar-->
	    				</div>
	    				<!--end::Card header-->
	    				<!--begin::Card body-->
	    				<div class="card-body py-4">
	    					<!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="w-10px pe-2">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                                <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_table_users .form-check-input" value="1" />
                                            </div>
                                        </th>
                                        <th class="min-w-156px">المستخدم</th>
                                        <th class="min-w-156px">دور</th>
                                        <th class="min-w-156px">أخر تسجيل دخول</th>
                                        <th class="min-w-156px">تاريخ الانضمام</th>
                                        <th class=" min-w-100px">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold"></tbody>
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

</div>

	<!--begin::Modal - Update User Role-->
	<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bold">تحديث صلاحيات المستخدم</h2>
                <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_user_form" class="form">
                    <!-- اسم المستخدم -->
                    <div class="fv-row mb-10">
                        <label class="fs-5 fw-bold form-label mb-2">اسم المستخدم</label>
                        <input type="text" class="form-control form-control-solid" name="user_name" readonly />
                    </div>
                    <!-- الصلاحيات -->
                    <div class="fv-row">
                        <label class="required fw-semibold fs-6 mb-5">تحديث الصلاحيات</label>
                        <div id="role_options_container">
                            <!-- سيتم إدخال الخيارات ديناميكيًا -->
                        </div>
                    </div>
                    <!-- أزرار التحكم -->
                    <div class="text-center pt-10">
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
	<!--end::Modal - Update User Role-->



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
            <script src="assets/js/js/users/listing.js"></script>
			<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>