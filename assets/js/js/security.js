"use strict";
var KTAccountSettingsSigninMethods = function () {
    return {
        init: function () {
             // ربط زر إعادة التعيين بالوظيفة

             const resetButton = document.querySelector('#kt_signin_password_button button');
             if (resetButton) {
                 resetButton.addEventListener('click', function () {
                     togglePasswordReset();
                     resetButton.classList.add('d-none'); // إخفاء زر إعادة تعيين
                 });
             }

            
             const cancelButton = document.getElementById('kt_password_cancel');
                if (cancelButton) {
                    cancelButton.addEventListener('click', function () {
                        const passwordForm = document.getElementById('kt_signin_change_password');
                        if (passwordForm) {
                            passwordForm.reset(); // إعادة تعيين الحقول
                        }
                        togglePasswordReset(); // إخفاء النموذج
                        resetButton.classList.remove('d-none'); 
                    });
                }
 
 
            const passwordForm = document.getElementById('kt_signin_change_password');

            if (passwordForm) {
                const validator = FormValidation.formValidation(passwordForm, {
                    fields: {
                        currentpassword: { validators: { notEmpty: { message: "كلمة المرور الحالية مطلوبة" } } },
                        newpassword: {
                            validators: {
                                notEmpty: { message: "كلمة المرور الجديدة مطلوبة" },
                                callback: {
                                    message: "كلمة المرور الجديدة ضعيفة. يجب أن تحتوي على 8 أحرف على الأقل بما في ذلك رقم ورمز وحرف كبير وصغير.",
                                    callback: function (input) {
                                        return checkPasswordStrength(input.value);
                                    },
                                },
                            },
                        },
                        confirmpassword: {
                            validators: {
                                notEmpty: { message: "تأكيد كلمة المرور مطلوب" },
                                identical: {
                                    compare: function () {
                                        return passwordForm.querySelector('[name="newpassword"]').value;
                                    },
                                    message: "كلمة المرور وتأكيدها غير متطابقين",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                        }),
                    },
                });

                passwordForm.querySelector('#kt_password_submit').addEventListener('click', function (e) {
                    e.preventDefault();
                    validator.validate().then(function (status) {
                        if (status === 'Valid') {
                            // إرسال البيانات إلى الخادم
                            const formData = new FormData(passwordForm);

                            fetch('assets/php/employee_transaction.php?action=updatecupassword', {
                                method: 'POST',
                                body: formData,
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        Swal.fire({
                                            title: 'تم التحديث',
                                            text: data.message,
                                            icon: 'success',
                                            confirmButtonText: 'حسنًا',
                                        }).then(() => {
                                            // إعادة ضبط النموذج
                                            passwordForm.reset();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'خطأ',
                                            text: data.message,
                                            icon: 'error',
                                            confirmButtonText: 'حسنًا',
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: 'خطأ في الاتصال',
                                        text: 'تعذر الاتصال بالخادم. يرجى المحاولة لاحقًا.',
                                        icon: 'error',
                                        confirmButtonText: 'حسنًا',
                                    });
                                });
                        } else {
                            Swal.fire({
                                title: 'خطأ',
                                text: 'يرجى تصحيح الأخطاء في النموذج.',
                                icon: 'error',
                                confirmButtonText: 'حسنًا',
                            });
                        }
                    });
                });
            }
        },
    };
}();

document.addEventListener('DOMContentLoaded', function () {
    KTAccountSettingsSigninMethods.init();
});

/**
 * وظيفة للتحقق من قوة كلمة المرور
 */
function checkPasswordStrength(password) {
    // يجب أن تكون كلمة المرور على الأقل 8 أحرف
    if (password.length < 8) return false;

    // يجب أن تحتوي على حرف صغير
    const hasLowerCase = /[a-z]/.test(password);

    // يجب أن تحتوي على حرف كبير
    const hasUpperCase = /[A-Z]/.test(password);

    // يجب أن تحتوي على رقم
    const hasNumber = /[0-9]/.test(password);

    // يجب أن تحتوي على رمز خاص
    const hasSpecialChar = /[@#$%^&*(),.?":{}|<>]/.test(password);

    return hasLowerCase && hasUpperCase && hasNumber && hasSpecialChar;
}


function togglePasswordReset() {
    const passwordView = document.getElementById('kt_signin_password');
    const passwordEdit = document.getElementById('kt_signin_password_edit');

    if (passwordView && passwordEdit) {
        passwordView.classList.toggle('d-none');
        passwordEdit.classList.toggle('d-none');
    }
}
function togglePasswordReset() {
    const passwordDisplay = document.getElementById('kt_signin_password');
    const passwordEdit = document.getElementById('kt_signin_password_edit');
            
    if (passwordDisplay && passwordEdit) {
        passwordDisplay.classList.toggle('d-none');
        passwordEdit.classList.toggle('d-none');
    }
}