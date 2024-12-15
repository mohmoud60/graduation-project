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

            // ربط زر الإلغاء
            const cancelButton = document.getElementById('kt_password_cancel');
            if (cancelButton) {
                cancelButton.addEventListener('click', function () {
                    const passwordForm = document.getElementById('kt_signin_change_password');
                    if (passwordForm) {
                        passwordForm.reset(); // إعادة تعيين الحقول
                    }
                    togglePasswordReset(); // إخفاء النموذج
                    resetButton.classList.remove('d-none'); // إعادة زر إعادة تعيين
                });
            }

            const passwordForm = document.getElementById('kt_signin_change_password');

            if (passwordForm) {
                const validator = FormValidation.formValidation(passwordForm, {
                    fields: {
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
                            const formData = new FormData(passwordForm);

                            fetch('assets/php/employee_transaction.php?action=update_password', {
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
                                            passwordForm.reset(); // إعادة تعيين النموذج
                                            togglePasswordReset(); // إخفاء النموذج
                                            resetButton.classList.remove('d-none'); // إعادة زر إعادة تعيين
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
                                .catch(() => {
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
    if (password.length < 8) return false;
    const hasLowerCase = /[a-z]/.test(password);
    const hasUpperCase = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
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
