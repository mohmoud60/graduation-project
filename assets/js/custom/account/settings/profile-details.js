$(document).ready(function() {
    // Set the avatar_remove value to 'true' when the remove button is clicked
    $('[data-kt-image-input-action="remove"]').click(function() {
        $('input[name="avatar_remove"]').val('true');
    });

    // Reset the avatar_remove value when the cancel button is clicked
    $('[data-kt-image-input-action="cancel"]').click(function() {
        $('input[name="avatar_remove"]').val('false');
    });

    $('#kt_account_profile_details_submit').on('click', function(e) {
        e.preventDefault();

        // Collect data from form
        var formData = new FormData($('#kt_account_profile_details_form')[0]);

        // Send data to PHP script
        $.ajax({
            url: 'assets/php/update_profile.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Show success message
                Swal.fire({
                    icon: 'success',
                    text: 'تم التعديل بنجاح',
                    buttonsStyling: !1,
                    confirmButtonText: "حسنًا، فهمت!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    text: 'حدث خطأ أثناء التعديل',
                    confirmButtonText: "حسنًا، فهمت!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                });
            }
        });
    });
});
