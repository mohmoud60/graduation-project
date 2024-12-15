"use strict";
var KTModalCustomersAdd = function () {
    var t, e, o, n, r, i;
    return {
        init: function () {
            i = new bootstrap.Modal(document.querySelector("#add_TraderTable")),
                r = document.querySelector("#add_TraderTable_form"),
                t = r.querySelector("#add_TraderTable_submit"),
                e = r.querySelector("#add_TraderTable_cancel"),
                o = r.querySelector("#add_TraderTable_close"),
                n = FormValidation.formValidation(r, {
                    fields: {
                        customer_name: {
                            validators: {
                                notEmpty: { message: "اسم الزبون مطلوب" }
                            }
                        },
                        customer_address: {
                            validators: {
                                notEmpty: { message: "عنوان الزبون مطلوب" }
                            }
                        },
                        customer_phone: {
                            validators: {
                                notEmpty: { message: "رقم هاتف الزبون مطلوب" },
                                regexp: {
                                    regexp: /^[0-9]+$/,
                                    message: "يرجى إدخال رقم الهاتف باستخدام الأرقام الإنجليزية فقط"
                                }
                            }
                        },                        
                        type_id: {
                            validators: {
                                notEmpty: { message: "فئة حساب الزبون مطلوب" }
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
                            url: 'assets/php/process_customer.php?action=add_customer',
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
    KTModalCustomersAdd.init();
}));
