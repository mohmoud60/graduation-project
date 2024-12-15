<?php
include 'session_check.php';
// Include database connection file
include 'assets/php/connection.php';

// Query to get the data
$sql = "SELECT * FROM company_info LIMIT 1"; // Change this query to match your needs
$result = $conn->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);

$logo = isset($row['logo']) ? $row['logo'] : 'default_logo.png';
$companyName = isset($row['companyName']) ? $row['companyName'] : '';
$companyAddress = isset($row['companyAddress']) ? $row['companyAddress'] : '';
$mobileNumber = isset($row['mobileNumber']) ? $row['mobileNumber'] : '';
$companyDescription = isset($row['companyDescription']) ? $row['companyDescription'] : '';
?>

<!DOCTYPE html>
<html lang="en" direction="rtl" dir="rtl" style="direction: rtl" data-bs-theme-mode="light">
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
        الإعدادات الرئيسية 
    </span>
    <!--end::Label-->

    <!--begin::Line-->
    <span class="d-inline-block position-absolute h-3px bottom-0 end-0 start-0 bg-success translate rounded"></span>
    <!--end::Line-->
</span>
<!--end::Underline-->

<div class="mt-8">
<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
    <li class="nav-item mt-2" id="myTab" role="tablist">
            <li class="nav-item mt-2" role="presentation">
                <a class="nav-link active fw-bold" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">معلومات الشركة</a>
            </li>
            <li class="nav-item mt-2" role="presentation">
                <a class="nav-link fw-bold" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">إعدادات المنظقة والموقع</a>
            </li>
        </ul>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card mt-3 ">
                <div class="card-body">
                <form id="Overview-form">

                <!--begin::Input group-->
													<div class="row mb-6">
														<!--begin::Label-->
														<label class="col-lg-4 col-form-label fw-semibold fs-6">شعار الشركة</label>
														<!--end::Label-->
														<!--begin::Col-->
														<div class="col-lg-8">
															<!--begin::Image input-->
															<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/avatars/blank.svg')">
																<!--begin::Preview existing avatar-->
																<div  class="image-input-wrapper w-125px h-125px" style="background-image: url(<?php echo $logo; ?>)"></div>
															</div>
															<!--end::Image input-->
															<!--begin::Hint-->
															<div class="form-text">أنواع الملفات المسموح بها: png، jpg، jpeg.</div>
															<!--end::Hint-->
														</div>
														<!--end::Col-->
													</div>
													<!--end::Input group-->

   
                        <div class="mb-3 ">
                            <label for="companyName" class="form-label fw-bold required ">اسم الشركة</label>
                            <div class="input-group flex-nowrap">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-profile-user fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </span>
                            <input type="text" class="form-control" id="companyName" required value="<?php echo $companyName; ?>">
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="companyAddress" class="form-label fw-bold required ">عنوان الشركة</label>
                            <div class="input-group flex-nowrap">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-address-book fs-3"> <i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                            </span>
                            <input type="text" class="form-control" id="companyAddress" required value="<?php echo $companyAddress; ?>">
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="mobileNumber" class="form-label fw-bold required ">أرقام التواصل</label>
                            <div class="input-group flex-nowrap">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-phone fs-3"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <input type="tel" class="form-control" id="mobileNumber" value="<?php echo $mobileNumber; ?>">
                        </div>
                        </div>
                        <div class="mb-3">
                            <label for="companyDescription" class="form-label fw-bold ">وصف عمل الشركة</label>
                            <div class="input-group flex-nowrap">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-information fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                            <textarea class="form-control" id="companyDescription" rows="3"><?php echo $row['companyDescription']; ?></textarea>
                        </div>
                        </div>
                        <div class="mb-3">
                    <!-- You can use the same image input structure as in the first tab -->
                    <label for="company_Logo" class="form-label fw-bold required ">شعار الشركة</label>
                    <input type="file" class="form-control" id="company_Logo" accept="assets/media/dollar/*" name="company_Logo">
                    <p class="help-block"><i> سيتم استبدال الشعار السابق (إن وجد)</i></p>
                    
                </div>
                        <div class="d-flex justify-content-center align-items-center">
                        <button type="submit" id="Overview-btn" class="btn btn-primary ">تحديث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <!-- Begin second settings -->
    <div class="card mt-3">
        <div class="card-body">
            <form" id="settings-form">
                <div class="row">
                    <div class="col-md-4">
                        <label for="timeZone" class="form-label fw-bold required ">المنطقة الزمنية</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-time fs-3"><span class="path1"></span><span class="path2"></span></i>
                            </span>
                            <div class="overflow-hidden flex-grow-1">
                                <select id="timeZone" class="form-select rounded-start-0" data-control="select2" data-placeholder="Select an option">
                                    <option value="Asia/Riyadh"  >(GMT+03:00) Riyadh الرياض</option>
                                    <option value="Asia/Kuwait"  >(GMT+03:00) Kuwait الكويت</option>
                                    <option value="Asia/Dubai"  >(GMT+04:00) Dubai دبي </option>
                                    <option value="Africa/Cairo"  >(GMT+02:00) Cairo القاهرة</option>
                                    <option value="Asia/Jerusalem"  >(GMT+02:00) Jerusalem القدس</option>
                                    <option value="Asia/Baghdad"  >(GMT+03:00) Baghdad بغداد</option>
                                    <option value="Asia/Muscat"  >(GMT+04:00) Muscat مسقط</option>
                                    <option value="Asia/Amman"  >(GMT+02:00) Amman عمان</option>
                                    <option value="Africa/Khartoum"  >(GMT+02:00) Khartoum الخرطوم</option>
                                    <option value="Asia/Gaza"   >(GMT+02:00) Gaza غزة</option>
                                    <option value="Africa/Algiers"  >(GMT+01:00) Algeria الجزائر</option>
                                    <option value="Asia/Qatar"  >(GMT+03:00) Qatar قطر </option>
                                    <option value="Asia/Bahrain"  >(GMT+03:00) Bahrain البحرين </option>                        
                                    <option value="Europe/Istanbul"   >(GMT+03:00) Istanbul اسطنبول </option>
                                    <option value="Asia/Damascus"   >(GMT+03:00) Damascus دمشق </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="dateFormat" class="form-label fw-bold required ">صيغة التاريخ</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text">
                                <i class="ki-duotone ki-calendar-2 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </span>
                        <select class="form-select" id="dateFormat" required>
                            <option value="d-m-Y">dd-mm-yyyy</option>
                            <option value="m-d-Y">mm-dd-yyyy</option>
                            <option value="d/m/Y">dd/mm/yyyy</option>
                            <option value="m/d/Y">mm/dd/yyyy</option>
                        </select>
                    </div>
                    </div>
                    <div class="col-md-4">
                        <label for="timeFormat" class="form-label fw-bold required ">تنسيق الوقت</label>
                        <div class="input-group flex-nowrap">
                         <span class="input-group-text">
                             <i class="ki-duotone ki-time fs-3"><span class="path1"></span><span class="path2"></span></i>
                         </span>
                        <select class="form-select" id="timeFormat" required>
                            <option value="12">12 ساعة</option>
                            <option value="24">24 ساعة</option>
                        </select>
                    </div>
                    </div>
                
                <div class="row">
                <div class="col-md-4">
                        <label for="financialYearStart" class="form-label fw-bold">بداية السنة المالية</label>
                        <div class="input-group flex-nowrap">
                        <span class="input-group-text">
                                <i class="ki-duotone ki-calendar-2 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </span>
                        <select class="form-select" id="financialYearStart" required>
                            <option value="1" selected="selected">يناير</option>
                            <option value="2">فبراير</option>
                            <option value="3">مارس</option>
                            <option value="4">أبريل</option>
                            <option value="5">مايو</option>
                            <option value="6">يونيو</option>
                            <option value="7">يوليو</option>
                            <option value="8">أغسطس</option>
                            <option value="9">سبتمبر</option>
                            <option value="10">أكتوبر</option>
                            <option value="11">نوفمبر</option>
                            <option value="12">ديسمبر</option>
                    </select>
                    </div>

                    </div>
                    <div class="col-md-4">
                        <label for="exchange_rate_sub" class="form-label fw-bold required ">سعر صرف الشيكل لدولار صناديق فرعية</label>
                        <div class="input-group flex-nowrap">
                         <span class="input-group-text">
                             <i class="ki-duotone ki-dollar fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                         </span>
                        <input type="text" class="form-control"  id="exchange_rate_sub" required></input>
                    </div>
                    </div>
                    <div class="row align-items-center">
    <label class="col-lg-4 col-form-label fw-semibold fs-6 fw-bold">تفعيل الوضع الداكن</label>
    <div class="col-md-4 pl-0">
        <div class="form-check form-check-solid form-check-custom form-check-success form-switch">
            <input class="form-check-input bg-secondary" type="checkbox" id="allowmarketing" onchange="changeColor(this)">
            <label class="form-check-label" for="allowmarketing"></label>
        </div>
    </div>
</div>

                <div class=" form-label fw-bold required " >
                    شعار الواتس اب
                </div>
                <div class="col-md-4">
                    <!-- You can use the same image input structure as in the first tab -->
                    <input type="file" class="form-control" id="whatsapp_Logo" accept="assets/media/dollar/*" name="whatsapp_Logo">
                    <p class="help-block"><i> سيتم استبدال الشعار السابق (إن وجد)</i></p>
                    
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" id="settings-btn" class="btn btn-primary ">تحديث</button>
                </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End second settings -->
</div>

    </div>
</div>




					<!--end:::Main-->
                <script>

                    // Find the form element
let Overviewbtn = document.getElementById('Overview-btn');

// Attach a submit event listener to the form
Overviewbtn.addEventListener('click', function(event) {
    // Prevent the form's default submit action
    event.preventDefault();

    // Call your function
    OverviewForm();
});


    function OverviewForm() {
    // Prepare form data for uploading
    let formData = new FormData();
    let fileField = document.querySelector('#company_Logo');
    let companyName = document.querySelector('#companyName');
    let companyAddress = document.querySelector('#companyAddress');
    let mobileNumber = document.querySelector('#mobileNumber');
    let companyDescription = document.querySelector('#companyDescription');

    formData.append('logo', fileField.files[0]);
    formData.append('companyName', companyName.value);
    formData.append('companyAddress', companyAddress.value);
    formData.append('mobileNumber', mobileNumber.value);
    formData.append('companyDescription', companyDescription.value);

    // Use fetch API to send the data
    fetch('assets/php/process_currency.php?action=0', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(result => {
    console.log('Success:', result);
    if(result.success) {
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: result.message,
                confirmButtonText: 'تأكيد'
            }).then(() => {
                location.reload();
            });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: result.message,
            confirmButtonText: 'تأكيد'
        });
    }
})
.catch(error => {
    console.error('Error:', error);
});


}

let settingsBtn = document.getElementById('settings-btn');

// Attach a click event listener to the button
settingsBtn.addEventListener('click', function(event) {
    // Prevent the form's default submit action
    event.preventDefault();

    // Call your function
    SettingsForm();
});


document.addEventListener('DOMContentLoaded', (event) => {
    fetch('assets/php/get_settings.php')
        .then(response => response.json())
        .then(data => {
            // Fill the form with the data received
            document.querySelector('#timeZone').value = data.timeZone;
            $('#timeZone').trigger('change');
            document.querySelector('#dateFormat').value = data.dateFormat;
            document.querySelector('#timeFormat').value = data.timeFormat;
            document.querySelector('#financialYearStart').value = data.financialYearStart;
            document.querySelector('#exchange_rate_sub').value = data.exchange_rate_sub;
                })
        .catch(error => {
            console.error('Error:', error);
        });
});


function SettingsForm() {
    // Prepare form data for uploading
    let formData = new FormData();
    let timeZone = document.querySelector('select[data-control="select2"]');
    let dateFormat = document.querySelector('#dateFormat');
    let timeFormat = document.querySelector('#timeFormat');
    let financialYearStart = document.querySelector('#financialYearStart');
    let whatsAppLogo = document.querySelector('#whatsapp_Logo');
    let exchange_rate_sub = document.querySelector('#exchange_rate_sub');

    formData.append('timeZone', timeZone.value);
    formData.append('dateFormat', dateFormat.value);
    formData.append('timeFormat', timeFormat.value);
    formData.append('financialYearStart', financialYearStart.value);
    formData.append('exchange_rate_sub', exchange_rate_sub.value);
    formData.append('whatsAppLogo', whatsAppLogo.files[0]);

    // Use fetch API to send the data
    fetch('assets/php/settings_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        console.log('Success:', result);
        if(result.success) {
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: result.message,
                confirmButtonText: 'تأكيد'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: result.message,
                confirmButtonText: 'تأكيد'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}



function changeColor(element) {
    if(element.checked) {
        element.className = 'form-check-input bg-success';
    } else {
        element.className = 'form-check-input bg-secondary';
    }
}

window.addEventListener('DOMContentLoaded', (event) => {
        let defaultThemeMode = "light";
        let themeMode;
    
        const checkbox = document.getElementById('allowmarketing');
        
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
    
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
    
            document.documentElement.setAttribute("data-bs-theme", themeMode);

            // set checkbox state according to theme mode
            checkbox.checked = themeMode === "dark";
        }
        
        checkbox.addEventListener('change', (event) => {
            themeMode = event.target.checked ? "dark" : "light";
            document.documentElement.setAttribute("data-bs-theme", themeMode);
            localStorage.setItem("data-bs-theme", themeMode);
        });

    });

                    </script>
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
        
		<!--end::Custom Javascript-->
        <script src="assets/js/js/employee_over.js"></script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>