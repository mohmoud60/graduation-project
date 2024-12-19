<?php

$required_permission = 'permission_7';

include 'session_check.php';
include 'assets/php/connection.php';

$total_balances = [];

$sql = "SELECT currency_id, currency_sname, currency_symbole FROM currency";
$currencystmt = $conn->prepare($sql);
$currencystmt->execute();
$currencies = $currencystmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($currencies as $currency) {
    $currency_id = $currency['currency_id'];
    $currency_sname = $currency['currency_sname'];
    $currency_symbole = $currency['currency_symbole'];
    
    $total_balances[$currency_id] = ['currency_sname' => $currency_sname, 'currency_symbole' => $currency_symbole, 'total_deposit' => 0, 'total_withdraw' => 0];


    $sql = "SELECT customer_id, tr_type, SUM(tr_amount) AS sum FROM customer_transaction WHERE tr_currency = :currency_id AND customer_id != 7  AND Delete_Date IS NULL GROUP BY customer_id, tr_type";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([':currency_id' => $currency_id]);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users_balance = [];

        foreach ($transactions as $transaction) {
            $users_id = $transaction['customer_id'];
            
            if(!isset($users_balance[$users_id])) {
                $users_balance[$users_id] = ['deposit' => 0, 'withdraw' => 0];
            }

            if ($transaction['tr_type'] === 'deposit') {
                $users_balance[$users_id]['deposit'] += $transaction['sum'];
            } else {
                $users_balance[$users_id]['withdraw'] += $transaction['sum'];
            }
        }

        foreach($users_balance as $user_balance) {
            $balance = $user_balance['deposit'] - $user_balance['withdraw'];
            if ($balance > 0) {
                $total_balances[$currency_id]['total_deposit'] += $balance;
            } else {
                $total_balances[$currency_id]['total_withdraw'] += abs($balance);
            }
        }
    }





function fetchAccountData(PDO $conn, $accountType) {
    $sql = "
        SELECT 
            accounting.account_Sname, 
            accounting.account_amount, 
            currency.currency_symbole 
        FROM 
            accounting 
        INNER JOIN 
            currency ON accounting.currency_id = currency.currency_id 
        WHERE 
            accounting.account_type = :account_type AND accounting.Delete_Date IS NULL
        ORDER BY
            accounting.account_number ASC
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute(['account_type' => $accountType]);
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        // Handle the error, for example:
        // error_log($e->getMessage()); // Log the error
        throw new Exception("An error occurred while fetching data for account type: $accountType");
    }
}

try {
    $result_main = fetchAccountData($conn, '3000');
    $result_sub = fetchAccountData($conn, '3100');
    $result_income = fetchAccountData($conn, '3200');
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}



// إعداد الاستعلام
$query = "
    SELECT c.currency_sname, c.currency_symbole, SUM(a.account_amount) AS total_amount
    FROM accounting a
    INNER JOIN currency c ON a.currency_id = c.currency_id
    WHERE a.account_type = '3200' AND a.Delete_Date IS NULL
    GROUP BY c.currency_id
";

// تنفيذ الاستعلام
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="en" direction="rtl" dir="rtl" style="direction: rtl">
    <?php include 'head.php'; ?>

    <body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
        <?php include 'header.php'; ?>
        <?php include 'sidebar.php'; ?>

        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
            <div class="d-flex flex-column flex-column-fluid">
                <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            		<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
    				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <span class="d-inline-block position-relative ms-2">
                        <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">صناديق العملات </span>
                        <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span></span>
                    </div>
				    <div class="d-flex align-items-center gap-2 gap-lg-3">
					    <a class="btn fw-bold btn-info" data-bs-toggle="modal" data-bs-target="#updateIncomeModal" data-permission="permission_19">ترحيل إيرادات</a>
				    </div>
            		</div>
                </div>
            </div>
        </div>
        <div class="container py-2" >
        <div class="row"  data-permission="permission_15">
        <h1 class="mt-5 d-flex align-items-center justify-content-center  mb-5">صناديق النقد الرئيسية</h1>
        <?php foreach ($result_main as $row): ?>
        <div class="col-md-3">
            <div class="card mb-3">
            <div class="card-header d-flex align-items-center justify-content-center">
                    <h5 class="card-title fw-bold "><?php echo htmlspecialchars($row['account_Sname']); ?></h5>
                </div>
                <div class="card-body">
                    <!--begin::Input group-->
                    <div class="input-group mb-2">
                        <span class="input-group-text  fs-1"><?php echo htmlspecialchars($row['currency_symbole']); ?></span>
                        <input type="text" class="form-control text-center fs-1 fw-bold text-success" value="<?php echo number_format($row['account_amount'], 2, '.', ','); ?>" aria-label="Amount" readonly/>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
        <div class="row" data-permission="permission_16">
        <h1 class="mt-5 d-flex align-items-center justify-content-center mb-5">صناديق النقد الفرعية</h1>
        <?php foreach ($result_sub as $row): ?>
            <!-- Only display if account_amount is greater than 0 -->
            <?php if ($row['account_amount'] != 0): ?>
                <div class="col-md-3">
                    <div class="card mb-3">
                        <div class="card-header d-flex align-items-center justify-content-center">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['account_Sname']); ?></h5>
                        </div>
                        <div class="card-body">
                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <span class="input-group-text  fs-1"><?php echo htmlspecialchars($row['currency_symbole']); ?></span>
                                <input type="text" class="form-control text-center fs-1 fw-bold text-success" value="<?php echo number_format($row['account_amount'], 2, '.', ','); ?>" aria-label="Amount" readonly/>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
        <div class="row" data-permission="permission_17">
            <h1 class="mt-5 d-flex align-items-center justify-content-center mb-5">صناديق إيرادات</h1>
            <?php foreach ($result_income as $row): ?>
                <?php if ($row['account_amount'] != 0): ?>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-header d-flex align-items-center justify-content-center">
                                <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['account_Sname']); ?></h5>
                            </div>
                            <div class="card-body">
                                <!--begin::Input group-->
                                <div class="input-group mb-5">
                                    <span class="input-group-text  fs-1"><?php echo htmlspecialchars($row['currency_symbole']); ?></span>
                                    <input type="text" class="form-control text-center fs-1 fw-bold text-success" value="<?php echo number_format($row['account_amount'], 2, '.', ','); ?>" aria-label="Amount" readonly/>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="row" >
                <h1 class="mt-5 d-flex align-items-center justify-content-center mb-5">إجمالي الإيرادات</h1>
                <?php foreach ($results as $row): ?>
                    <?php if ($row['total_amount'] != 0): ?>
                        <div class="col-md-3">
                            <div class="card mb-3">
                                <div class="card-header d-flex align-items-center justify-content-center">
                                    <h5 class="card-title fw-bold"> اجمالي إيراد <?php echo htmlspecialchars($row['currency_sname']); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="input-group mb-5 income_total">
                                        <span class="input-group-text  fs-1"><?php echo htmlspecialchars($row['currency_symbole']); ?></span>
                                        <input type="text" class="form-control text-center fs-1 fw-bold text-success" value="<?php echo number_format($row['total_amount'], 2, '.', ','); ?>" aria-label="Amount" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row" data-permission="permission_18">
            <h1 class="mt-5 d-flex align-items-center justify-content-center mb-5">إجمالي حسابات علينا - لنا</h1>
            <?php foreach ($total_balances as $balance_info): ?>
                <?php 
                    $currency_sname = $balance_info['currency_sname'];
                    $currency_symbole = $balance_info['currency_symbole'];
                    $total_deposit = number_format($balance_info['total_deposit'], 2);
                    $total_withdraw = number_format($balance_info['total_withdraw'], 2);
                    if ($total_deposit == 0.00 && $total_withdraw == 0.00) {
                        continue;
                    }
                ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header d-flex align-items-center justify-content-center">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($currency_sname) ?></h5>
                        </div>
                        <div class="card-body">
                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <span class="input-group-text fs-1"><?= $currency_symbole ?></span>
                                <input type="text" class="form-control text-center fs-1 fw-bold text-success" value="<?= $total_deposit . ' علينا' ?>" aria-label="Amount" readonly/>
                            </div>
                            <div class="input-group mb-5">
                                <span class="input-group-text fs-1"><?= $currency_symbole ?></span>
                                <input type="text" class="form-control text-center fs-1 fw-bold text-danger" value="<?= $total_withdraw . ' لنا' ?>" aria-label="Amount" readonly/>
                            </div>
                            <!--end::Input group-->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
        <div class="modal fade" id="updateIncomeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <form class="form" action="#" id="updateIncomeModal_form" data-kt-redirect="fund.php">
                        <div class="modal-header d-flex justify-content-between align-items-center" id="updateIncomeModal_header">
                            <h2 class="fw-bold m-auto">ترحيل إيرادات</h2>
                            <div id="updateIncomeModal_close" class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal" aria-label="Close">
                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                            </div>
                        </div>
                        <div class="modal-body py-10 px-lg-17">
                            <div class="scroll-y me-n7 pe-7" id="updateIncomeModal_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#updateIncomeModal_header" data-kt-scroll-wrappers="#updateIncomeModal_scroll" data-kt-scroll-offset="300px">
                                <h2>  اجمالي إيرادات </h2>
                                <div class="mb-3" id="total">
                                    <?php foreach ($results as $row): ?>
                                        <?php if ($row['total_amount'] != 0): ?>
                                            <div class="my-3">
                                                <p class="fs-1 fw-bold" id="sum_total">
                                                  <?php echo htmlspecialchars($row['currency_sname']); ?>: <span class="text-success"><?php echo htmlspecialchars($row['currency_symbole']); ?> <?php echo number_format($row['total_amount'], 2, '.', ','); ?></span>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="modal-footer flex-center">
                                <button type="reset" id="updateIncomeModal_cancel" class="btn btn-light me-3">إلغاء</button>
                                <button type="submit" id="updateIncomeModal_submit" class="btn btn-primary">
                                    <span class="indicator-label">ترحيل</span>
                                    <span class="indicator-progress">انتظر من فضلك...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="incomeConfirmModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">تأكيد ترحيل الإيرادات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="incomeConfirmForm">
                  <label for="usd " class="fw-bold mb-2">ترحيل دولار أمريكي</label>
                  <div class="input-group mb-5">
                    <span class="input-group-text  fs-1">$</span>
                    <input id="usd" type="number" class="form-control text-center fs-1 fw-bold " value="">
                  </div>
                  <label for="ils " class="fw-bold mb-2">ترحيل  شيكل إسرائيلي</label>
                  <div class="input-group mb-5">
                    <span class="input-group-text  fs-1">₪</span>
                    <input id="ils" type="number" class="form-control text-center fs-1 fw-bold " value="">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger confirm-income-btn">تأكيد</button>
              </div>
            </div>
          </div>
        </div>

        <iframe id="print_frame" name="print_frame" width="0" height="0" frameborder="0" src="about:blank"></iframe>

	    <!--begin::Javascript-->
	    <script>var hostUrl = "assets/";</script>
	    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
	    <script src="assets/plugins/global/plugins.bundle.js"></script>
	    <script src="assets/js/scripts.bundle.js"></script>
	    <!--end::Global Javascript Bundle-->
	    <!--begin::Vendors Javascript(used for this page only)-->
	    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
	    <script src="assets/plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
        <script src="assets/js/js/fund/update_income.js"></script>
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
	    <!--end::Javascript-->
        <script>
            var createdBy = '<?php echo $_SESSION["username"]; ?>';
            </script>
    </body>
	<!--end::Body-->
</html>