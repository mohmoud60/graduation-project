"use strict";
var KTModalSalaryPayment = function () {
    var t, e, o, n;
    return {
        init: function () {
            n = new bootstrap.Modal(document.querySelector("#pay_salary")),
                t = document.querySelector("#pay_salary_form"),
                e = t.querySelector("#pay_salary_submit"),
                o = t.querySelector("#pay_salary_cancel");

                e.addEventListener("click", (function (ev) {
                    ev.preventDefault();
                

                    var selectedEmployeeId = $('#employeeSelect').val();
                    var paymentValue = $('#current_payment_div').find('#current_payment_value').text().replace(/[^0-9.]/g, "");
                
                    $.ajax({
                        url: 'assets/php/employee_transaction.php?action=pay_salary',
                        method: 'POST',
                        data: {
                            employee_id: selectedEmployeeId,
                            amount: paymentValue,
                            transaction_type: 'Salary'
                        },
                        success: function(response) {
                            if(response.success) {
                                Swal.fire({
                                    text: "تم الدفع بنجاح!",
                                    icon: "success",
                                    confirmButtonText: "حسناً",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                                n.hide();
                                t.reset();
                                $('#basic_salary_div, #loan_div, #current_payment_div').hide();
                            } else {
                                Swal.fire({
                                    text: "حدث خطأ أثناء الدفع.",
                                    icon: "error",
                                    confirmButtonText: "حسناً",
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
                        // إخفاء التفاصيل
                        $('#basic_salary_div, #loan_div, #current_payment_div').hide();
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
    KTModalSalaryPayment.init();
});

$(document).ready(function() {
    // اخفاء العناصر عند تحميل الصفحة
    $('#basic_salary_div, #loan_div, #current_payment_div').hide();
    
    $('#pay_salary').on('shown.bs.modal', function (e) {
        $.getJSON('assets/php/employee_transaction.php?action=get_employees', function(data) {
            var select = $('#employeeSelect');
            select.empty();
            select.append('<option></option>');
            $.each(data, function(index, employee) {
                select.append('<option value="' + employee.Employee_id + '">' + employee.Employee_FullName + '</option>');
            });
        });
    });

    $('#employeeSelect').change(function() {
        var selectedEmployeeId = $(this).val();
        if (selectedEmployeeId) {
            $.getJSON('assets/php/employee_transaction.php?action=get_employees', function(data) {
                $.each(data, function(index, employee) {
                    if (employee.Employee_id == selectedEmployeeId) {
                        if (employee.salary_paid == 1) {
                            // تم دفع الراتب
                            Swal.fire({
                                icon: 'warning',
                                text: 'تم دفع راتب هذا الشهر بالفعل!',
                                confirmButtonText: 'حسناً',
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            });
                            // اخفاء تفاصيل الراتب
                            $('#basic_salary_div, #loan_div, #current_payment_div').hide();
                            $('#pay_salary_submit').prop('disabled', true); // تعطيل الزر
                        } else {
                            // لم يتم دفع الراتب بعد
                            $('#basic_salary_div').show().find('#basic_salary_value').text(employee.Salary + " ₪");
                            $('#loan_div').show().find('#loan_value').text(employee.loan + " ₪");
                            var currentPayment = employee.Salary - employee.loan;
                            $('#current_payment_div').show().find('#current_payment_value').text(currentPayment + " ₪");
                            $('#pay_salary_submit').prop('disabled', false); // تمكين الزر
                        }
                    }
                });
            });
        } else {
            $('#basic_salary_div, #loan_div, #current_payment_div').hide();
            $('#pay_salary_submit').prop('disabled', true); // تعطيل الزر عندما لا يتم اختيار موظف
        }
    });
    
});
