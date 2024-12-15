"use strict";

var KTSuppliersList = function () {
    var t, e;

    var o = () => {
        e.querySelectorAll('[data-kt-customer-table-filter="delete_row"]').forEach((e => {
            e.addEventListener("click", (function (e) {
                e.preventDefault();
        
                const o = e.target.closest("tr"),
                    n = o.querySelectorAll("td")[0].querySelector("input").value;
                const n_name = o.querySelectorAll("td")[1].innerText;

                Swal.fire({
                    text: "هل أنت متأكد أنك تريد حذف " + n_name + "؟",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "نعم ، احذف!",
                    cancelButtonText: "لا ، إلغاء",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((function (e) {
                    if(e.value) {
                        $.ajax({
                            url: 'assets/php/process_customer.php',
                            type: 'POST',
                            data: {
                                customer_id: n
                            },
                            success: function(response) {
                                // الاستجابة يجب أن تحتوي على خاصية النجاح للتحقق منها
                                    Swal.fire({
                                        text: "لقد حذفت " + n_name + "!.",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "حسنًا!",
                                        customClass: {
                                            confirmButton: "btn fw-bold btn-primary"
                                        }
                                    }).then(() => {
                                        t.row($(o)).remove().draw(); // حذف الصف من جدول DataTable
                                    });
                          
                            },
                            error: function() {
                                Swal.fire({
                                    text: n_name + " لم يتم حذفه.",
                                    icon: "error",
                                    buttonsStyling: !1,
                                    confirmButtonText: "حسنًا!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                });
                            }
                        });
                    } else if("cancel" === e.dismiss) {
                        Swal.fire({
                            text: n_name + " لم يتم حذفه.",
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "حسنًا!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary"
                            }
                        });
                    }
                }));
            }))
        }));
        
    };

    var n = () => {
        const o = e.querySelectorAll('[type="checkbox"]'),
            n = document.querySelector('[data-kt-customer-table-select="delete_selected"]');

        o.forEach((t => {
            t.addEventListener("click", (function () {
                setTimeout((function () {
                    c()
                }), 50)
            }))
        }));

        n.addEventListener("click", (function () {
            Swal.fire({
                text: "هل أنت متأكد أنك تريد حذف الموردين المختارين؟",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "نعم ، احذف!",
                cancelButtonText: "لا ، إلغاء",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then((function (n) {
                if(n.value) {
                    // Collect all selected trader ids
                    let selectedTraderIds = [];
                    o.forEach((e => {
                        if(e.checked) {
                            selectedTraderIds.push(e.value);
                        }
                    }));
        
                    // Send AJAX request to delete selected traders
                    $.ajax({
                        url: 'assets/php/process_customer.php',
                        type: 'POST',
                        data: {
                            customer_ids: JSON.stringify(selectedTraderIds)
                        },
                        success: function(response) {
                            // Refresh the table
                            t.draw();
        
                            Swal.fire({
                                text: "لقد قمت بحذف جميع الموردين المختارين !.",
                                icon: "success",
                                buttonsStyling: !1,
                                confirmButtonText: "حسنًا!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            });
                        },
                        error: function() {
                            Swal.fire({
                                text: "لم يتم حذف الموردين المختارين.",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "حسنًا!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            });
                        }
                    });
        
                } else if("cancel" === n.dismiss) {
                    Swal.fire({
                        text: "لم يتم حذف الموردين المختارين.",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "حسنًا!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary"
                        }
                    });
                }
            }));
        })
    )};

    const c = () => {
        const t = document.querySelector('[data-kt-customer-table-toolbar="base"]'),
            o = document.querySelector('[data-kt-customer-table-toolbar="selected"]'),
            n = document.querySelector('[data-kt-customer-table-select="selected_count"]'),
            c = e.querySelectorAll('tbody [type="checkbox"]');

        let r = !1,
            l = 0;

        c.forEach((t => {
            t.checked && (r = !0, l++)
        }));

        r ? (n.innerHTML = l, t.classList.add("d-none"), o.classList.remove("d-none")) : (t.classList.remove("d-none"), o.classList.add("d-none"))
    };

    return {
        init: function () {
            (e = document.querySelector("#TraderTable")) && (
                (t = $(e).DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    retrieve: true,
                    ajax: {
                        url: 'assets/php/process_customer.php',
                        type: 'GET',
                        data: { action : "drow_customer"},
                    },
                    language: {
                        info: "عرض _START_ إلى _END_ من _TOTAL_ سجلات",
                        infoEmpty: "لا يوجد سجلات للعرض",
                        infoFiltered: "(تم التصفية من _MAX_ سجلات)",
                        zeroRecords: "لم يتم العثور على نتائج مطابقة",
                        // add more translations as needed
                    },
                    "columns": [
                        { 
                            "data": "customer_id",
                            "render": function (data) {
                                return `
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="${data}" />
                                    </div>`;
                            }
                        },
                        {
                            "data": "customer_id",
                            "render": function (data, type, row) {
                                return '<a href="customer_veiw.php?customer_id=' + row.customer_id + '" class="text-gray-800 text-hover-primary mb-1">' + data + '</a>';
                            }
                        },
                        {
                            "data": "full_name",
                            "render": function (data, type, row) {
                                return '<a href="customer_veiw.php?customer_id=' + row.customer_id + '" class="text-gray-800 text-hover-primary mb-1">' + data + '</a>';
                            }
                        },
                        { 
                            "data": "customer_address",
                            "render": function (data, type, row) {
                                return '<a class="text-gray-600 text-hover-primary mb-1">' + data + '</a>';
                            }
                        },
                        { 
                            "data": "customer_phone",
                            "render": function (data, type, row) {
                                return '<a class="text-gray-600 text-hover-primary mb-1">' + data + '</a>';
                            }
                        },
                        {
                            "data": "type_sname",
                            "render": function (data, type, row) {
                                let backgroundColor = 'badge-light-primary'; // اللون الافتراضي
                        
                                // تغيير لون الخلفية بناءً على نوع الحساب
                                if (data === 'حسابات زبون') {
                                    backgroundColor = 'badge-secondary'; // لون أزرق
                                }
                        
                                return '<a class="badge ' + backgroundColor + ' fw-bold px-5 py-3">' + data + '</a>';
                            }
                        },
                        
                        {
                            "data": "balance",
                            "render": function (data, type, row) {
                                return data;
                            }
                        },   
                        {
                            "data": "customer_id",
                            "render": function (data) {
                                return `
                                    <td class="text-end">
                                        <div class="dropdown text-end">
                                            <button class="btn btn-sm btn-light dropdown-toggle " type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" data-bs-placement="bottom" aria-expanded="false">
                                            أجراءات
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item" href="customer_veiw.php?customer_id=${data}">عرض</a></li>
                                                <li><a class="dropdown-item" href="#" data-kt-customer-table-filter="delete_row">حذف</a></li>
                                            </ul>
                                        </div>
                                    </td>`;
                            }
                        }
                    ],
                    columnDefs: [{
                        orderable: !1,
                        targets: 0
                    }, {
                        orderable: !1,
                        targets: 4
                    }]
                })).on("draw", (function () {
                    n(), o(), c()
                })),
                n(),
                document.querySelector('[data-kt-customer-table-filter="search"]').addEventListener("keyup", (function (e) {
                    t.search(e.target.value).draw()
                })),
                o()
            )
        }
    };

}();

KTUtil.onDOMContentLoaded((function () {
    KTSuppliersList.init()
}));
