"use strict";
var KTModalTransactionAdd = function () {
    var t, e, o, n;
    return {
        init: function () {
            n = new bootstrap.Modal(document.querySelector("#add_transaction")),
                t = document.querySelector("#add_transaction_form"),
                e = t.querySelector("#add_transaction_submit"),
                o = t.querySelector("#add_transaction_cancel");

            e.addEventListener("click", (function (ev) {
                ev.preventDefault();
                var tr_type = $("#transaction_type").val();
                var tr_amount = $("#amount").val();
                var tr_currency = $("#currency").val();
                var tr_descripcion = $("#descripcion").val();

                if (!tr_type || !tr_amount || !tr_currency || !tr_descripcion) {
                    Swal.fire({
                        text: "يجب تعبئة جميع الحقول!",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "حسنًا!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return;
                }
                
                e.setAttribute("data-kt-indicator", "on");
                e.disabled = true;

                $.ajax({
                    url: 'assets/php/traders_transaction.php',
                    type: 'post',
                    data: {
                        'tr_type': tr_type,
                        'tr_amount': tr_amount,
                        'tr_currency': tr_currency,
                        'tr_descripcion': tr_descripcion,
                        'trader_Id': location.search.split('traders_id=')[1]
                    },
                    success: function(response){
                        // Enable the button, remove loading state
                        e.removeAttribute("data-kt-indicator");
                        e.disabled = false;
                        
                        if (response === 'success') {
                            Swal.fire({
                                text: "تم الحفظ بنجاح",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "حسنًا!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function () {
                                n.hide();
                                t.reset();
                                window.location = t.getAttribute("data-kt-redirect");
                            });
                        } else {
                            Swal.fire({
                                text: "معذرة ، يبدو أنه تم اكتشاف بعض الأخطاء ، يرجى المحاولة مرة أخرى.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "حسنًا!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                        }
                    }
                });
            }));

            o.addEventListener("click", function(ev) {
                ev.preventDefault(),
                Swal.fire({
                    text: "هل أنت متأكد أنك تريد الإلغاء؟",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "نعم ، قم بإلغائها!",
                    cancelButtonText: "لا رجوع",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then(function (res) {
                    if (res.isConfirmed) {
                        n.hide();
                        t.reset();
                    } else if (res.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            text: "لم يتم إلغاء النموذج الخاص بك !.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            }
                        })
                    }
                });
            });
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTModalTransactionAdd.init();
});
