"use strict";
var KTModalCustomersAdd = function () {
    var t, e, o, n, r, i;
    return {
        init: function () {
            i = new bootstrap.Modal(document.querySelector("#add_mainTable")),
                r = document.querySelector("#add_mainTable_form"),
                t = r.querySelector("#add_mainTable_submit"),
                e = r.querySelector("#add_mainTable_cancel"),
                n = FormValidation.formValidation(r, {
                    fields: {
                        account_Sname: {
                            validators: {
                                notEmpty: { message: "اسم الحساب مطلوب" }
                            }
                        },
                        currency_id: {
                            validators: {
                                notEmpty: { message: "عملة الحساب مطلوب" }
                            }
                        },
                        type_id: {
                            validators: {
                                notEmpty: { message: "فئة الحساب مطلوب" }
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
                            url: 'assets/php/process_main_account.php?action=add_account',
                            type: 'POST',
                            data: formData,
                            processData: false,  // Important!
                            contentType: false,  // Important!
                            success: function (response) {
                                t.removeAttribute("data-kt-indicator");
                                try {
                                    var res = JSON.parse(response);
                                    if (res.status === 'success') {
                                        Swal.fire({
                                            text: res.message || "تم الحفظ بنجاح",
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
                                            text: res.message || "حدث خطأ أثناء الحفظ، يرجى المحاولة مرة أخرى.",
                                            icon: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "حسنًا!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    }
                                } catch (err) {
                                    console.error("Invalid JSON response:", response);
                                    Swal.fire({
                                        text: "حدث خطأ غير متوقع، يرجى المحاولة مرة أخرى.",
                                        icon: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "حسنًا!",
                                        customClass: {
                                            confirmButton: "btn btn-primary"
                                        }
                                    });
                                }
                            },
                            error: function () {
                                t.removeAttribute("data-kt-indicator");
                                t.disabled = !1;
                                Swal.fire({
                                    text: "تعذر الاتصال بالخادم. يرجى المحاولة لاحقًا.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "حسنًا!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
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
