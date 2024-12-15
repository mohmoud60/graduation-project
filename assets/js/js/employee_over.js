$(document).ready(function() {
    $.ajax({
        url: "assets/php/loadData.php?action=load_data",
        type: "GET",
        dataType: "json",
        success: function(employeeData) {
            $("#EFullName").text(employeeData.Employee_FullName);
            $("#FFullName").text(employeeData.Employee_FullName);
            $("#HFullName").text(employeeData.Employee_FullName);
            $("#Eemail").text(employeeData.Employee_Email);
            $("#Hemail").text(employeeData.Employee_Email);
            $("#phone").text(employeeData.Employee_Phone);
            $("#address").text(employeeData.Employee_Address);
            $("#job_titel").text(employeeData.job_titel);
            $("#Fjob_titel").text(employeeData.job_titel);
            $("#Salary").text('₪' + employeeData.Salary);
            $("#PFullName").val(employeeData.Employee_FullName);
            $("#Pemail").val(employeeData.Employee_Email);
            $("#Pphone").val(employeeData.Employee_Phone);
            $("#Paddress").val(employeeData.Employee_Address);

            // Create a new image element
            var img = new Image();

            // Set the image source to the avatar path
            img.src = employeeData.avatar_path;

            // If the image fails to load, set it to the default avatar
            img.onerror = function() {
                $("#user_avatar").attr("src", "assets/media/avatars/blank.png");
                $("#user_avatar1").attr("src", "assets/media/avatars/blank.png");
                $("#user_avatar3").attr("src", "assets/media/avatars/blank.png");
                $("#user_avatar4").attr("src", "assets/media/avatars/blank.png");
                $('#avatar-wrapper').css('background-image', 'url(assets/media/avatars/blank.png)');
            };

            // If the image loads successfully, set it to the avatar path
            img.onload = function() {
                var url = 'url(' + employeeData.avatar_path + ')';
                $('#avatar-wrapper').css('background-image', url);
                $("#user_avatar").attr("src", employeeData.avatar_path);
                $("#user_avatar1").attr("src", employeeData.avatar_path);
                $("#user_avatar3").attr("src", employeeData.avatar_path);
                $("#user_avatar4").attr("src", employeeData.avatar_path);
            };
        },
        
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
    
});


