document.addEventListener("DOMContentLoaded", function () {
    fetchRoles();

    function fetchRoles() {
        fetch('assets/php/users_rols.php?action=listing_rols')
            .then(response => response.json())
            .then(data => {
                renderRoles(data);
            })
            .catch(error => console.error('Error fetching roles:', error));
    }

    function renderRoles(roles) {
        const container = document.querySelector("#kt_app_content_container .row");
        container.innerHTML = ""; // تنظيف الحاوية
    
        roles.forEach(role => {
            const permissions = role.permissions
                ? role.permissions.split(', ').map(permission => `
                    <div class="d-flex align-items-center py-2">
                        <span class="bullet bg-primary me-3"></span>${permission}
                    </div>
                `).join('')
                : `<div class="d-flex align-items-center py-2 text-gray-500">لا توجد صلاحيات</div>`; // نص بديل في حالة عدم وجود صلاحيات
    
            container.innerHTML += `
                <!--begin::Col-->
                <div class="col-md-4">
                    <!--begin::Card-->
                    <div class="card card-flush h-md-100">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>${role.role_description}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-1">
                            <div class="fw-bold text-gray-600 mb-5">إجمالي المستخدمين الذين لديهم هذا الدور: ${role.total_users}</div>
                            <div class="d-flex flex-column text-gray-600">
                                ${permissions}
                            </div>
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer flex-wrap pt-0">
                            <button type="button" 
                                class="btn btn-light btn-active-light-primary my-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#kt_modal_update_role" 
                                onclick="loadRolePermissions(${role.role_id}, '${role.role_description}')"
                                data-role-id="${role.role_id}">
                                تعديل الصلاحيات
                            </button>
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Col-->
            `;
        });
    
        // إضافة بطاقة "إضافة صلاحيات جديدة"
        container.innerHTML += `
            <!--begin::Add new card-->
            <div class="col-md-4">
                <div class="card h-md-100">
                    <div class="card-body d-flex flex-center">
                        <button type="button" class="btn btn-clear d-flex flex-column flex-center" data-bs-toggle="modal" data-bs-target="#kt_modal_add_role">
                            <img src="assets/media/auth/4.png" alt="" class="mw-100 mh-150px mb-7" />
                            <div class="fw-bold fs-3 text-gray-600 text-hover-primary">إضافة صلاحيات جديدة</div>
                        </button>
                    </div>
                </div>
            </div>
            <!--end::Add new card-->
        `;
    }
    
});

function loadRolePermissions(roleId, roleDescription) {
    const modal = document.querySelector("#kt_modal_update_role");
    modal.setAttribute("data-role-id", roleId); // تخزين role_id داخل المودال

    const roleNameInput = modal.querySelector("input[name='role_name']");
    roleNameInput.value = roleDescription;

    // تحميل الصلاحيات
    const permissionsContainer = modal.querySelector(".table-responsive tbody");
    fetch(`assets/php/users_rols.php?action=get_role_permissions&role_id=${roleId}`)
        .then(response => response.json())
        .then(data => {
            let permissionsHTML = '';
            data.permissions.forEach(permission => {
                const isChecked = permission.assigned === "1" ? "checked" : ""; // تحقق صحيح من القيمة
                permissionsHTML += `
                    <tr>
                        <td class="text-gray-800">${permission.description}</td>
                        <td>
                            <label class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="${permission.id}" 
                                    ${isChecked} />
                                <span class="form-check-label">اختيار</span>
                            </label>
                        </td>
                    </tr>
                `;
            });
            permissionsContainer.innerHTML = permissionsHTML;
        })
        .catch(error => console.error('Error fetching role permissions:', error));
}


document.querySelector("#kt_modal_update_role_form").addEventListener("submit", function (event) {
    event.preventDefault();

    const modal = document.querySelector("#kt_modal_update_role");
    const roleId = modal.getAttribute("data-role-id"); // الحصول على role_id من المودال
    if (!roleId) {
        Swal.fire({
            icon: "error",
            title: "خطأ",
            text: "لا يوجد معرف الدور.",
        });
        return;
    }

    const permissions = Array.from(document.querySelectorAll("#kt_modal_update_role .form-check-input:checked"))
        .map(input => input.value);

    const updatedData = {
        role_id: roleId,
        permissions: permissions,
    };

    fetch("assets/php/users_rols.php?action=update_role_permissions", {
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
                    text: "تم تحديث الصلاحيات بنجاح!",
                    confirmButtonText: "موافق",
                }).then(() => {
                    location.reload(); // إعادة تحميل الصفحة بعد إغلاق التنبيه
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: "خطأ أثناء التحديث: " + data.message,
                });
            }
        })
        .catch(error => {
            console.error("Error updating role permissions:", error);
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "حدث خطأ أثناء الاتصال بالخادم.",
            });
        });
});
