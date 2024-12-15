<?php

include 'session_check.php';
// Include database connection file
include 'assets/php/connection.php';

// Check if traders_id is set in the URL
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM employee WHERE Employee_id = :employee_id");
    $stmt->execute([':employee_id' => $employee_id]);
    
    // Check if the query returns a row
    if($stmt->rowCount() > 0){
        // Fetch the data
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $full_name = isset($row['Employee_FullName']) ? $row['Employee_FullName'] : '';
        $employee_address = isset($row['Employee_Address']) ? $row['Employee_Address'] : '';
        $employee_phone = isset($row['Employee_Phone']) ? $row['Employee_Phone'] : '';
        $Employee_Email = isset($row['Employee_Email']) ? $row['Employee_Email'] : '';
        $job_titel = isset($row['job_titel']) ? $row['job_titel'] : '';
        $Salary = isset($row['Salary']) ? $row['Salary'] : '';
        $loan = isset($row['loan']) ? $row['loan'] : '';
    } else {
        echo 'No trader found with this ID.';
    }
} else {
    echo 'No traders_id specified in the URL.';
}

$imageBasePath = "assets/media/employee/$employee_id";
    $imageExtensions = ["png", "jpg", "jpeg", "gif"];

    $employeeImage = "assets/media/avatars/blank.png"; // Default image
    foreach ($imageExtensions as $ext) {
        $imagePath = "$imageBasePath.$ext";
        if (file_exists($imagePath)) {
            // Image exists, use it and stop searching
            $employeeImage = $imagePath;
            break;
        }
    }

// Fetch attachments
$upload_dir = 'assets/media/dollar/employee/';
$attachments = glob($upload_dir . $employee_id . '_*');

if (isset($_GET['action']) && $_GET['action'] == 'delete_attachment' && isset($_GET['attachment'])) {
    $attachment = $_GET['attachment'];
    $path = $upload_dir . $attachment;
    if (is_file($path) && unlink($path)) {
        header("Location: employee_veiw.php?employee_id=$employee_id");
        exit;
    } else {
        echo "Sorry, there was an error deleting your file.";
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
                    					             بيانات الموظف 
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
								<div class="d-flex align-items-center gap-2 gap-lg-3">
									<!--begin:: button-->
									<a class="btn btn-primary" id="advance-link">سلفة نقدية</a>
									<!--end:: button-->
								</div>
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
														<!-- begin::Avatar -->
                                                        <div class="symbol symbol-150px symbol-circle mb-7">
                                                            <img id="employe_avatar" src="<?php echo $employeeImage; ?>" alt="image" />
                                                        </div>
                                                        <!-- end::Avatar -->
                                                        <!--begin::id-->
														<a  class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1" id="employee_id">رقم الموظف : <?php echo $employee_id; ?></a>
														<!--end::id-->
														<!--begin::Name-->
														<a  class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1" id="full_name">اسم الموظف: <?php echo $full_name; ?></a>
														<!--end::Name-->
														<!--begin::phone-->
														<a  class="fs-5 fw-semibold text-muted text-hover-primary mb-6" id="employee_phone">رقم الهاتف :<?php echo $employee_phone; ?></a>
														<!--end::phone-->
														<!--begin::address-->
														<a  class="fs-5 fw-semibold text-muted text-hover-primary mb-6" id="employee_address">العنوان :<?php echo $employee_address; ?></a>
														<!--end::address-->
                                                        <!--begin::email-->
														<a  class="fs-5 fw-semibold text-muted text-hover-primary mb-6" id="Employee_Email">البريد الإلكتروني :<?php echo $Employee_Email; ?></a>
														<!--end::email-->
                                                         <!--begin::job_titel -->
														<a  class="fs-5 fw-semibold text-muted text-hover-primary mb-6" id="job_titel1">المسمى الوظيفي:<?php echo $job_titel; ?></a>
														<!--end::job_titel-->
                                                          <!--begin::Salary -->
														<a  class="fs-5 fw-semibold text-muted text-hover-primary mb-6" id="Salary1">الراتب:<?php echo $Salary ; ?> شيكل</a>
														<!--end::Salary-->
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
											<!--begin:::Tabs-->
											<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-2 fw-semibold mb-8">
                                                <li class="nav-item">
                                                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#KT_report">تقارير</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#KT_details">البيانات الشخصية</a>
                                                </li>
												<li class="nav-item">
                                                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#KT_passcode">الأمان</a>
                                                </li>
                                            </ul>
											<!--end:::Tabs-->
											<!--begin:::Tab content-->
											<div class="tab-content" id="myTabContent">
												<!--begin:::Tab pane-->
                                                <div class="tab-pane fade show active" id="KT_report" role="tabpanel">
													<!--begin::Filters-->
													<div class="card mb-6 mb-xl-9">
													    <!--begin::Header-->
													    <div class="card-header border-0">
													    </div>
													    <!--end::Header-->
													    <!--begin::Body-->
                                                        <div class="me-2 row ms-2 justify-content-center">
                                                            <div class="col-md-4">
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
                                                            <div class="col-md-4">
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
													        <button type="button" class="btn btn-primary" id="show_report">عرض البيان المالي</button>
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
        												    <h2>سجل البيان المالي</h2>
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
															<table class="table align-middle table-row-dashed gy-5" id="kt_table_employee_payment">
																<thead class="border-bottom border-gray-200 fs-7 fw-bold">
																	<tr class="text-start text-muted text-uppercase gs-0">
                                                                        <th class="min-w-100px">م.</th>
																		<th class="min-w-100px">البيان</th>
																		<th class="min-w-100px">المبلغ</th>
																		<th class="min-w-100px">التاريخ</th>
																		<th class="min-w-100px">الساعة</th>
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
                                                    <div class="tab-pane fade" id="KT_details" role="tabpanel">

                                                    <!--begin::update_details-->
                                                    <div class="card mb-6 mb-xl-9">
                                                        
                                                        <!--begin::Body-->
                                                        <div class="card-body">
                                                            <!-- Employee data form -->
                                                            <form id="update_employee_form" data-employee-id="<?php echo $employee_id; ?>" enctype="multipart/form-data">
                                                                <div class="mb-3">
                                                                    
                                                                    <label for="full_name"  class="required form-label">اسم الموظف</label>
                                                                    <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text">
                                                                        <i class="ki-duotone ki-user-square fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $full_name; ?>">
                                                                </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="Employee_Email" class="required form-label">البريد الإلكتروني</label>
                                                                    <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text">
                                                                    <i class="bi bi-envelope-check"></i>
                                                                    </span>
                                                                    <input type="email" class="form-control" id="Employee_Email" name="Employee_Email" value="<?php echo $Employee_Email; ?>">
                                                                </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="employee_phone" class="required form-label">رقم الهاتف</label>
                                                                    <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text">
                                                                    <i class="bi bi-phone-fill"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" id="employee_phone" name="employee_phone" value="<?php echo $employee_phone; ?>">
                                                                </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="employee_address" class="required form-label">العنوان</label>
                                                                    <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text">
                                                                    <i class="bi bi-geo-alt"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" id="employee_address" name="employee_address" value="<?php echo $employee_address; ?>">
                                                                </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="job_title" class="required form-label">المسمى الوظيفي</label>
                                                                    <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text">
                                                                        <i class="ki-duotone ki-subtitle fs-3"><span class="path1"></span><span class="path2"></span></span><span class="path3"></span></span><span class="path4"></span></span><span class="path5"></span></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" id="job_title" name="job_title" value="<?php echo $job_titel; ?>">
                                                                </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="basic_salary" class="required form-label">الراتب الأساسي</label>
                                                                    <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text">
                                                                    <i class="bi bi-cash-coin"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" id="basic_salary" name="basic_salary" value="<?php echo $Salary; ?>">
                                                                </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                <label for="attachments" class="form-label">مرفقات</label>
                                                                <div class="input-group flex-nowrap">
                                                                    <span class="input-group-text">
                                                                        <i class="ki-duotone ki-add-folder"><i class="path1"></i><i class="path2"></i></i>
                                                                    </span>
                                                                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                                                                </div>
                                                                <?php foreach ($attachments as $attachment): ?>
                                                                    <div class="mt-2 d-flex justify-content-between align-items-center border p-2">
                                                                    <a class="btn btn-link flex-grow-1" href="<?php echo $attachment; ?>" target="_blank">
                                                                      <?php echo basename($attachment); ?>
                                                                    </a>

                                                                        <a class="btn btn-danger btn-sm" href="employee_veiw.php?employee_id=<?php echo $employee_id; ?>&action=delete_attachment&attachment=<?php echo urlencode(basename($attachment)); ?>">
                                                                          حذف
                                                                        </a>

                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                                <!--begin::Footer-->
                                                                <div class="card-footer text-center">
                                                            <button type="submit" class="btn btn-primary" id="update_daitals">تحديث البيانات الشخصية</button>
                                                        </div>
                                                        <!--end::Footer-->
                                                            </form>
                                                        </div>
                                                        <!--end::Body-->
                                                       
                                                    </div>
                                                    <!--end::update_details-->

                                                    </div>
													<div class="tab-pane fade" id="KT_passcode" role="tabpanel">
														 <!--begin::update_details-->
														 <div class="card mb-6 mb-xl-9">
                                                        
                                                        <!--begin::Body-->
                                                        <div class="card-body">
                                		<!--begin::Sign-in Method-->
											<div class="card mb-5 mb-xl-10">
												<!--begin::Card header-->
												<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_signin_method">
													<div class="card-title m-0">
														<h3 class="fw-bold m-0">الأمان</h3>
													</div>
												</div>
												<!--end::Card header-->
												<!--begin::Content-->
												<div id="kt_account_settings_signin_method" class="collapse show">
													<!--begin::Card body-->
													<div class="card-body border-top p-9">
														<!--begin::Password-->
														<div class="d-flex flex-wrap align-items-center mb-10">
															<!--begin::Label-->
															<div id="kt_signin_password">
																<div class="fs-6 fw-bold mb-1">كلمة السر</div>
																<div class="fw-semibold text-gray-600">************</div>
															</div>
															<!--end::Label-->
															<!--begin::Edit-->
															<div id="kt_signin_password_edit" class="flex-row-fluid d-none">
																<!--begin::Form-->
																<form id="kt_signin_change_password" class="form" novalidate="novalidate">
																<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
																	<div class="row mb-1">
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
																<!--end::Form-->
															</div>
															<!--end::Edit-->
															<!--begin::Action-->
															<div id="kt_signin_password_button" class="ms-auto">
																<button class="btn btn-light btn-active-light-primary">إعادة التعين</button>
															</div>
															<!--end::Action-->
														</div>
														<!--end::Password-->
													</div>
													<!--end::Card body-->
												</div>
												<!--end::Content-->
											</div>
											<!--end::Sign-in Method-->
                                                        </div>
                                                        <!--end::Body-->
                                                       
                                                    </div>
                                                    <!--end::update_details-->
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
										<div class="modal fade" id="add_advances" tabindex="-1" aria-hidden="true">
										<!--begin::Modal dialog-->
										<div class="modal-dialog modal-dialog-centered mw-650px">
											<!--begin::Modal content-->
											<div class="modal-content">
												<!--begin::Form-->
												<form class="form" action="#" id="add_advances_form" data-kt-redirect="<?php echo 'employee_veiw.php?employee_id=' . $_GET['employee_id']; ?>">
													<!--begin::Modal header-->
                                                    <div class="modal-header d-flex justify-content-between align-items-center" id="add_advances_header">
                                                        <!--begin::Modal title-->
                                                        <h2 class="fw-bold m-auto">سلفة نقدية</h2>
                                                        <!--end::Modal title-->
                                                        <!--begin::Close-->
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                        </div>
                                                        <!--end::Close-->
                                                    </div>
                                                    <!--end::Modal header-->

												<!--begin::Modal body-->
                                                <div class="modal-body py-10 px-lg-17">
                                                    <!--begin::Scroll-->
                                                    <div class="scroll-y me-n7 pe-7" id="add_advances_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#add_advances_header" data-kt-scroll-wrappers="#add_advances_scroll" data-kt-scroll-offset="300px">
                                                        <!--begin::Input group-->
                                                        <div class="col-md-8 mb-3">
                                                            <!--begin::Label-->
                                                            <label class="fs-2 fw-semibold mb-2 ">الراتب الأساسي : <span class="text-success" ><?php echo $Salary ; ?> ₪ </span></label>
                                                            <!--end::Label-->
                                                            <!--begin::Label-->
                                                            <label class="fs-2 fw-semibold mb-2 " id="loan2">السلف السابقة  : <span class="text-danger" ><?php echo $loan ; ?> ₪ </span></label>
                                                            <!--end::Label-->
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <!--begin::Label-->
                                                                <label class="fs-6 fw-semibold mb-2" for="amount">
                                                                    <span class="required">قيمة السلفة</span>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <!--begin::Input-->
                                                                <input type="number" class="form-control" placeholder="" name="amount" id="amount" />
                                                            </div>
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                    <!--end::Scroll-->
                                                </div>
                                                <!--end::Modal body-->

													<!--begin::Modal footer-->
													<div class="modal-footer flex-center">
														<!--begin::Button-->
														<button type="reset" id="add_advances_cancel" class="btn btn-light me-3">إلغاء</button>
														<!--end::Button-->
														<!--begin::Button-->
														<button type="submit" id="add_advances_submit" class="btn btn-primary">
															<span class="indicator-label">دفع</span>
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
									<input type="hidden" id="getsalary" value="<?php echo $Salary; ?>">
                                    <input type="hidden" id="getloan" value="<?php echo $loan; ?>">
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
        <script>
            $("#from_date").flatpickr();
            $("#to_date").flatpickr();
			



        </script>
		<script src="assets/js/js/employee_over.js"></script>
		<script src="assets/js/js/employee/view/add.js"></script>
		<script src="assets/js/js/employee/view/listing.js"></script>
		<script src="assets/js/js/employee/view/print.js"></script>
        <script src="assets/js/js/employee/view/update.js"></script>
		<script src="assets/js/js/employee/view/update_password.js"></script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>