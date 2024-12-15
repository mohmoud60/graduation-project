<?php
include 'session_check.php';

$employee_id = $_SESSION['employee_id'];

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
        <div class="container mt-5">
            <!--begin::Underline-->
            <span class="d-inline-block position-relative ms-2">
                <span class="d-inline-block mb-2 fs-2tx fw-bold">الملف الشخصي</span>
                <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
            </span>
            <!--end::Underline-->

            <div id="kt_app_content" class="app-content flex-column-fluid mt-5">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <!--begin::Navbar-->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-body pt-9 pb-0">
                            <div class="d-flex flex-wrap flex-sm-nowrap">
                                <div class="me-7 mb-4">
                                    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                        <img id="user_avatar3" src="assets/media/avatars/blank.png" alt="image" />
                                        <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-2">
                                                <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1" id="FFullName" name="FFullName"></a>
                                            </div>
                                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2" id="Fjob_titel" name="Fjob_titel"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#KT_overview">الملف الشخصي</a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#KT_profileset">إعدادات</a>
                                </li>
                                <li class="nav-item mt-2">
                                    <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#KT_security">الأمان</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end::Navbar-->

                    <!--begin::Tabs content-->
                    <div class="tab-content">
                        <!--begin::Overview tab-->
                        <div class="tab-pane fade show active" id="KT_overview" role="tabpanel">
                            <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
                                <div class="card-header cursor-pointer">
                                    <div class="card-title m-0">
                                        <h3 class="fw-bold m-0">البيانات الشخصية</h3>
                                    </div>
                                </div>
                                <div class="card-body p-9">
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">الاسم كامل:</label>
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800" id="EFullName" name="EFullName"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">البريد الإلكتروني:</label>
                                        <div class="col-lg-8">
                                            <span class="fw-bold fs-6 text-gray-800" id="Eemail" name="Eemail"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">العنوان:</label>
                                        <div class="col-lg-8">
                                            <span class="fw-semibold text-gray-800 fs-6" id="address" name="address"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">أرقام التواصل :
                                                <span class="ms-1" data-bs-toggle="tooltip" title="يجب أن يكون رقم الهاتف نشطًا">
													<i class="ki-duotone ki-information fs-7">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
													</i>
												</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <span class="fw-semibold text-gray-800 fs-6" id="phone" name="phone"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">الراتب الشهري :</label>
                                        <div class="col-lg-8">
                                            <span class="fw-semibold text-gray-800 fs-6" id="Salary" name="Salary"></span>
                                        </div>
                                    </div>
                                    <div class="row mb-7">
                                        <label class="col-lg-4 fw-semibold text-muted">المسمى الوظيفي :</label>
                                        <div class="col-lg-8">
                                            <span class="fw-semibold text-gray-800 fs-6" id="job_titel" name="job_titel"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Overview tab-->

                        <!--begin::Profile settings tab-->
                        <div class="tab-pane fade" id="KT_profileset" role="tabpanel">
                            <div class="card mb-5 mb-xl-10">
                                <div class="card-header">
                                    <div class="card-title m-0">
                                        <h3 class="fw-bold m-0">البيانات الشخصية</h3>
                                    </div>
                                </div>
                                <div class="card-body border-top p-9">
                                    <form id="kt_account_profile_details_form" class="form">
                                        <!--begin::Input group-->
										<div class="row mb-6">
											<!--begin::Label-->
											<label class="col-lg-4 col-form-label fw-semibold fs-6">الصورة الشخصية</label>
											<!--end::Label-->
											<!--begin::Col-->
											<div class="col-lg-8">
												<!--begin::Image input-->
												<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/avatars/blank.svg')">
													<!--begin::Preview existing avatar-->
													<div id="avatar-wrapper" class="image-input-wrapper w-125px h-125px" style="background-image: url('assets/media/avatars/blank.png')"></div>
													<!--end::Preview existing avatar-->
													<!--begin::Label-->
													<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="تغير الصورة">
														<i class="ki-duotone ki-pencil fs-7">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
														<!--begin::Inputs-->
														<input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
														<input type="hidden" name="avatar_remove" value="false" />
														<!--end::Inputs-->
													</label>
													<!--end::Label-->
													<!--begin::Cancel-->
													<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="حذف الصورة">
														<i class="ki-duotone ki-cross fs-2">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</span>
													<!--end::Cancel-->
													<!--begin::Remove-->
													<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="حذف الصورة">
														<i class="ki-duotone ki-cross fs-2">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</span>
													<!--end::Remove-->
												</div>
												<!--end::Image input-->
												<!--begin::Hint-->
												<div class="form-text">أنواع الملفات المسموح بها: png، jpg، jpeg.</div>
												<!--end::Hint-->
											</div>
											<!--end::Col-->
										</div>
										<!--end::Input group-->

                                        <div class="row mb-6">
                                            <label class="col-lg-4 col-form-label fw-semibold fs-6">الاسم كامل:</label>
                                            <div class="col-lg-8 fv-row">
                                                <input type="text" id="PFullName" name="PFullName" class="form-control form-control-lg form-control-solid" placeholder="الاسم كامل" />
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <label class="col-lg-4 col-form-label fw-semibold fs-6">البريد الإلكتروني:</label>
                                            <div class="col-lg-8 fv-row">
                                                <input type="text" id="Pemail" name="Pemail" class="form-control form-control-lg form-control-solid" placeholder="البريد الإلكتروني" />
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <label class="col-lg-4 col-form-label fw-semibold fs-6">عنوان السكن:</label>
                                            <div class="col-lg-8 fv-row">
                                                <input type="text" id="Paddress" name="Paddress" class="form-control form-control-lg form-control-solid" placeholder="البريد الإلكتروني" />
                                            </div>
                                        </div>
                                        <div class="row mb-6">
                                            <label class="col-lg-4 col-form-label fw-semibold fs-6">أرقام التواصل :</label>
                                            <div class="col-lg-8 fv-row">
                                                <input type="text" id="Pphone" name="Pphone" class="form-control form-control-lg form-control-solid" placeholder="البريد الإلكتروني" />                                        
                                            </div>
                                        </div>
										<div class="card-footer d-flex justify-content-end py-6 px-9">
											<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">حفظ التعديلات</button>
										</div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--end::Profile settings tab-->

                        <!--begin::security settings tab-->
                        <div class="tab-pane fade" id="KT_security" role="tabpanel">
                            <div class="card mb-5 mb-xl-10">
						        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
							        <div class="card-title m-0">
							    	    <h3 class="fw-bold m-0">الأمان</h3>
							        </div>
						        </div>
						        <div id="kt_account_settings_signin_method" class="collapse show">
						        	<div class="card-body border-top p-9">
						        		<div class="d-flex flex-wrap align-items-center mb-10">
						        			<div id="kt_signin_password">
						        				<div class="fs-6 fw-bold mb-1">كلمة السر</div>
						        				<div class="fw-semibold text-gray-600">************</div>
						        			</div>
						        			<div id="kt_signin_password_edit" class="flex-row-fluid d-none">
						        				<form id="kt_signin_change_password" class="form" novalidate="novalidate">
						        				<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
						        					<div class="row mb-1">
						        						<div class="col-lg-4">
						        							<div class="fv-row mb-0">
						        								<label for="currentpassword" class="form-label fs-6 fw-bold mb-3">كلمة المرور الحالية</label>
						        								<input type="password" class="form-control form-control-lg form-control-solid" name="currentpassword" id="currentpassword" />
						        							</div>
						        						</div>
						        						<div class="col-lg-4">
						        							<div class="fv-row mb-0">
						        								<label for="newpassword" class="form-label fs-6 fw-bold mb-3">كلمة المرور الجديدة</label>
						        								<input type="password" class="form-control form-control-lg form-control-solid" name="newpassword" id="newpassword" />
						        							</div>
						        						</div>
						        						<div class="col-lg-4">
						        							<div class="fv-row mb-0">
						        								<label for="confirmpassword" class="form-label fs-6 fw-bold mb-3">تأكيد كلمة المرور الجديدة</label>
						        								<input type="password" class="form-control form-control-lg form-control-solid" name="confirmpassword" id="confirmpassword" />
						        							</div>
						        						</div>
						        					</div>
						        					<div class="form-text mb-5">يجب أن تتكون كلمة المرور من 8 أحرف على الأقل وتحتوي على رموز</div>
						        					<div class="d-flex">
						        						<button id="kt_password_submit" type="button" class="btn btn-primary me-2 px-6">تحديث كلمة المرور</button>
						        						<button id="kt_password_cancel" type="button" class="btn btn-color-gray-500 btn-active-light-primary px-6">إلغاء</button>
						        					</div>
						        				</form>
						        			</div>
						        			<div id="kt_signin_password_button" class="ms-auto">
						        				<button class="btn btn-light btn-active-light-primary">إعادة التعين</button>
						        			</div>
						        		</div>
						        	</div>
						        </div>
						    </div>
                        </div>
                        <!--end::security settings tab-->

                    </div>
                    <!--end::Tabs content-->
                </div>
                <!--end::Content container-->
            </div>
        </div>
        <!--end::Main-->

        <!-- Scripts -->
        <script>var hostUrl = "assets/";</script>
        <script src="assets/plugins/global/plugins.bundle.js"></script>
        <script src="assets/js/custom/account/settings/profile-details.js"></script>
        <script src="assets/js/scripts.bundle.js"></script>
        <script src="assets/js/widgets.bundle.js"></script>
        <script src="assets/js/js/employee_over.js"></script>
        <script src="assets/js/js/security.js"></script>
    </body>
    <!--end::Body-->
</html>
