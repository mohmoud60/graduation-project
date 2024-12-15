<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en" direction="rtl" dir="rtl" style="direction: rtl">
<?php include 'head.php'; ?>
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled page-loading">
	<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="check-circle-fill" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"></path>
      </symbol>
      <symbol id="info-fill" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"></path>
      </symbol>
      <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
      </symbol>
    </svg>
		<div class="d-flex flex-column flex-root">
			<div class="login login-6 login-signin-on login-signin-on d-flex flex-column-fluid" id="kt_login">
				<div class="d-flex flex-column flex-lg-row flex-row-fluid text-center" style="background-image: url(assets/media/back_ground.jpg);">
					<div class="d-flex w-100 flex-center p-15">
						<div class="login-wrapper">
							<div class="text-dark-75">
								<a href="#">
									<img src="assets/media/logos/logo.png" style="    max-height: 200px !important;" alt="" />
                                    <h2 class="d-flex w-100 flex-center p-15" style="color:#252525; font-size:20px;">شركة دولار للصرافة و الحوالات المالية و الدفع الإلكتروني</h2>
								</a>
							</div>
						</div>
					</div>
					<div class="d-flex w-100 flex-center p-20 position-relative overflow-hidden">
						<div class="login-wrapper">
							<div class="login-signin">
								<div class="text-center mb-10 mb-lg-20 p-15">
									<h2 class="text-dark fw-bolder mb-5 ">تسجيل دخول</h2>
									<p class="text-gray-500 fw-semibold fs-6">أدخل اسم المستخدم و كلمة المرور.</p>
								</div>
								<?php
   									if (isset($_SESSION['error'])) {
   									    echo '<div class="alert alert-danger  d-flex align-items-center p-5">
										   <i class="ki-duotone ki-information-5 fs-2hx text-danger me-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path2"></span></i>
										   <div class="d-flex flex-column">
											   <h4 class="mb-1 text-dark">' . $_SESSION['error'] . '</h4>
										   </div>
									   </div>';
   									    unset($_SESSION['error']);
   									}
   									?>
									<form class="form text-left" id="kt_login_signin_form" method="post" action="welcome/doLogin.php">
									<div class="form-group py-2 m-0">
										<input required class="form-control h-auto border-0 px-0 placeholder-dark-75" type="text" placeholder="اسم المستخدم" name="username" autocomplete="off" />
									</div>
									<div class="form-group py-2 border-top m-0">
										<input required class="form-control h-auto border-0 px-0 placeholder-dark-75" type="Password" placeholder="كلمة المرور" name="password" />
									</div>
									<div class="text-center mt-15">
										<button id="kt_sign_in_submit" class="btn btn-primary" style="background-color:#252525;">تسجيل دخول</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="assets/js/custom/authentication/sign-in/general.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>