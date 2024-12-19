<?php
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
	    						<!--begin::Modal - Adjust Balance-->
	    						<div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
	    							<!--begin::Modal dialog-->
	    							<div class="modal-dialog modal-dialog-centered mw-650px">
	    								<!--begin::Modal content-->
	    								<div class="modal-content">
	    									<!--begin::Modal header-->
	    									<div class="modal-header">
	    										<!--begin::Modal title-->
	    										<h2 class="fw-bold">Export Users</h2>
	    										<!--end::Modal title-->
	    										<!--begin::Close-->
	    										<div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
	    											<i class="ki-duotone ki-cross fs-1">
	    												<span class="path1"></span>
	    												<span class="path2"></span>
	    											</i>
	    										</div>
	    										<!--end::Close-->
	    									</div>
	    									<!--end::Modal header-->
	    									<!--begin::Modal body-->
	    									<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
	    										<!--begin::Form-->
	    										<form id="kt_modal_export_users_form" class="form" action="#">
	    											<!--begin::Input group-->
	    											<div class="fv-row mb-10">
	    												<!--begin::Label-->
	    												<label class="fs-6 fw-semibold form-label mb-2">Select Roles:</label>
	    												<!--end::Label-->
	    												<!--begin::Input-->
	    												<select name="role" data-control="select2" data-placeholder="Select a role" data-hide-search="true" class="form-select form-select-solid fw-bold">
	    													<option></option>
	    													<option value="Administrator">Administrator</option>
	    													<option value="Analyst">Analyst</option>
	    													<option value="Developer">Developer</option>
	    													<option value="Support">Support</option>
	    													<option value="Trial">Trial</option>
	    												</select>
	    												<!--end::Input-->
	    											</div>
	    											<!--end::Input group-->
	    											<!--begin::Input group-->
	    											<div class="fv-row mb-10">
	    												<!--begin::Label-->
	    												<label class="required fs-6 fw-semibold form-label mb-2">Select Export Format:</label>
	    												<!--end::Label-->
	    												<!--begin::Input-->
	    												<select name="format" data-control="select2" data-placeholder="Select a format" data-hide-search="true" class="form-select form-select-solid fw-bold">
	    													<option></option>
	    													<option value="excel">Excel</option>
	    													<option value="pdf">PDF</option>
	    													<option value="cvs">CVS</option>
	    													<option value="zip">ZIP</option>
	    												</select>
	    												<!--end::Input-->
	    											</div>
	    											<!--end::Input group-->
	    											<!--begin::Actions-->
	    											<div class="text-center">
	    												<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
	    												<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
	    													<span class="indicator-label">Submit</span>
	    													<span class="indicator-progress">Please wait... 
	    													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
	    						<!--end::Modal - New Card-->
	    						<!--begin::Modal - Add task-->
	    						<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
	    							<!--begin::Modal dialog-->
	    							<div class="modal-dialog modal-dialog-centered mw-650px">
	    								<!--begin::Modal content-->
	    								<div class="modal-content">
	    									<!--begin::Modal header-->
	    									<div class="modal-header" id="kt_modal_add_user_header">
	    										<!--begin::Modal title-->
	    										<h2 class="fw-bold">Add User</h2>
	    										<!--end::Modal title-->
	    										<!--begin::Close-->
	    										<div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
	    											<i class="ki-duotone ki-cross fs-1">
	    												<span class="path1"></span>
	    												<span class="path2"></span>
	    											</i>
	    										</div>
	    										<!--end::Close-->
	    									</div>
	    									<!--end::Modal header-->
	    									<!--begin::Modal body-->
	    									<div class="modal-body px-5 my-7">
	    										<!--begin::Form-->
	    										<form id="kt_modal_add_user_form" class="form" action="#">
	    											<!--begin::Scroll-->
	    											<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
	    												<!--begin::Input group-->
	    												<div class="fv-row mb-7">
	    													<!--begin::Label-->
	    													<label class="d-block fw-semibold fs-6 mb-5">Avatar</label>
	    													<!--end::Label-->
	    													<!--begin::Image placeholder-->
	    													<style>.image-input-placeholder { background-image: url('assets/media/svg/files/blank-image.svg'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('assets/media/svg/files/blank-image-dark.svg'); }</style>
	    													<!--end::Image placeholder-->
	    													<!--begin::Image input-->
	    													<div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
	    														<!--begin::Preview existing avatar-->
	    														<div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/media/avatars/300-6.jpg);"></div>
	    														<!--end::Preview existing avatar-->
	    														<!--begin::Label-->
	    														<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
	    															<i class="ki-duotone ki-pencil fs-7">
	    																<span class="path1"></span>
	    																<span class="path2"></span>
	    															</i>
	    															<!--begin::Inputs-->
	    															<input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
	    															<input type="hidden" name="avatar_remove" />
	    															<!--end::Inputs-->
	    														</label>
	    														<!--end::Label-->
	    														<!--begin::Cancel-->
	    														<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
	    															<i class="ki-duotone ki-cross fs-2">
	    																<span class="path1"></span>
	    																<span class="path2"></span>
	    															</i>
	    														</span>
	    														<!--end::Cancel-->
	    														<!--begin::Remove-->
	    														<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
	    															<i class="ki-duotone ki-cross fs-2">
	    																<span class="path1"></span>
	    																<span class="path2"></span>
	    															</i>
	    														</span>
	    														<!--end::Remove-->
	    													</div>
	    													<!--end::Image input-->
	    													<!--begin::Hint-->
	    													<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
	    													<!--end::Hint-->
	    												</div>
	    												<!--end::Input group-->
	    												<!--begin::Input group-->
	    												<div class="fv-row mb-7">
	    													<!--begin::Label-->
	    													<label class="required fw-semibold fs-6 mb-2">Full Name</label>
	    													<!--end::Label-->
	    													<!--begin::Input-->
	    													<input type="text" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Full name" value="Emma Smith" />
	    													<!--end::Input-->
	    												</div>
	    												<!--end::Input group-->
	    												<!--begin::Input group-->
	    												<div class="fv-row mb-7">
	    													<!--begin::Label-->
	    													<label class="required fw-semibold fs-6 mb-2">Email</label>
	    													<!--end::Label-->
	    													<!--begin::Input-->
	    													<input type="email" name="user_email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" value="smith@kpmg.com" />
	    													<!--end::Input-->
	    												</div>
	    												<!--end::Input group-->
	    												<!--begin::Input group-->
	    												<div class="mb-5">
	    													<!--begin::Label-->
	    													<label class="required fw-semibold fs-6 mb-5">Role</label>
	    													<!--end::Label-->
	    													<!--begin::Roles-->
	    													<!--begin::Input row-->
	    													<div class="d-flex fv-row">
	    														<!--begin::Radio-->
	    														<div class="form-check form-check-custom form-check-solid">
	    															<!--begin::Input-->
	    															<input class="form-check-input me-3" name="user_role" type="radio" value="0" id="kt_modal_update_role_option_0" checked='checked' />
	    															<!--end::Input-->
	    															<!--begin::Label-->
	    															<label class="form-check-label" for="kt_modal_update_role_option_0">
	    																<div class="fw-bold text-gray-800">Administrator</div>
	    																<div class="text-gray-600">Best for business owners and company administrators</div>
	    															</label>
	    															<!--end::Label-->
	    														</div>
	    														<!--end::Radio-->
	    													</div>
	    													<!--end::Input row-->
	    													<div class='separator separator-dashed my-5'></div>
	    													<!--begin::Input row-->
	    													<div class="d-flex fv-row">
	    														<!--begin::Radio-->
	    														<div class="form-check form-check-custom form-check-solid">
	    															<!--begin::Input-->
	    															<input class="form-check-input me-3" name="user_role" type="radio" value="1" id="kt_modal_update_role_option_1" />
	    															<!--end::Input-->
	    															<!--begin::Label-->
	    															<label class="form-check-label" for="kt_modal_update_role_option_1">
	    																<div class="fw-bold text-gray-800">Developer</div>
	    																<div class="text-gray-600">Best for developers or people primarily using the API</div>
	    															</label>
	    															<!--end::Label-->
	    														</div>
	    														<!--end::Radio-->
	    													</div>
	    													<!--end::Input row-->
	    													<div class='separator separator-dashed my-5'></div>
	    													<!--begin::Input row-->
	    													<div class="d-flex fv-row">
	    														<!--begin::Radio-->
	    														<div class="form-check form-check-custom form-check-solid">
	    															<!--begin::Input-->
	    															<input class="form-check-input me-3" name="user_role" type="radio" value="2" id="kt_modal_update_role_option_2" />
	    															<!--end::Input-->
	    															<!--begin::Label-->
	    															<label class="form-check-label" for="kt_modal_update_role_option_2">
	    																<div class="fw-bold text-gray-800">Analyst</div>
	    																<div class="text-gray-600">Best for people who need full access to analytics data, but don't need to update business settings</div>
	    															</label>
	    															<!--end::Label-->
	    														</div>
	    														<!--end::Radio-->
	    													</div>
	    													<!--end::Input row-->
	    													<div class='separator separator-dashed my-5'></div>
	    													<!--begin::Input row-->
	    													<div class="d-flex fv-row">
	    														<!--begin::Radio-->
	    														<div class="form-check form-check-custom form-check-solid">
	    															<!--begin::Input-->
	    															<input class="form-check-input me-3" name="user_role" type="radio" value="3" id="kt_modal_update_role_option_3" />
	    															<!--end::Input-->
	    															<!--begin::Label-->
	    															<label class="form-check-label" for="kt_modal_update_role_option_3">
	    																<div class="fw-bold text-gray-800">Support</div>
	    																<div class="text-gray-600">Best for employees who regularly refund payments and respond to disputes</div>
	    															</label>
	    															<!--end::Label-->
	    														</div>
	    														<!--end::Radio-->
	    													</div>
	    													<!--end::Input row-->
	    													<div class='separator separator-dashed my-5'></div>
	    													<!--begin::Input row-->
	    													<div class="d-flex fv-row">
	    														<!--begin::Radio-->
	    														<div class="form-check form-check-custom form-check-solid">
	    															<!--begin::Input-->
	    															<input class="form-check-input me-3" name="user_role" type="radio" value="4" id="kt_modal_update_role_option_4" />
	    															<!--end::Input-->
	    															<!--begin::Label-->
	    															<label class="form-check-label" for="kt_modal_update_role_option_4">
	    																<div class="fw-bold text-gray-800">Trial</div>
	    																<div class="text-gray-600">Best for people who need to preview content data, but don't need to make any updates</div>
	    															</label>
	    															<!--end::Label-->
	    														</div>
	    														<!--end::Radio-->
	    													</div>
	    													<!--end::Input row-->
	    													<!--end::Roles-->
	    												</div>
	    												<!--end::Input group-->
	    											</div>
	    											<!--end::Scroll-->
	    											<!--begin::Actions-->
	    											<div class="text-center pt-10">
	    												<button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
	    												<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
	    													<span class="indicator-label">Submit</span>
	    													<span class="indicator-progress">Please wait... 
	    													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
	    						<!--end::Modal - Add task-->
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