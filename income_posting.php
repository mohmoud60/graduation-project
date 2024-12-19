<?php
$required_permission = 'permission_9';
include 'session_check.php';

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
                    					            <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">
                    					            تقرير ترحيل الإيرادات
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
								</div>
								<!--end::Actions-->
								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container ">
									<!--begin::Layout-->
									<div class="d-flex flex-column flex-xl-row ">
										<!--begin::Content-->
										<div class="flex-lg-row-fluid ms-lg-15">
											<!--begin:::Tabs-->
											<ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
												
											</ul>
											<!--end:::Tabs-->
											<!--begin:::Tab content-->
											<div class="tab-content" id="myTabContent">

													<!--begin::Filters-->
													<div class="card mb-6 mb-xl-9">
													    <!--begin::Header-->
													    <div class="card-header border-0">
													      
													    </div>
													    <!--end::Header-->
													    <!--begin::Body-->
													    <div class="me-2 row ms-3">
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
														<div class="col-md-4">
													    <label for="search" class="form-label fw-bold ">البحث</label>
													    <div class="input-group flex-nowrap">
													        <span class="input-group-text">
													            <i class="ki-duotone ki-magnifier fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
													        </span>
													        <input class="form-control" placeholder="إبحث هنا" id="search"/>
													    </div>
														</div>

													</div>
													<div class="card-footer text-center">
													    <button type="button" class="btn btn-primary" id="show_report">عرض التقرير</button>
													</div>

													    <!--end::Footer-->
													</div>
													<!--end::Filters-->
                                                    
													<!--begin::Card-->
													<div class="card pt-4 mb-6 mb-xl-8 ">
														<!--begin::Card header-->
														<div class="card-header border-0">
															<!--begin::Card title-->
															<div class="card-title">
        												    <h2>سجل ترحيل الإيرادات</h2>
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
														<div class="card-body pt-0 pb-5 ">
														<div class="table-responsive">
															<!--begin::Table-->
															<div class="table-responsive">
															<table class="table align-middle table-row-dashed gy-5" id="kt_table_company_report">
																<thead class="border-bottom border-gray-200 fs-7 fw-bold">
																	<tr class="text-start text-muted text-uppercase gs-0">
                                                                        <th class="min-w-100px">تحويل رقم</th>
                                                                        <th class="min-w-100px">قيمة شيكل </th>
																		<th class="min-w-100px">قيمة دولار أمريكي</th>
																		<th class="min-w-100px">التاريخ</th>
                                                                        <th class="min-w-100px">الترحيل بواسطة</th>
																	</tr>
																</thead>
																<tbody class="fs-7 fw-semibold text-gray-600">
																</tbody>
															</table>
															</div>
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
		<!--end::Custom Javascript-->
		<script src="assets/js/js/employee_over.js"></script>
		<script>
            $("#from_date").flatpickr();
            $("#to_date").flatpickr();
        </script>
		<script src="assets/js/js/report/post_income_listing.js"></script>
        <script src="assets/js/js/report/post_income_print.js"></script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>