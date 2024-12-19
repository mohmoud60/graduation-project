<?php
$required_permissions = ['permission_9', 'permission_14']; 

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
                    					            كشف عمليات خلال فترة
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
								<div id="kt_app_content_container" class="app-container container-xxl">
									<!--begin::Layout-->
									<div class="d-flex flex-column flex-xl-row">
										<!--begin::Sidebar-->
										<div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
											<!--begin::Card-->
											<div class="card mb-5 mb-xl-8 d-none" id="ditales">
											    <!--begin::Card body-->
											    <div class="card-body pt-15" id="total">
											        <!--begin::Summary-->
											        <div class="d-flex flex-center flex-column mb-5 ">
											            <h2 class="fw-bold" id="morning_total"></h2>
											            <h2 class="fw-bold" id="evening_total"></h2>
											            <h2 class="fw-bold" id="difference"></h2>
											            <h2 class="fw-bold" id="bonds_expense_total"></h2>
											            <h2 class="fw-bold" id="bonds_receipt_total"></h2>
											            <h2 class="fw-bold" id="total_prof"></h2>
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
                    										<div class=" col-md-5">
                    										    <label for="from_date" class="form-label fw-bold">اختر تاريخ التقرير</label>
                    										    <div class="input-group flex-nowrap">
                    										        <span class="input-group-text">
                    										            <i class="ki-duotone ki-calendar-2 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    										        </span>
                    										        <div class="overflow-hidden flex-grow-1">
																	<input class="form-control" placeholder="إختر من تاريخ" id="from_date"/>
                    										        </div>
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
        												    <h2>سجل العمليات</h2>
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
															<div class="table-responsive">
															<table class="table align-middle table-row-dashed gy-5" id="kt_table_company_report">
																<thead class="border-bottom border-gray-200 fs-7 fw-bold">
																	<tr class="text-start text-muted text-uppercase gs-0">
                                                                        <th class="w-5px">م.</th>
																		<th class="w-5px">رقم الفاتورة</th>
                                                                        <th class="w-5px">نوع العملية</th>
                                                                        <th class="w-125px">نوع العملة</th>
																		<th class="w-20px">الكمية</th>
																		<th class="w-5px">سعر الصرف</th>
																		<th class="w-20px">الإجمالي</th>
																		<th class="w-100px">التاريخ</th>
																	</tr>
																</thead>
																<tbody class="fs-6 fw-semibold text-gray-600">
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
		<script>
            $("#from_date").flatpickr();
            $("#to_date").flatpickr();
        </script>
		<script src="assets/js/js/report/daily_listing.js"></script>
        <script src="assets/js/js/report/daily_print.js"></script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>