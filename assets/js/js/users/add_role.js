document.addEventListener("DOMContentLoaded", function () {
    const permissionsContainer = document.getElementById("permissions_container");
    const form = document.getElementById("kt_modal_add_role_form");

    // جلب قائمة الصلاحيات
    fetch("assets/php/users_rols.php?action=get_permissions")
        .then((response) => response.json())
        .then((data) => {
            let permissionsHTML = `<table class="table align-middle table-row-dashed fs-6 gy-5">
                <tbody class="text-gray-600 fw-semibold">`;

            data.forEach((permission) => {
                permissionsHTML += `
                    <tr>
                        <td class="text-gray-800">${permission.description}</td>
                        <td>
                            <label class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input permission-checkbox" type="checkbox" value="${permission.id}" />
                                <span class="form-check-label">اختيار</span>
                            </label>
                        </td>
                    </tr>`;
            });

            permissionsHTML += `</tbody></table>`;
            permissionsContainer.innerHTML = permissionsHTML;
        })
        .catch((error) => {
            console.error("Error fetching permissions:", error);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حدث خطأ أثناء جلب الصلاحيات.',
            });
        });

    // إضافة دور جديد
    form.addEventListener("submit", function (event) {
        event.preventDefault();

        const roleName = form.role_name.value.trim();
        const permissionCheckboxes = document.querySelectorAll(".permission-checkbox:checked");
        const permissions = Array.from(permissionCheckboxes).map((checkbox) => checkbox.value);

        if (!roleName || permissions.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'تحذير',
                text: 'يرجى إدخال اسم الدور واختيار الصلاحيات.',
            });
            return;
        }

        const roleData = {
            role_name: roleName,
            permissions: permissions,
        };

        fetch("assets/php/users_rols.php?action=add_role", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(roleData),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'نجاح',
                        text: data.message,
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: data.message,
                    });
                }
            })
            .catch((error) => {
                console.error("Error adding role:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء الاتصال بالخادم.',
                });
            });
    });
});
