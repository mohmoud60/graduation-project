<?php
$required_permission = 'permission_1';
include 'session_check.php';
include 'assets/php/connection.php';

// استعلام لجلب عدد المستخدمين
$query1 = "SELECT COUNT(*) AS total_users FROM account WHERE Delete_Date IS NULL";
$stmt1 = $conn->prepare($query1);
$stmt1->execute();
$total_users = $stmt1->fetch(PDO::FETCH_ASSOC)['total_users'];

// استعلام لجلب عدد التحويلات
$query2 = "SELECT COUNT(*) AS total_transactions FROM customer_transaction WHERE Delete_Date IS NULL";
$stmt2 = $conn->prepare($query2);
$stmt2->execute();
$total_transactions = $stmt2->fetch(PDO::FETCH_ASSOC)['total_transactions'];

$query3 = "
    SELECT 
        SUM(CASE 
                WHEN currency_id = '001' AND account_type LIKE '3200%' THEN account_amount
                WHEN currency_id = '005' AND account_type LIKE '3200%' THEN account_amount / (
                    SELECT sell_rate 
                    FROM exchange_rates 
                    WHERE currency_ex = '10100_10101' 
                    LIMIT 1
                )
                ELSE 0
            END
        ) AS total_balance
    FROM accounting
    WHERE Delete_Date IS NULL
      AND (currency_id = '001' OR currency_id = '005');
";

// تحضير وتنفيذ الاستعلام
$stmt3 = $conn->prepare($query3);
$stmt3->execute();
$total_balance = $stmt3->fetch(PDO::FETCH_ASSOC)['total_balance'];

// استعلام لجلب عدد الزبائن
$query4 = "SELECT COUNT(*) AS total_customers FROM customer WHERE Delete_Date IS NULL";
$stmt4 = $conn->prepare($query4);
$stmt4->execute();
$total_customers = $stmt4->fetch(PDO::FETCH_ASSOC)['total_customers'];


$query5 = "
        SELECT 
            SUM(
                CASE 
                    WHEN a.account_number = '10100' THEN a.account_amount
                    ELSE a.account_amount / er.sell_rate
                END
            ) AS total_in_usd
        FROM 
            accounting a
        LEFT JOIN 
            exchange_rates er
        ON 
            er.currency_ex = CONCAT('10100_', a.account_number)
        WHERE 
            a.account_type = 3000
            AND a.Delete_Date IS NULL;
    ";

    $stmt5 = $conn->prepare($query5);
    $stmt5->execute();

    $result = $stmt5->fetch(PDO::FETCH_ASSOC);
    $total_in_usd = $result['total_in_usd'];


    $query6 = "
        SELECT 
            SUM(CASE WHEN currency_id = '001' THEN account_amount ELSE 0 END) AS sun_total_dollar,
            SUM(CASE WHEN currency_id = '005' THEN account_amount ELSE 0 END) AS sun_total_shikel,
            SUM(CASE WHEN currency_id = '001' THEN account_amount ELSE 0 END) +
            (SUM(CASE WHEN currency_id = '005' THEN account_amount ELSE 0 END) / 
            (SELECT er.sell_rate FROM exchange_rates er WHERE er.currency_ex = '10100_10101' LIMIT 1)) AS total
        FROM 
            accounting a
        WHERE 
            a.account_type = 3100
            AND a.currency_id IN ('001', '005')
            AND a.Delete_Date IS NULL
        LIMIT 0, 25;


    ";

    $stmt6 = $conn->prepare($query6);
    $stmt6->execute();

    $result6 = $stmt6->fetch(PDO::FETCH_ASSOC);
    $total_in_usd_sub = $result6['sun_total_dollar'];
    $total_in_shikel_sub = $result6['sun_total_shikel'];
    $total_in_total_sub = $result6['total'];



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
        <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">لوحة التحكم</span>
        <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
        </span>
    </div>

    <div class="row g-4" data-permission="permission_23">    
		<!-- بطاقة عدد المستخدمين -->
        <div class="col-xl-3 col-md-6">
                <div class="card card-custom bg-light-primary card-stretch gutter-b shadow-sm">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-primary">
                            <i class="fas fa-user fs-2"></i>
                        </span>
                        <div class="text-dark font-weight-bold mt-3 fs-2">عدد مستخدمين النظام</div>
                        <div class="text-muted fs-3"><?php echo $total_users; ?> مستخدم</div>
                    </div>
                </div>
        </div>
        <!-- بطاقة عدد التحويلات -->
        <div class="col-xl-3 col-md-6">
                <div class="card card-custom bg-light-success card-stretch gutter-b shadow-sm">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-success">
                            <i class="fas fa-exchange-alt fs-2"></i>
                        </span>
                        <div class="text-dark font-weight-bold mt-3 fs-2">عدد التحويلات</div>
                        <div class="text-muted fs-3"><?php echo $total_transactions; ?> عملية</div>
                    </div>
                </div>
        </div>
        
        <!-- بطاقة عدد الزبائن -->
        <div class="col-xl-3 col-md-6">
                <div class="card card-custom bg-light-danger card-stretch gutter-b shadow-sm">
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-primary">
                            <i class="fas fa-user fs-2"></i>
                        </span>
                        <div class="text-dark font-weight-bold mt-3 fs-2">عدد الزبائن</div>
                        <div class="text-muted fs-3"><?php echo $total_customers; ?> زبون</div>
                    </div>
                </div>
        </div>
        <!-- بطاقة الرصيد المتوفر -->
        <div class="col-xl-3 col-md-6" data-permission="permission_28">
                <div class="card card-custom bg-light-warning card-stretch gutter-b shadow-sm" >
                    <div class="card-body">
                        <span class="svg-icon svg-icon-3x svg-icon-warning">
                            <i class="fas fa-wallet fs-2"></i>
                        </span>
                        <div class="text-dark font-weight-bold mt-3 fs-2"> مجموع إيرادات دولار - فرعي</div>
                        <div class="text-info fs-3">$<?php echo number_format($total_balance, 2); ?></div>
                    </div>
                </div>
        </div>
    </div>

    <div class="row g-4 mt-5" >    
        <div class="col-xl-3 col-md-6" data-permission="permission_26">
            <div class="card card-custom  card-stretch gutter-b shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <span class="svg-icon svg-icon-3x svg-icon-primary me-3">
                            <i class="fas fa-dollar  text-success fs-2"></i>
                        </span>
                        <div class="text-dark font-weight-bold fs-4">مجموع دولار أمريكي رئيسي</div>
                    </div>
                    <div class="text-info fs-2 mt-3"><?php echo number_format($total_in_usd, 2); ?> $</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6" data-permission="permission_27">
                <div class="card card-custom  card-stretch gutter-b shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="svg-icon svg-icon-3x svg-icon-success me-3">
                                <i class="fas fa-dollar  text-success fs-2"></i>
                            </span>
                            <div class="text-dark font-weight-bold mt-3 fs-4">مجموع دولار أمريكي - فرعي</div>
                        </div>
                        <div class="text-info fs-3 mt-3"><?php echo number_format($total_in_usd_sub, 2); ?>$</div>
                    </div>
                </div>
        </div>
        <div class="col-xl-3 col-md-6" data-permission="permission_27">
                <div class="card card-custom  card-stretch gutter-b shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="svg-icon svg-icon-3x svg-icon-success me-3">
                                <i class="fas fa-wallet  text-success fs-2"></i>
                            </span>
                            <div class="text-dark font-weight-bold mt-3 fs-4">مجموع شيكل إسرائيلي - فرعي</div>
                        </div>
                        <div class="text-info fs-3 mt-3"><?php echo number_format($total_in_shikel_sub, 2); ?>	₪</div>
                    </div>
                </div>
        </div>
        <div class="col-xl-3 col-md-6" data-permission="permission_27">
                <div class="card card-custom  card-stretch gutter-b shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="svg-icon svg-icon-3x svg-icon-success me-3">
                                <i class="fas fa-dollar  text-success fs-2"></i>
                            </span>
                            <div class="text-dark font-weight-bold mt-3 fs-4">مجموع فرعي - دولار أمريكي</div>
                        </div>
                        <div class="text-info  font-bold fs-3 mt-3"><?php echo number_format($total_in_total_sub, 2); ?>	$</div>
                    </div>
                </div>
        </div>
    </div>

    <div class="row g-4 mt-5" data-permission="permission_24"> <!-- g-4 لإضافة مسافات بين البطاقات -->
        <!-- بطاقة تحويل العملة -->
        <div class="col-xl-4 col-md-6" data-permission="permission_2">
            <div class="card shadow-sm">
                <a href="currency.php" class="text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-exchange-alt fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">تحويل العملة</h5>
                        <p class="card-text text-muted">انتقل إلى قسم تحويل العملات</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- بطاقة إدارة الحسابات -->
        <div class="col-xl-4 col-md-6" data-permission="permission_3">
            <div class="card shadow-sm">
                <a href="account.php" class="text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-user-cog fa-3x text-success mb-3"></i>
                        <h5 class="card-title">إدارة الحسابات</h5>
                        <p class="card-text text-muted">إدارة حسابات المستخدمين</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- بطاقة تحويلات وخدمات -->
        <div class="col-xl-4 col-md-6" data-permission="permission_5">
            <div class="card shadow-sm">
                <a href="transfer.php" class="text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-concierge-bell fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">تحويلات وخدمات</h5>
                        <p class="card-text text-muted">إدارة تحويلات العملاء والخدمات</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- بطاقة صناديق العملات -->
        <div class="col-xl-4 col-md-6" data-permission="permission_7">
            <div class="card shadow-sm">
                <a href="fund.php" class="text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-box fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">صناديق العملات</h5>
                        <p class="card-text text-muted">إدارة صناديق العملات</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- بطاقة حسابات الزبائن -->
        <div class="col-xl-4 col-md-6" data-permission="permission_10">
            <div class="card shadow-sm">
                <a href="customer.php" class="text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-info mb-3"></i>
                        <h5 class="card-title">حسابات الزبائن</h5>
                        <p class="card-text text-muted">عرض وإدارة حسابات الزبائن</p>
                    </div>
                </a>
            </div>
        </div>
        <!-- بطاقة الموظفين -->
        <div class="col-xl-4 col-md-6" data-permission="permission_11">
            <div class="card shadow-sm">
                <a href="employees.php" class="text-decoration-none">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-3x text-secondary mb-3"></i>
                        <h5 class="card-title">الموظفين</h5>
                        <p class="card-text text-muted">إدارة بيانات الموظفين</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
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
			<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>