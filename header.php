<?php
include 'assets/php/connection.php';
$query = $conn->query("SELECT Employee_FullName, Employee_Email, avatar_path FROM employee");
$employees = $query->fetchAll(PDO::FETCH_ASSOC);

?>


<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header">
				
					<!--begin::Header container-->
					<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
						<!--begin::Sidebar mobile toggle-->
						<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</div>
						</div>
						<!--end::Sidebar mobile toggle-->
						<!--begin::Mobile logo-->
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
							<a href="dashboard.php" class="d-lg-none">
								<img alt="Logo" src="assets/media/logos/logos_mobile.png" class="h-30px" />
							</a>
						</div>
						<!--end::Mobile logo-->
						<!--begin::Header wrapper-->
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<!--begin::Menu wrapper-->
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
								<!--begin::Menu-->
							</div>
							<!--end::Menu wrapper-->
							<!--begin::Navbar-->
							<div class="app-navbar flex-shrink-0">
							
								<!--begin::Theme mode-->
								<div class="app-navbar-item ms-1 ms-md-3">
									<!--begin::Menu toggle-->
									<a href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-night-day theme-light-show fs-2 fs-lg-1">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
											<span class="path6"></span>
											<span class="path7"></span>
											<span class="path8"></span>
											<span class="path9"></span>
											<span class="path10"></span>
										</i>
										<i class="ki-duotone ki-moon theme-dark-show fs-2 fs-lg-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<!--begin::Menu toggle-->
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-night-day fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
														<span class="path5"></span>
														<span class="path6"></span>
														<span class="path7"></span>
														<span class="path8"></span>
														<span class="path9"></span>
														<span class="path10"></span>
													</i>
												</span>
												<span class="menu-title">Light</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-moon fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</span>
												<span class="menu-title">Dark</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-screen fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
													</i>
												</span>
												<span class="menu-title">System</span>
											</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Theme mode-->
								<!--begin::User menu-->
								<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
									<!--begin::Menu wrapper-->
									<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img id="user_avatar" src="assets/media/avatars/blank.png" alt="user" />
									</div>
									
									<!--begin::User account menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													<img id="user_avatar1" alt="Logo" src="assets/media/avatars/blank.png" />
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													<div class="fw-bold d-flex align-items-center fs-5" id="HFullName" name="HFullName"></div>
													<a  class="fw-semibold text-muted text-hover-primary fs-7" id="Hemail" name="Hemail"></a>
												</div>
												<!--end::Username-->
											</div>
										</div>
										<!--end::Menu item-->
										
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="overview.php" class="menu-link px-5">الملف الشخصي</a>
										</div>
										<div class="separator my-2"></div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="welcome/doLogout.php" class="menu-link px-5">تسجيل خروج</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
									
									<!--end::Menu wrapper-->
								</div>
								<!--end::User menu-->
							    <div id="toast-container" aria-live="polite" aria-atomic="true">
				    <!-- هنا ستظهر التوستات -->
				</div>

							</div>
							<!--end::Navbar-->
							
						</div>
						<!--end::Header wrapper-->
						
					</div>
					<!--end::Header container-->
					
				</div>
				<!--end::Header-->

				<!--begin::Chat drawer-->
		<div id="kt_drawer_chat_info" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="info_chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chatinfo_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
			
			<!--begin::Contacts-->
			<div class="card w-100 border-0 rounded-0">

			
			<!--begin::Card header-->
			<div class="card-header pt-7 d-flex flex-column" id="kt_chat_contacts_header">
			<!--begin::Title and Close Button-->
			<div class="d-flex align-items-center mb-3 w-100 justify-content-between">
			    <!--begin::Title-->
				
				<div class="fs-4 fw-bold text-gray-900 text-hover-primary lh-1 mr-3">الدردشات</div>
			    <!--end::Title-->

			    <!--begin::Close-->
			    <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_chat_close">
			        <i class="ki-duotone ki-cross-square fs-2">
			            <span class="path1"></span>
			            <span class="path2"></span>
			        </i>
			    </div>
			    <!--end::Close-->
			</div>
			<!--end::Title and Close Button-->
			    <!--begin::Form-->
			    <form class="position-relative w-100 mb-3" autocomplete="off">
			        <!--begin::Icon-->
			        <i class="ki-duotone ki-magnifier fs-3 text-gray-500 position-absolute top-50 ms-5 translate-middle-y">
			            <span class="path1"></span>
			            <span class="path2"></span>
			        </i>
			        <!--end::Icon-->
			        <!--begin::Input-->
			        <input type="text" class="form-control form-control-solid px-13" name="contact_search" value="" placeholder="البحث عن مستخدم" />
			        <!--end::Input-->
			    </form>
			    <!--end::Form-->
			</div>
			<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body pt-5" id="kt_chat_contacts_body">
					<!--begin::List-->
					<div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_contacts_body" data-kt-scroll-offset="5px">
						<!--begin::User-->
						<div id="users_container">
						</div>
						<!--end::User-->
						
					</div>
					<!--end::List-->
				</div>
				<!--end::Card body-->
			</div>
			<!--end::Contacts-->
		</div>
		<!--end::Chat drawer-->
		
		
				<!--begin::Chat masagee drawer-->
		<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
			<!--begin::Messenger-->
			<div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
				<!--begin::Card header-->
				<div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
					<!--begin::Title-->
					<div class="card-title">
					<div class="btn btn-bg btn-icon btn-active-color-primary me-5" id="back_contact_chat">
					<i class="ki-duotone ki-arrow-left fs-1">
					 <i class="path1"></i>
					 <i class="path2"></i>
					</i>
					</div>
					<!--begin::User-->

					<div class="d-flex flex-stack py-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px symbol-circle">
								<img id="contact_avatar" alt="Logo" src="assets/media/avatars/blank.png" />
								</div>
                                <div class="ms-5">
									<a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1" id="contact_fullname"></a>
                                </div>
                            </div>
                        </div>

					<!--end::User-->

					</div>
					<!--end::Title-->
					<!--begin::Card toolbar-->
					<div class="card-toolbar">
						
						<!--begin::Close-->
						<div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_chat_close">
							<i class="ki-duotone ki-cross-square fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
						</div>
						<!--end::Close-->
					</div>
					<!--end::Card toolbar-->
				</div>
				<!--end::Card header-->
				<!--begin::Card body-->
				<div class="card-body" id="kt_drawer_chat_messenger_body">
					<!--begin::Messages-->
					<div class="scroll-y me-n5 pe-5" id="massage_contaner" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
						

					</div>
					<!--end::Messages-->
				</div>
				<!--end::Card body-->
				<!--begin::Card footer-->
				<div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
					<!--begin::Input-->
					<textarea id="message_input" class="form-control form-control-flush mb-3" rows="1" data-kt-element="input" placeholder="أكتب رسالة ... "></textarea>
					<!--end::Input-->
					<!--begin:Toolbar-->
					<div class="d-flex flex-stack">
						<!--begin::Actions-->
						<div class="d-flex align-items-center me-2">

						</div>
						<!--end::Actions-->
						<!--begin::Send-->
						<button class="btn btn-primary" type="button" data-kt-element="send">إرسال</button>
						<!--end::Send-->
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card footer-->
			</div>
			<!--end::Messenger-->
		</div>
		<!--end::Chat masagee drawer-->


		<script type="text/javascript">
    var myID = "<?php echo $_SESSION['employee_id']; ?>";
</script>

