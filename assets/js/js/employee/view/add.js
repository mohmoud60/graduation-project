"use strict";
var KTModalTransactionAdd = function () {
    var t, e, o, n;
    return {
        init: function () {
            n = new bootstrap.Modal(document.querySelector("#add_advances")),
                t = document.querySelector("#add_advances_form"),
                e = t.querySelector("#add_advances_submit"),
                o = t.querySelector("#add_advances_cancel");

            e.addEventListener("click", (function (ev) {
                ev.preventDefault();
                var tr_amount = parseFloat($("#amount").val());
                var current_loan = parseFloat(document.getElementById('getloan').value);
                var salary = parseFloat(document.getElementById('getsalary').value);
                if ( !tr_amount) {
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

                if ((tr_amount + current_loan) > salary) {
                    Swal.fire({
                        text: "قيم السلف تجاوزت الراتب الأساسي",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "حسنًا!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                    return;
                }

                Swal.fire({
                    text: "هل تريد بالفعل القيام بالدفع بقيمة " + tr_amount + "؟",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "نعم ، ادفع!",
                    cancelButtonText: "لا، ألغي!",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then(function (res) {
                    if (res.isConfirmed) {
                        e.setAttribute("data-kt-indicator", "on");
                        e.disabled = true;

                        $.ajax({
                            url: 'assets/php/employee_transaction.php?action=pay_advance',
                            type: 'post',
                            data: {
                                'advance': tr_amount,
                                'id': location.search.split('employee_id=')[1]
                            },
                            success: function(response){
                                e.removeAttribute("data-kt-indicator");
                                e.disabled = false;

                                if (response === 'success') {
                                    Swal.fire({
                                        text: "تم الدفع بنجاح",
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
                    }
                });
            }));

            o.addEventListener("click", (function (e) {
                e.preventDefault();
                Swal.fire({
                    text: "هل أنت متأكد من أنك تريد إلغاء العملية؟",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "نعم ، ألغ!",
                    cancelButtonText: "لا، ألغي!",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then(function (t) {
                    t.isConfirmed && (n.hide(), t.reset())
                })
            }));
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTModalTransactionAdd.init()
});



$('#advance-link').on('click', function(event) {
    event.preventDefault();

    var selectedEmployeeId = location.search.split('employee_id=')[1];

    $.ajax({
        url: 'assets/php/employee_transaction.php?action=get_employees',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $.each(data, function(index, employee) {
                if (employee.Employee_id == selectedEmployeeId) {
                    if (employee.salary_paid == 1) {
                        // تم دفع الراتب
                        Swal.fire({
                            icon: 'warning',
                            text: 'السلف متوقفة حتى بداية الشهر الجديد!',
                            confirmButtonText: 'حسناً',
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    } else {
                        // لم يتم دفع الراتب بعد
                        $('#add_advances').modal('show');
                    }
                    return false;  // End the iteration
                }
            });
        }
    });
});
