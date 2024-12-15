<?php
include 'session_check.php';
include 'assets/php/connection.php';

// استعلم قاعدة البيانات للحصول على البيانات المطلوبة
$stmt = $conn->prepare("SELECT currency_ex ,fund_sname, buy_rate, sell_rate FROM exchange_rates WHERE Delete_Date IS NULL");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        <!--begin::Label-->
        <span class="d-inline-block mb-2 fs-2tx fw-bold" id="page_titel">
        تحديث أسعار الصرف
        </span>
        <!--end::Label-->

        <!--begin::Line-->
        <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
        <!--end::Line-->
    </span>
    <!--end::Underline-->

    <div class="card mt-5">
        <div class="card-body">
        <form action="" method="POST">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <h2>فئات العملات</h2>
                    </div>
                    <div class="col-md-4">
                        <h2>سعر الشراء</h2>
                    </div>
                    <div class="col-md-4">
                        <h2>سعر البيع</h2>
                    </div>
                </div>

                <?php
                // عرض الخانات بناءً على البيانات المحصلة
                foreach($results as $row) {
                    ?>
                    <div class="row form-group mb-3">
                        <div class="col-md-4">
                            <h3 for="<?= $row['fund_sname']; ?>"><?= $row['fund_sname']; ?></h3>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control fs-3" id="buy_rate_<?= $row['currency_ex']; ?>" name="buy_rate_<?= $row['currency_ex']; ?>" value="<?= $row['buy_rate']; ?>" placeholder="سعر الشراء">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control fs-3" id="sell_rate_<?= $row['currency_ex']; ?>" name="sell_rate_<?= $row['currency_ex']; ?>" value="<?= $row['sell_rate']; ?>" placeholder="سعر البيع">
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-center align-items-center mt-5" >
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Main-->

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
    $("form").submit(function(e) {
        e.preventDefault(); // منع تحديث الصفحة

        // جمع البيانات من النموذج
        var formData = $(this).serialize();

        // طلب AJAX لتحديث البيانات
        $.post('assets/php/process_currency_setting.php?action=update_exchange', formData, function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم بنجاح',
                    text: 'تم تحديث أسعار الصرف بنجاح!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'currency_management.php';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: response.error
                });
            }
        }, 'json').fail(function(jqXHR, textStatus) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ في الطلب',
                text: 'رسالة الخطأ: ' + textStatus
            });
        });
    });
</script>




		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>