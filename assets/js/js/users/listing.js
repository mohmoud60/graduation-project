"use strict";

var KTUsersList = function () {
    var t, e;

    // دالة لتنسيق الوقت
    function formatTimeAgo(timestamp) {
        const now = new Date();
        const loginTime = new Date(timestamp);
        const diffInSeconds = Math.floor((now - loginTime) / 1000);

        if (diffInSeconds < 60) {
            return `${diffInSeconds} seconds ago`;
        } else if (diffInSeconds < 3600) {
            const mins = Math.floor(diffInSeconds / 60);
            return `${mins} mins ago`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} hours ago`;
        } else if (diffInSeconds < 172800) {
            return "Yesterday";
        } else {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} days ago`;
        }
    }

    // دالة لتنسيق التاريخ والوقت
    function formatDateTime(datetime) {
        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        };
        return new Date(datetime).toLocaleDateString('en-GB', options);
    }

    // دالة لتحديد الصورة الرمزية
    function getAvatarPath(avatar) {
        const defaultAvatar = "assets/media/avatars/blank.png";
        if (!avatar || avatar === "" || avatar === "null") {
            return defaultAvatar;
        }
        return avatar;
    }

    return {
        init: function () {
            (e = document.querySelector("#kt_table_users")) && (
                (t = $(e).DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    retrieve: true,
                    ajax: {
                        url: 'assets/php/users_rols.php',
                        type: 'GET',
                        data: { action: "listing_users" },
                    },
                    language: {
                        info: "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                        infoEmpty: "لا يوجد سجلات للعرض",
                        infoFiltered: "(تم التصفية من _MAX_ سجلات)",
                        zeroRecords: "لم يتم العثور على نتائج مطابقة",
                        search: "بحث:",
                        lengthMenu: "عرض _MENU_ سجلات",
                        paginate: {
                            first: "الأول",
                            last: "الأخير",
                            next: "التالي",
                            previous: "السابق",
                        },
                    },
                    columns: [
                        {
                            data: "Employee_id",
                            render: function (data) {
                                return `
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="${data}" />
                                    </div>`;
                            }
                        },
                        {
                            data: "Employee_FullName",
                            render: function (data, type, row) {
                                const avatar = getAvatarPath(row.avatar_path);
                                return `
                                    <div class="d-flex align-items-center">
                                        <!-- Avatar -->
                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                            <div class="symbol-label">
                                                <img src="${avatar}" alt="${data}" class="w-100" onerror="this.src='assets/media/avatars/blank.png'" />
                                            </div>
                                        </div>
                                        <!-- User Details -->
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                            <span>${row.Employee_Email}</span>
                                        </div>
                                    </div>`;
                            }
                        },
                        {
                            data: "role_name",
                            render: function (data) {
                                return `<span>${data}</span>`;
                            }
                        },
                        {
                            data: "Last_login",
                            render: function (data) {
                                return `
                                    <div class="badge badge-light fw-bold">${data ? formatTimeAgo(data) : 'N/A'}</div>`;
                            }
                        },
                        {
                            data: "CreatedDate",
                            render: function (data) {
                                return data ? formatDateTime(data) : 'N/A';
                            }
                        },
                        {
                            data: "Employee_id",
                            render: function (data) {
                                return `
                                    <td class="text-end">
                                        <button type="button" class="btn btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#kt_modal_add_user" 
                                            onclick="loadUserRole(${data})">
                                            تحديث صلاحيات
                                        </button>
                                    </td>`;
                            }
                        }
                    ],
                    columnDefs: [
                        {
                            orderable: false,
                            targets: [0, 5]
                        }
                    ]
                })),
                
                // ربط حقل البحث مع الجدول
                document.querySelector('[data-kt-user-table-filter="search"]').addEventListener("keyup", (function (event) {
                    t.search(event.target.value).draw();
                }))
            )
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    KTUsersList.init()
}));


function loadUserRole(employeeId) {
    const modal = document.querySelector("#kt_modal_add_user");
    modal.setAttribute("data-employee-id", employeeId); // تخزين Employee_id في المودال

    const userNameInput = modal.querySelector("input[name='user_name']");
    const roleOptionsContainer = modal.querySelector("#role_options_container");

    // استدعاء API للحصول على الصلاحيات
    fetch(`assets/php/users_rols.php?action=get_user_role_per&employee_id=${employeeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // تعبئة اسم المستخدم
                userNameInput.value = data.user_name;

                // إنشاء الخيارات ديناميكيًا
                let roleOptionsHTML = '';
                data.roles.forEach(role => {
                    const isChecked = role.assigned === "1" ? "checked" : ""; // تحقق صحيح من القيمة
                    roleOptionsHTML += `
                        <div class="d-flex fv-row mb-5">
                            <div class="form-check form-check-custom form-check-solid">
                                <input class="form-check-input me-3" name="user_role" type="radio" value="${role.id}" 
                                    id="role_option_${role.id}" ${isChecked} />
                                <label class="form-check-label" for="role_option_${role.id}">
                                    <div class="fw-bold text-gray-800">${role.description}</div>
                                </label>
                            </div>
                        </div>
                    `;
                });

                roleOptionsContainer.innerHTML = roleOptionsHTML;
            } else {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: "حدث خطأ أثناء تحميل بيانات الصلاحيات.",
                });
            }
        })
        .catch(error => {
            console.error("Error fetching user role permissions:", error);
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "حدث خطأ أثناء الاتصال بالخادم.",
            });
        });
}



document.querySelector("#kt_modal_add_user_form").addEventListener("submit", function (event) {
    event.preventDefault();

    const modal = document.querySelector("#kt_modal_add_user");
    const employeeId = modal.getAttribute("data-employee-id"); // الحصول على Employee_id
    const selectedRole = document.querySelector("input[name='user_role']:checked").value;

    if (!employeeId || !selectedRole) {
        Swal.fire({
            icon: "warning",
            title: "تحذير",
            text: "يرجى تحديد صلاحية للمستخدم.",
        });
        return;
    }

    const updatedData = {
        employee_id: employeeId,
        role_id: selectedRole,
    };

    fetch("assets/php/users_rols.php?action=update_user_role", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(updatedData),
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "نجاح",
                    text: "تم تحديث صلاحيات المستخدم بنجاح!",
                }).then(() => {
                    location.reload(); // إعادة تحميل الصفحة بعد إغلاق التنبيه
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: "خطأ أثناء تحديث الصلاحيات: " + data.message,
                });
            }
        })
        .catch(error => {
            console.error("Error updating user role:", error);
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "حدث خطأ أثناء الاتصال بالخادم.",
            });
        });
});

