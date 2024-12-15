<head>

        <base href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"; ?>">

    <title>شركة دولار للصرافة و الحوالات المالية</title>
		<meta charset="utf-8" />
		<meta name="description" content="شركة دولار للصرافة و الحوالات الإلكترونية" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="assets/media/logos/logo.png" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="assets/plugins/custom/datatables/datatables.bundle.rtl.css" rel="stylesheet" type="text/css"/>
		<link href="assets/plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="assets/plugins/global/plugins.bundle.rtl.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.rtl.css" rel="stylesheet" type="text/css" />
		
        <style type="text/css">
            @font-face {
              font-family: Cairo;
              src: url(assets/font/Cairo-Regular.ttf);
            }
            body{
                font-family: "Cairo", sans-serif, Helvetica;
                
            }
			
			.delete-icon {
    font-size: 2.5rem; /* Change this value as needed */
}
.swal2-icon-ltr {
    direction: ltr;
}

.select2-container {
    z-index: 1051; /* This value can be more or less, depending on your project */
}

.select2-dropdown {
    position: relative;
    z-index: 2000; 
}

body.modal-open .select2-container {
    z-index: 1051; 
}

.daterangepicker.dropdown-menu.opensleft .ranges li:last-child {
    direction: rtl;
}


.sucssessalert {
    z-index: 1051;
    position: fixed;
    bottom: 20px;
    right: 20px;
    max-width: 500px;
    width: calc(100% - 40px);
    padding: 1rem;
    font-size: 1.5rem;
}

/* الاستجابة للشاشات الكبيرة */
@media (min-width: 576px) {
    .sucssessalert {
        right: 300px;
        width: auto; 
    }
}


.fade-in {
    animation: fadeIn 0.5s forwards;
}

.fade-out {
    animation: fadeOut 0.5s forwards;
}

@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes fadeOut {
    0% { opacity: 1; }
    100% { opacity: 0; }
}

#toast-container {
    position: fixed;
    top: 90px; /* يمكنك تعديل هذه القيمة بناءً على ارتفاع الرأس */
    left: 10px; 
    z-index: 9999;
}





            </style>
	</head>

    