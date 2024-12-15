"use strict";
var KTModalemployeesAdd = function () {
    var t, e, o, n, r, i;
    return {
        init: function () {
            i = new bootstrap.Modal(document.querySelector("#add_employeeTable")),
                r = document.querySelector("#add_employeeTable_form"),
                t = r.querySelector("#add_employeeTable_submit"),
                e = r.querySelector("#add_employeeTable_cancel"),
                o = r.querySelector("#add_employeeTable_close"),
                n = FormValidation.formValidation(r, {
                    fields: {
                        employee_name: {
                            validators: {
                                notEmpty: { message: "اسم الموظف مطلوب" }
                            }
                        },
                        employee_address: {
                            validators: {
                                notEmpty: { message: "العنوان  مطلوب" }
                            }
                        },
                        employee_phone: {
                            validators: {
                                notEmpty: { message: "رقم هاتف  مطلوب" }
                            }
                        },
                        employee_email: {
                            validators: {
                                notEmpty: { message: "البريد الإلكتروني مطلوب" }
                            }
                        },
                        job_titel: {
                            validators: {
                                notEmpty: { message: "المسمى الوظيفي مطلوب" }
                            }
                        },
                        salary: {
                            validators: {
                                notEmpty: { message: "قيمة الراتب مطلوبة" }
                            }
                        },
                        username: {
                            validators: {
                                notEmpty: { message: "اسم المستخدم مطلوبة" }
                            }
                        },
                        password: {
                            validators: {
                                notEmpty: { message: "كلمة المرور مطلوبة" }
                            }
                        },
                        account_rolls: {
                            validators: {
                                notEmpty: { message: "الرجاء إختيار صلاحيات للمستخدم" }
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
                            url: 'assets/php/process_employee.php',
                            type: 'POST',
                            data: formData,
                            processData: false,  // Important!
                            contentType: false,  // Important!
                            success: function (response) {
                                response = JSON.parse(response);
                                t.removeAttribute("data-kt-indicator");
                                if (response.status === 'success') {
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
            })),
                o.addEventListener("click", (function () {
                    r.reset(),
                        n && n.resetForm(),
                        i.hide()
                }))
        }
    }
}();

KTUtil.onDOMContentLoaded((function () {
    KTModalemployeesAdd.init();
}));
