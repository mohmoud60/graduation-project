function loadNotifications() {
    $.ajax({
        url: 'assets/php/notifications.php?action=get_notifications',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var notifications = data.notifications;
            
            // فرز الإشعارات من الأحدث إلى الأقدم
            notifications.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            var notificationItems = "";
            notifications.forEach(function(notification) {
                var formattedDate = moment(notification.created_at , "hh:mm A DD-MM-YYYY").locale('ar').fromNow();
                notificationItems += `
                    <!--begin::Item-->
                    <div class="d-flex flex-stack py-4 align-items-center">
                        <!--begin::Red Bar-->
                        <div class="bg-danger me-2" style="width: 5px; height: 100%;"></div>
                        <!--end::Red Bar-->
                        <!--begin::Section-->
                        <div class="d-flex align-items-center flex-grow-1">
                        <!--begin::Symbol-->
                        <span class="bullet bullet-vertical bg-info me-5  h-15px fs-1"></span>

                        <!--end::Symbol-->
                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">${notification.content}</a>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->
                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">${formattedDate}</span>
                        <!--end::Label-->
                    </div>
                    <!--end::Item-->`;
            });

            $("#Notifications_iteam").html(notificationItems);

            if (data.unread_count > 0) {
                // تحديث العدد الظاهر بجوار رمز الإشعارات
                $(".unread-count").text(data.unread_count).show();
            } else {
                // إخفاء العدد إذا لم يكن هناك إشعارات غير مقروئة
                $(".unread-count").hide();
            }
        },
        error: function(error) {
            console.error('Error fetching notifications:', error);
        }
    });
}


function markNotificationsAsRead() {
    $.ajax({
        url: 'assets/php/notifications.php?action=mark_as_read',
        type: 'POST',
        success: function(response) {
            if (response.status === "success") {
                console.log("Notifications marked as read");
            } 
        }
    });
}



$(document).ready(function() {
    loadNotifications();
    // عند النقر على رمز الإشعارات
    $("#kt_menu_item_wow").on("click", function() {
        loadNotifications();

        markNotificationsAsRead(); // هنا ننفذ الوظيفة عند النقر على الرمز
    });
});


$(document).on('click', '#clear_Notifications', function() {
    $.ajax({
        url: 'assets/php/notifications.php?action=clear_notifications',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            if (data.status === "success") {
                // إخفاء أو حذف عناصر الإشعارات من الواجهة الرئيسية إذا أردت
                $("#Notifications_iteam").empty();
                $(".unread-count").hide();
            } else {
                console.error('حدث خطأ أثناء حذف الإشعارات.');
            }
        },
        error: function(error) {
            console.error('Error:', error);
        }
    });
});



