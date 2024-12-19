"use strict";
var KTModalCustomersAdd = function () {
    var t, e, o, n, r, i;
    return {
        init: function () {
            i = new bootstrap.Modal(document.querySelector("#add_rulesTable")),
                r = document.querySelector("#add_rulesTable_form"),
                t = r.querySelector("#add_rulesTable_submit"),
                e = r.querySelector("#add_rulesTable_cancel"),
                n = FormValidation.formValidation(r, {
                    fields: {
                        sidebar_name: {
                            validators: {
                                notEmpty: { message: "اسم الصلاحية مطلوب" }
                            }
                        },
                        sidebar_pages: {
                            validators: {
                                notEmpty: { message: "رابط صفحة الصلاحية مطلوب" }
                            }
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger,
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: ""
                        })
                    }
                });

            t.addEventListener("click", (function (e) {
                e.preventDefault();
                n && n.validate().then((function (e) {
                    console.log("validated!");
                    if ("Valid" == e) {
                        t.setAttribute("data-kt-indicator", "on"),
                            t.disabled = !0;
                        // Gather form data
                        var formData = new FormData(r);
                        // AJAX request
                        $.ajax({
                            url: 'assets/php/process_roles.php?action=sidebar_roles',
                            type: 'POST',
                            data: formData,
                            processData: false,  // Important!
                            contentType: false,  // Important!
                            success: function (response) {
                                t.removeAttribute("data-kt-indicator");
                                if (response === 'success') {
                                    Swal.fire({
                                        text: "تم الحفظ بنجاح",
                                        icon: "success",
                                        buttonsStyling: !1,
                                        confirmButtonText: "حسنًا!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    }).then((function (e) {
                                        if (e.isConfirmed) {
                                            i.hide();
                                            t.disabled = !1;
                                            window.location = r.getAttribute("data-kt-redirect");
                                        }
                                    }));
                                } else {
                                    Swal.fire({
                                        text: "معذرة ، يبدو أنه تم اكتشاف بعض الأخطاء ، يرجى المحاولة مرة أخرى.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "حسنًا!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    });
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            text: "معذرة ، يبدو أنه تم اكتشاف بعض الأخطاء ، يرجى المحاولة مرة أخرى.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                }));
            }));

            e.addEventListener("click", (function (t) {
                t.preventDefault(),
                    Swal.fire({
                        text: "هل أنت متأكد أنك تريد الإلغاء؟",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "نعم ، قم بإلغائها!",
                        cancelButtonText: "لا رجوع",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then((function (t) {
                        t.value ? (
                            i.hide(),
                            r.reset(),
                            n && n.resetForm()
                        ) : "cancel" === t.dismiss && Swal.fire({
                            text: "لم يتم إلغاء النموذج الخاص بك !.",
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        })
                    }))
            }))
        }
    }
}();

KTUtil.onDOMContentLoaded((function () {
    KTModalCustomersAdd.init();
}));
