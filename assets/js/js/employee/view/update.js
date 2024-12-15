var employee_id = document.querySelector("#update_employee_form").dataset.employeeId;

window.onload = function() {
    localStorage.removeItem('activeTab');
}

document.querySelectorAll(".nav-link").forEach(function(tab) {
    tab.addEventListener('click', function(e) {
        localStorage.setItem('activeTab', e.target.getAttribute('href'));
    });
});


document.addEventListener('DOMContentLoaded', (event) => {
    let activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        let activeTabElement = document.querySelector(`a[href="${activeTab}"]`);
        let tab = new bootstrap.Tab(activeTabElement);

        tab.show();
    }
});



document.querySelector("#update_employee_form").addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    formData.append("employee_id", employee_id);  // Add the employee_id to formData

    fetch('assets/php/employee_transaction.php?action=update_profile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);

        if (data.includes("success")) {
            Swal.fire({
                title: 'نجاح!',
                text: 'تم تحديث البيانات بنجاح!',
                icon: 'success',
                confirmButtonText: 'موافق'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // Reload the current page
                }
            });
        } else if (data.includes("The file") && data.includes("has been uploaded.")) {
            Swal.fire({
                title: 'نجاح!',
                text: 'تم رفع الملف بنجاح!',
                icon: 'success',
                confirmButtonText: 'موافق'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // Reload the current page
                }
            });
        } else {
            Swal.fire({
                title: 'خطأ!',
                text: 'هناك خطأ ما، الرجاء المحاولة مرة أخرى.',
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        Swal.fire({
            title: 'خطأ!',
            text: 'هناك خطأ ما، الرجاء المحاولة مرة أخرى.',
            icon: 'error',
            confirmButtonText: 'موافق'
        });
    });
});
