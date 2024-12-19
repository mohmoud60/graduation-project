document.addEventListener("DOMContentLoaded", function () {
   


    loadPermissions();
});

// دالة اختيار اللون بناءً على ID الدور
function getRoleBadgeClassById(roleId) {
    switch (roleId) {
        case "1":
            return "badge-light-primary"; // Administrator
        case "2":
            return "badge-light-danger"; // Developer
        case "3":
            return "badge-light-success"; // Analyst
        case "4":
            return "badge-light-info";    // Support
        case "5":
            return "badge-light-warning"; // Trial
        default:
            return "badge-light-secondary";    // لون افتراضي
    }
}


// تحميل البيانات وتوليد الصفوف
function loadPermissions() {
    const tableBody = document.querySelector("#kt_permissions_table tbody");

    fetch("assets/php/users_rols.php?action=get_permission")
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                let permissionsHTML = "";
                data.permissions.forEach((permission) => {
                    let rolesHTML = "غير مخصص";

                    if (permission.roles) {
                        const roles = permission.roles.split(", ");
                        rolesHTML = roles
                            .map((role) => {
                                const [roleId, roleName] = role.split(":");
                                return `<span class="badge ${getRoleBadgeClassById(roleId)} fs-6 m-1">${roleName}</span>`;
                            })
                            .join("");
                    }

                    permissionsHTML += `
                        <tr>
                            <td class="fs-5 m-1">${permission.permission_name}</td>
                            <td>${rolesHTML}</td>
                            <td>${permission.created_at}</td>
                            <td class="text-end">
                                <button class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#kt_modal_update_permission"
                                    onclick="loadPermissionForEdit(${permission.permission_id}, '${permission.permission_name}')">
                                    <i class="ki-duotone ki-setting-3 fs-3">
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																	<span class="path4"></span>
																	<span class="path5"></span>
																</i>
                                </button>
                                <button class="btn btn-icon btn-active-light-primary w-30px h-30px" 
                                    onclick="deletePermission(${permission.permission_id})">
                                   <i class="ki-duotone ki-trash fs-3">
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																	<span class="path4"></span>
																	<span class="path5"></span>
																</i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                tableBody.innerHTML = permissionsHTML;
            } else {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: data.message,
                });
            }
        })
        .catch((error) => {
            console.error("Error fetching permissions:", error);
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "حدث خطأ أثناء الاتصال بالخادم.",
            });
        });
}


function deletePermission(permissionId) {
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: "سيتم حذف الإذن نهائيًا!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذف!",
        cancelButtonText: "إلغاء",
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`assets/php/users_rols.php?action=delete_permission&permission_id=${permissionId}`)
    .then((response) => {
        console.log("Response Status:", response.status); // التحقق من حالة الاستجابة
        return response.json();
    })
    .then((data) => {
        console.log("Response Data:", data); // عرض البيانات المسترجعة
        if (data.success) {
            Swal.fire({
                icon: "success",
                title: "نجاح",
                text: data.message,
            });
            loadPermissions();
        } else {
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: data.message,
            });
        }
    })
    .catch((error) => {
        console.error("Error deleting permission:", error);
        Swal.fire({
            icon: "error",
            title: "خطأ",
            text: "حدث خطأ أثناء الاتصال بالخادم.",
        });
    });

        }
    });
}


document.querySelector("#kt_modal_update_permission_form").addEventListener("submit", function (event) {
    event.preventDefault();

    const modal = document.querySelector("#kt_modal_update_permission");
    const permissionId = modal.getAttribute("data-permission-id");
    const permissionName = modal.querySelector("input[name='permission_name']").value.trim();
    console.log("Permission ID in Modal:", permissionId);
    console.log("Permission Name in Modal:", permissionName);
    if (!permissionName) {
        Swal.fire({
            icon: "warning",
            title: "تحذير",
            text: "يرجى إدخال اسم الإذن.",
        });
        return;
    }

    const updatedData = {
        permission_id: permissionId,
        permission_name: permissionName,
    };

    fetch("assets/php/users_rols.php?action=update_permission", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(updatedData),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "نجاح",
                    text: data.message,
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: data.message,
                });
            }
        })
        .catch((error) => {
            console.error("Error updating permission:", error);
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "حدث خطأ أثناء الاتصال بالخادم.",
            });
        });
});


function loadPermissionForEdit(permissionId, permissionName, isCore) {
    const modal = document.querySelector("#kt_modal_update_permission");

    modal.setAttribute("data-permission-id", permissionId);
    modal.querySelector("input[name='permission_name']").value = permissionName;

    if (isCore) {
        document.querySelector("#core_permission_warning").classList.remove("d-none");
        document.querySelector("#save_permission_button").classList.add("d-none");
    } else {
        document.querySelector("#core_permission_warning").classList.add("d-none");
        document.querySelector("#save_permission_button").classList.remove("d-none");
    }
}




document.querySelector("#kt_modal_add_permission_form").addEventListener("submit", function (event) {
    event.preventDefault();

    const modal = document.querySelector("#kt_modal_add_permission");
    const permissionName = modal.querySelector("input[name='permission_name']").value.trim();
    const isCore = modal.querySelector("input[name='permissions_core']").checked ? 1 : 0;

    if (!permissionName) {
        Swal.fire({
            icon: "warning",
            title: "تحذير",
            text: "يرجى إدخال اسم الإذن.",
        });
        return;
    }

    const data = {
        permission_name: permissionName,
        permissions_core: isCore,
    };

    fetch("assets/php/users_rols.php?action=add_permission", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "نجاح",
                    text: data.message,
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: data.message,
                });
            }
        })
        .catch((error) => {
            console.error("Error adding permission:", error);
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "حدث خطأ أثناء الاتصال بالخادم.",
            });
        });
});

