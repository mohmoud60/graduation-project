
	<div class="app-wrapper flex-column" id="kt_app_wrapper">
		<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
			<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
				<!--begin::Logo image-->
				<a href="dashboard.php">
					<img alt="Logo" src="assets/media/logos/logos1.png" class="h-45px app-sidebar-logo-default" />
					<img alt="Logo" src="assets/media/logos/logos_small.png" class="h-30px app-sidebar-logo-minimize" />
				</a>
				<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
					<i class="ki-duotone ki-double-left fs-2 rotate-180">
						<span class="path1"></span>
						<span class="path2"></span>
					</i>
				</div>
				<!--end::Sidebar toggle-->
			</div>
			<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
				<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
					<div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
						<div class="menu-item pt-5">
							<div class="menu-content">
								<span class="menu-heading fw-bold text-uppercase fs-7">الصفحات</span>
							</div>
						</div>
						<div class="menu-item">
							<a class="menu-link" href="dashboard.php" data-permission="permission_1">
								<span class="menu-icon">
									<i class="ki-duotone ki-home fs-2"></i>
								</span>
								<span class="menu-title h3">الرئيسية</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link" href="currency.php" data-permission="permission_2">
								<span class="menu-icon">
									<i class="bi bi-currency-exchange fs-2"></i>
								</span>
								<span class="menu-title h3">تحويل العملات</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link" href="account.php" data-permission="permission_3">
								<span class="menu-icon">
								<i class="ki-duotone ki-wallet fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
										<span class="path4"></span>
									</i>
								</span>
								<span class="menu-title h3">إدارة الحسابات</span>
							</a>
						</div>
						<div data-kt-menu-trigger="click" class="menu-item menu-accordion" data-permission="permission_4">
							<span class="menu-link">
								<span class="menu-icon">
									<i class="bi bi-sliders fs-2"></i>
								</span>
								<span class="menu-title h3">إدارة العملات</span>
								<span class="menu-arrow"></span>
							</span>
							<div class="menu-sub menu-sub-accordion menu-active-bg">
								<div class="menu-item">
									<a class="menu-link" href="currency_settings.php">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">إدارة العملات</span>
									</a>
								</div>
								<div class="menu-item">
									<a class="menu-link" href="currency_management.php">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">إدارة فئات العملات</span>
										</a>
								</div>
								
							</div>
						</div>
						<div class="menu-item">
							<a class="menu-link" href="transfer.php" data-permission="permission_5">
								<span class="menu-icon">
									<i class="fa-brands fa-swift fs-2"></i>
								</span>
								<span class="menu-title h3">تحويلات / خدمات</span>
							</a>
						</div>
						<div class="menu-item">
								<a class="menu-link" href="transfer_reports.php" data-permission="permission_6">
								<span class="menu-icon">
								<i class="ki-duotone ki-chart-pie-3 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
									<span class="menu-title h3">تقرير تحويلات إلكترونية</span>
								</a>
						</div>
						<div class="menu-item">
								<a class="menu-link" href="daily_report.php" data-permission="permission_14">
								<span class="menu-icon">
								<i class="ki-duotone ki-chart-pie-3 fs-2">
									<span class="path1"></span>
									<span class="path2"></span>
									<span class="path3"></span>
								</i>
							</span>
									<span class="menu-title h3">تقرير صرافة يومية </span>
								</a>
						</div>
						<div class="menu-item">
							<a class="menu-link" href="fund.php" data-permission="permission_7">
							<span class="menu-icon">
									<i class="bi bi-wallet2 fs-2"></i>
								</span>
								<span class="menu-title h3">صناديق العملات</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link" href="bonds.php" data-permission="permission_8">
							<span class="menu-icon">
							<i class="ki-duotone ki-receipt-square fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
								</span>
								<span class="menu-title h3">سندات صرف - قبض</span>
							</a>
						</div>
						<div data-kt-menu-trigger="click" class="menu-item menu-accordion" data-permission="permission_9">
							<span class="menu-link">
								<span class="menu-icon">
									<i class="ki-duotone ki-chart-pie-3 fs-2">
										<span class="path1"></span>
										<span class="path2"></span>
										<span class="path3"></span>
									</i>
								</span>
								<span class="menu-title h3">تقارير</span>
								<span class="menu-arrow"></span>
							</span>
							<div class="menu-sub menu-sub-accordion menu-active-bg">
								<div class="menu-item">
									<a class="menu-link" href="daily_report.php">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">تقارير يومية</span>
									</a>
								</div>
								<div class="menu-item">
									<a class="menu-link" href="transfer_reports.php">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">تقرير تحويلات إلكترونية</span>
									</a>
								</div>

								<div class="menu-item">
									<a class="menu-link" href="bonds_reports.php">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">تقارير سندات</span>
									</a>

								</div>

								<div class="menu-item">

									<a class="menu-link" href="company_expenses.php">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">مصروفات الشركة</span>
									</a>

								</div>

									<div class="menu-item">

									<a class="menu-link" href="income_posting.php">
										<span class="menu-bullet">
											<span class="bullet bullet-dot"></span>
										</span>
										<span class="menu-title">تقرير  ترحيل الإيرادات</span>
									</a>

								</div>

							</div>

						</div>
						<div class="menu-item">

							<a class="menu-link" href="customer.php" data-permission="permission_10">
								<span class="menu-icon">
									<i class="ki-duotone ki-save-deposit fs-2">
									<i class="path1"></i>
									<i class="path2"></i>
									<i class="path3"></i>
									<i class="path4"></i>
									</i>
								</span>
								<span class="menu-title h3">حسابات الزبائن</span>
							</a>
						</div>
						<div class="menu-item">
							<a class="menu-link" href="employees.php" data-permission="permission_11">
								<span class="menu-icon">
									<i class="ki-duotone ki-user-square fs-2">
									<i class="path1"></i>
									<i class="path2"></i>
									<i class="path3"></i>
									</i>
								</span>
								<span class="menu-title h3">الموظفين</span>
							</a>
						</div>
						<div class="menu-item">

							<a class="menu-link" href="settings.php" data-permission="permission_12">
								<span class="menu-icon">
									<i class="ki-duotone ki-gear fs-2">
									<i class="path1"></i>
									<i class="path2"></i>
									</i>
								</span>
								<span class="menu-title h3">إعدادات</span>
							</a>
						</div>
						<div data-kt-menu-trigger="click" class="menu-item menu-accordion" data-permission="permission_13">

								<span class="menu-link">
									<span class="menu-icon">
										<i class="ki-duotone ki-abstract-28 fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</span>
									<span class="menu-title h3">إدارة المستخدمين</span>
									<span class="menu-arrow"></span>
								</span>

								<div class="menu-sub menu-sub-accordion" kt-hidden-height="128" style="display: none; overflow: hidden;">

									<div class="menu-item">

										<a class="menu-link" href="users.php">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">المستخدمون</span>
										</a>

									</div>


									<div class="menu-item">

										<a class="menu-link" href="rols.php">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">قائمة الأدوار</span>
										</a>

									</div>


									<div class="menu-item">

										<a class="menu-link" href="permissions.php">
											<span class="menu-bullet">
												<span class="bullet bullet-dot"></span>
											</span>
											<span class="menu-title">الأذونات</span>
										</a>

									</div>

								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

				