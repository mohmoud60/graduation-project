<?php
$required_permission = 'permission_10';
include 'session_check.php';
// Include database connection file
include 'assets/php/connection.php';

$query = $conn->prepare("SELECT * FROM currency WHERE Delete_Date IS NULL");
$query->execute();
$main_currencies = $query->fetchAll(PDO::FETCH_ASSOC);


// Check if traders_id is set in the URL
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt1 = $conn->prepare("SELECT * FROM customer WHERE customer_id = :customer_id");
    $stmt1->execute([':customer_id' => $customer_id]);
    
    // Check if the query returns a row
    if($stmt1->rowCount() > 0){
        // Fetch the data
        $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    
        $full_name = isset($row['full_name']) ? $row['full_name'] : '';
        $customer_address = isset($row['customer_address']) ? $row['customer_address'] : '';
        $customer_phone = isset($row['customer_phone']) ? $row['customer_phone'] : '';
    } else {
        echo 'No trader found with this ID.';
    }
} else {
    echo 'No traders_id specified in the URL.';
}

// Prepare an array to store the balances
$balances = [];

$sql = "SELECT currency_id, currency_sname, currency_symbole FROM currency";
$stmt = $conn->prepare($sql);
$stmt->execute();
$currencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($currencies as $currency) {
    $currency_id = $currency['currency_id'];
    $currency_sname = $currency['currency_sname'];
    $currency_symbole = $currency['currency_symbole'];
    
    $sql_deposit = "SELECT SUM(tr_amount) AS sum FROM customer_transaction WHERE customer_id = :customer_id AND tr_type = 'deposit' AND tr_currency = :currency_id AND Delete_Date IS NULL";
	$sql_withdraw = "SELECT SUM(tr_amount) AS sum FROM customer_transaction WHERE customer_id = :customer_id AND tr_type = 'withdraw' AND tr_currency = :currency_id AND Delete_Date IS NULL";


    $params = [
        ':customer_id' => $customer_id,
        ':currency_id' => $currency_id
    ];

    try {
        // Fetch the deposit sum
        $stmt = $conn->prepare($sql_deposit);
        $stmt->execute($params);
        $deposit_sum = $stmt->fetch(PDO::FETCH_ASSOC)['sum'] ?? 0;

        // Fetch the withdraw sum
        $stmt = $conn->prepare($sql_withdraw);
        $stmt->execute($params);
        $withdraw_sum = $stmt->fetch(PDO::FETCH_ASSOC)['sum'] ?? 0;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Calculate the balance
    $balance = $deposit_sum - $withdraw_sum;

    // Store the balance and currency name in the array
    $balances[] = ['currency_name' => $currency_sname, 'currency_symbol' => $currency_symbole, 'balance' => $balance];
}



//////////
    $query_currency_sname = "
        SELECT 
            c.currency_sname, 
            ct.tr_currency, 
            COUNT(ct.tr_currency) AS currency_count 
        FROM 
            customer_transaction ct
        JOIN 
            currency c 
        ON 
            c.currency_id = ct.tr_currency
        GROUP BY 
            ct.tr_currency
    ";
    
    // تنفيذ الاستعلام
    $currency_sname = $conn->prepare($query_currency_sname);
    $currency_sname->execute();
    $results = $currency_sname->fetchAll(PDO::FETCH_ASSOC);

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
                    					             بيانات الزبون 
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
									<a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_transaction">إضافة تحويل جديد</a>
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
														<!--begin::Avatar-->
														<div class="symbol symbol-150px symbol-circle mb-7">
															<img src="assets/media/avatars/blank.png" alt="image" />
														</div>
														<!--end::Avatar-->
                                                        <!--begin::id-->
														<a  class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1" id="customer_id">رقم التاجر : <?php echo $customer_id; ?></a>
														<!--end::id-->
														<!--begin::Name-->
														<a  class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1" id="full_name">اسم التاجر: <?php echo $full_name; ?></a>
														<!--end::Name-->
														<!--begin::phone-->
														<a  class="fs-5 fw-semibold text-muted text-hover-primary mb-6" id="customer_phone">رقم الهاتف :<?php echo $customer_phone; ?></a>
														<!--end::phone-->
														<!--begin::address-->
														<a  class="fs-5 fw-semibold text-muted text-hover-primary mb-6" id="customer_address">العنوان :<?php echo $customer_address; ?></a>
														<!--end::address-->
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
												<!--begin:::Tab pane-->
													<div class="row row-cols-1 row-cols-md-2 mb-6 mb-xl-9">
														<div class="col">
															<!--begin::Card-->
															<div class="card pt-4 h-md-100 mb-6 mb-md-0">
															    <!--begin::Card header-->
															    <div class="card-header border-0 d-flex justify-content-center align-items-center">
															        <!--begin::Card title-->
															        <div class="card-title text-center">
															            <i class="ki-duotone ki-dollar text-info fs-2x">
															                <i class="path1"></i>
															                <i class="path2"></i>
															                <i class="path3"></i>
															            </i>
															            <h2 class="fw-bold">إجمالي الرصيد</h2>
																		<i class="ki-duotone ki-dollar text-info fs-2x">
															                <i class="path1"></i>
															                <i class="path2"></i>
															                <i class="path3"></i>
															            </i>
															        </div>
															        <!--end::Card title-->
															    </div>
															    <!--end::Card header-->
															    <!--begin::Card body-->
															    <div class="card-body pt-0">
															        <div class="fw-bold fs-2">
															            <div class="d-flex">
															                <i class="ki-duotone ki-bill text-info fs-2x">
															                    <i class="path1"></i>
															                    <i class="path2"></i>
															                    <i class="path3"></i>
															                    <i class="path4"></i>
															                    <i class="path5"></i>
															                    <i class="path6"></i>
															                </i>
															                <div class="ms-2">
																			    <a class="text-muted fs-4 fw-semibold" >الرصيد الحالي :</a><br>
																			    <?php foreach ($balances as $balance_info): ?>
																					<?php 
    if ($balance_info['balance'] != 0) {
        $balance = number_format(abs($balance_info['balance']), 2);
        $balanceText = $balance_info['currency_name'] . ': ';
        $balanceDirection = ($balance_info['balance'] < 0) ? 'لنا' : 'لكم';

        echo '<span class="text-muted fs-4 fw-semibold" data-balance-direction="' . $balanceDirection . '" data-balance-amount="' . $balance . ' ' .  $balance_info['currency_symbol'] . '"data-balance-symbol= " ' . $balance_info['currency_name'] . '" id="balunce">';
        echo $balanceText;
        if ($balance_info['balance'] < 0) {
            echo '<span class="text-danger" > سحب '  . $balance . ' ' .  $balance_info['currency_symbol'] . ' </span>';
        } else {
            echo '<span class="text-success"> إيداع ' . $balance .' ' .  $balance_info['currency_symbol'] . ' </span>';
        }
        echo '</span><br>';
    }
?>
																			    <?php endforeach; ?>
																			</div>
																			<i class="bi  fs-3x bi-clipboard-plus" id="clipboard"></i>

															            </div>
															        </div>
															    </div>
															    <!--end::Card body-->
															</div>
															<!--end::Card-->

														</div>
														<div class="col" id="traders_card" style="display: none;">
															<!--begin::Reward Tier-->
															<a class="card bg-whit hoverable h-md-100">
																<!--begin::Body-->
															<div class="card-body">
																<div class="card-header border-0 d-flex justify-content-center align-items-center">
															        <!--begin::Card title-->
															        <div class="card-title text-center">
															            <i class="ki-duotone ki-dollar text-info fs-2x">
															                <i class="path1"></i>
															                <i class="path2"></i>
															                <i class="path3"></i>
															            </i>
															            <h2 class="fw-bold">الرصيد خلال الفترة</h2>
																		<i class="ki-duotone ki-dollar text-info fs-2x">
															                <i class="path1"></i>
															                <i class="path2"></i>
															                <i class="path3"></i>
															            </i>
															        </div>
															        <!--end::Card title-->
															    </div>
															    <!--end::Card header-->
																
																<!--begin::Table-->
																<div class="table-responsive">
																<table class="table align-middle table-row-dashed gy-5" id="kt_table_traders_show">
																	<thead class="border-bottom border-gray-200 fs-7 fw-bold">
																		<tr class="text-start text-muted text-uppercase gs-0">
																			<th class="min-w-75px">العملة</th>
																			<th class="min-w-75px">إيداع</th>
																			<th class="min-w-75px">سحب</th>
																			<th class="min-w-75px">إيداع - سحب</th>
																		</tr>
																	</thead>
																	<tbody class="fs-6 fw-semibold text-gray-600">
																	</tbody>
																</table>
																</div>
																<!--end::Table-->
															</div>

																<!--end::Body-->
															</a>
															<!--end::Reward Tier-->
														</div>
													</div>
													</div>
													<!--begin::Filters-->
													<div class="card mb-6 mb-xl-9">
													    <!--begin::Header-->
													    <div class="card-header border-0">
													      
													    </div>
													    <!--end::Header-->
													    <!--begin::Body-->
													    <div class="me-2 row ms-2">
														 <!-- من تاريخ -->
                    										<div class=" col-md-3">
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
															
															   <!-- إلى تاريخ -->
                    										<div class="col-md-3">
                    										    <label for="to_date" class="form-label fw-bold ">إلى تاريخ</label>
                    										    <div class="input-group flex-nowrap">
                    										        <span class="input-group-text">
                    										            <i class="ki-duotone ki-calendar-2 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                    										        </span>
                    										        <input class="form-control" placeholder="إختر إلى تاريخ" id="to_date"/>

                    										</div>
                    										</div>
															<!-- نوع العملية -->
                    										<div class="col-md-3">
                    										    <label for="tr-types" class="form-label fw-bold ">نوع العملية</label>
                    										    <div class="input-group flex-nowrap">
                    										     <span class="input-group-text">
                    										         <i class="ki-duotone ki-up-down fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    										     </span>
                    										    <select class="form-select" id="tr-types" required>
																	<option value="">إختر نوع العملية</option>
                    										        <option value="deposit">إيداع</option>
                    										        <option value="withdraw">سحب</option>
																	<option value="both">سحب-إيداع</option>
                    										    </select>
                    										</div>
															</div>
															<!-- الصندوق -->
															<div class="col-md-3">
																<label for="fund-types" class="form-label fw-bold">الصندوق</label>
																<div class="input-group flex-nowrap">
																	<span class="input-group-text">
																		<i class="ki-duotone ki-up-down fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
																	</span>
																	<select class="form-select" id="fund-types" required>
																		<option value="">إختر الصندوق</option>
																		<?php foreach ($results as $result): ?>
																			<option value="<?= htmlspecialchars($result['tr_currency']) ?>">
																				<?= htmlspecialchars($result['currency_sname']) ?>
																			</option>
																		<?php endforeach; ?>
																	</select>
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
														        <h2>سجل التحويلات</h2>
														    </div>
														    <!--end::Card title-->
														    <!--begin::Card toolbar-->
														    <div class="card-toolbar d-flex justify-content-between">
														        <!--begin::Search bar-->
														        <div class="col-md-8">
														            <div class="input-group flex-nowrap">
														                <span class="input-group-text">
														                    <i class="ki-duotone ki-magnifier fs-3">
														                        <span class="path1"></span>
														                        <span class="path2"></span>
														                        <span class="path3"></span>
														                        <span class="path4"></span>
														                        <span class="path5"></span>
														                    </i>
														                </span>
														                <input class="form-control" placeholder="إبحث هنا" id="search"/>
														            </div>
														        </div>
														        <!--end::Search bar-->
																																			
														        <!--begin::Print button-->
														        <div>
														            <a class="btn btn-sm btn-flex btn-primary me-2" id="exp_print" onclick="printData();">
														                <i class="ki-duotone ki-printer fs-3">
														                    <span class="path1"></span>
														                    <span class="path2"></span>
														                    <span class="path3"></span>
														                    <span class="path4"></span>
														                    <span class="path5"></span>
														                </i>
														                طباعة
														            </a>
														        </div>
														        <!--end::Print button-->
														    </div>
														    <!--end::Card toolbar-->
														</div>
														<!--end::Card header-->

														<!--begin::Card body-->
														<div class="card-body pt-0 pb-5">
														<div class="table-responsive">
															<!--begin::Table-->
															<table class="table align-middle table-row-dashed gy-5" id="kt_table_traders_payment">
																<thead class="border-bottom border-gray-200 fs-7 fw-bold">
																	<tr class="text-start text-muted text-uppercase gs-0">
																		
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
										<div class="modal fade" id="add_transaction" tabindex="-1" aria-hidden="true">
										<!--begin::Modal dialog-->
										<div class="modal-dialog modal-dialog-centered mw-650px">
											<!--begin::Modal content-->
											<div class="modal-content">
												<!--begin::Form-->
												<form class="form" action="#" id="add_transaction_form" data-kt-redirect="<?php echo 'customer_veiw.php?customer_id=' . $_GET['customer_id']; ?>">
													<!--begin::Modal header-->
													<div class="modal-header" id="add_transaction_header">
														<!--begin::Modal title-->
														<h2 class="fw-bold">عملية تحويل جديدة</h2>
														<!--end::Modal title-->
														<!--begin::Close-->
														<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                										    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                										</div>
                										<!--end::Close-->
													</div>
													<!--end::Modal header-->
													<!--begin::Modal body-->
													<div class="modal-body py-10 px-lg-17">
														<!--begin::Scroll-->
														<div class="scroll-y me-n7 pe-7" id="add_transaction_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#add_transaction_header" data-kt-scroll-wrappers="#add_transaction_scroll" data-kt-scroll-offset="300px">
															<!--begin::Input group-->
															<div class="fv-row mb-7">
																<!--begin::Label-->
																<label class="required fs-6 fw-semibold mb-2" for="transaction_type">نوع العملية</label>
																<!--end::Label-->
																<!--begin::Input-->
																<select class="form-select" id="transaction_type" name="transaction_type">
            													  <option selected>إختر...</option>
            													  <option value="deposit">إيداع</option>
            													  <option value="withdraw">سحب</option>
            													</select>
																<!--end::Input-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="row">
																<div class="col-md-6 mb-3">
																	<!--begin::Label-->
																	<label class="fs-6 fw-semibold mb-2" for="amount">
																		<span class="required">المبلغ</span>
																	</label>
																	<!--begin::Input-->
																	<input type="number" class="form-control" placeholder="" name="amount" id="amount" />
																</div>
																	<div class="col-md-6 mb-3">
            														  <label for="currency" class="form-label required">صندوق العملات</label>
            														  <select class="form-select" id="currency" name="currency" data-allow-clear="true" data-dropdown-parent="#add_transaction" data-control="select2" data-placeholder="حدد خيارا">
            														    <option><option>
																		<?php
                                                                    foreach ($main_currencies as $currency) {
                                                                        echo "<option value=\"{$currency['currency_id']}\">{$currency['currency_sname']}</option>";
                                                                    }
                                                                    ?>
            														  </select>
            														</div>
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="fv-row mb-15">
																<!--begin::Label-->
																<label class="fs-6 fw-semibold mb-2 required" for="descripcion">بيان التحويل</label>
																<!--end::Label-->
																<!--begin::Input-->
																<textarea class="form-control" name="descripcion" id="descripcion" rows="3"></textarea>
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
														<button type="reset" id="add_transaction_cancel" class="btn btn-light me-3">إلغاء</button>
														<!--end::Button-->
														<!--begin::Button-->
														<button type="submit" id="add_transaction_submit" class="btn btn-primary">
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
		<script src="assets/js/js/customer/traders_veiw.js"></script>
		<script src="assets/js/js/customer/transaction/add.js"></script>
		<script src="assets/js/js/customer/transaction/listing.js"></script>
		<script src="assets/js/js/customer/transaction/print.js"></script>
		<!--end::Javascript-->
	</body>

	<script>
document.getElementById("clipboard").addEventListener("click", function() {
    let balances = document.querySelectorAll("#balunce");
	let traderName = "<?php echo $full_name; ?>";

    let currentDate = new Date();
    let formattedDate = currentDate.toLocaleDateString() + "\u00A0" + currentDate.toLocaleTimeString();

    let textToCopy = "*💰 Flex Pay 💰*\n\n *🌷 السلام عليكم ورحمة الله 🌷* \n\n ------------------ \n";
    textToCopy += "*لحساب " + traderName +"*   *حتى تاريخ " + formattedDate + "*\n------------------ \n\n";
    
    balances.forEach(function(balanceElement) {
        let balanceDirection = balanceElement.getAttribute("data-balance-direction");
        let balanceAmount = balanceElement.getAttribute("data-balance-amount");
		let balancesymbol = balanceElement.getAttribute("data-balance-symbol");
        textToCopy += balanceDirection + " *" + balanceAmount + "* - " + balancesymbol + "\n\n";
    });

    textToCopy += "------------------ \n*المطابقة والتأكيد 📂 ✅*";
    
    let textarea = document.createElement("textarea");
    textarea.value = textToCopy;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
    
    showBootstrapAlert("تم نسخ نص المطابقة بنجاح!");
});

function showBootstrapAlert(message) {
    let alertBox = document.createElement('div');
    alertBox.setAttribute('class', 'alert alert-primary d-flex align-items-center sucssessalert fade-in');
    
    let icon = document.createElement('i');
    icon.setAttribute('class', 'ki-duotone ki-shield-tick fs-2hx text-success me-4');
    let span1 = document.createElement('span');
    span1.setAttribute('class', 'path1');
    let span2 = document.createElement('span');
    span2.setAttribute('class', 'path2');
    icon.appendChild(span1);
    icon.appendChild(span2);
    
    let wrapper = document.createElement('div');
    wrapper.setAttribute('class', 'd-flex flex-column');

    let title = document.createElement('h4');
    title.setAttribute('class', 'mb-1 text-dark');
    title.textContent = "";

    let content = document.createElement('span');
    content.textContent = message;

    wrapper.appendChild(title);
    wrapper.appendChild(content);

    alertBox.appendChild(icon);
    alertBox.appendChild(wrapper);

    document.body.appendChild(alertBox);

    setTimeout(() => {
        alertBox.classList.remove('fade-in');
        alertBox.classList.add('fade-out');

        setTimeout(() => {
            document.body.removeChild(alertBox);
        }, 500);
    }, 1000);
}




</script>


	<!--end::Body-->
</html>