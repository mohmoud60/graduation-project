$(document).ready(function() {

    // Initialize DataTables
    var table = $('#TraderTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "assets/php/fetch_traders.php",
        "columns": [
            { 
                "data": "trader_id",
                "render": function (data) {
                    return `
                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="${data}" />
                        </div>`;
                }
            },
            { 
                "data": "trader_name",
                "render": function (data, type, row) {
                    return '<a class="text-gray-800 text-hover-primary mb-1">' + data + '</a>';
                }
            },
            { 
                "data": "trader_address",
                "render": function (data, type, row) {
                    return '<a class="text-gray-600 text-hover-primary mb-1">' + data + '</a>';
                }
            },
            { 
                "data": "trader_phone",
                "render": function (data, type, row) {
                    return '<a class="text-gray-600 text-hover-primary mb-1">' + data + '</a>';
                }
            },
            {
                "data": null,
                "render": function () {
                    return `
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" data-bs-placement="bottom" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="../../demo1/dist/apps/customers/view.html">View</a></li>
                                    <li><a class="dropdown-item" href="#" data-kt-customer-table-filter="delete_row">Delete</a></li>
                                </ul>
                            </div>
                        </td>`;
                }
            }
        ],
        "drawCallback": function() {
            // Reset the main checkbox after every draw
            $('#mainCheckbox').prop('checked', false);
        }
    });

    // Add event listener for the main checkbox
    $(document).on('click', '#mainCheckbox', function() {
        var isChecked = $(this).prop('checked');
        $('.rowCheckbox:visible').prop('checked', isChecked);
    });

    // Add event listener for the individual checkboxes
    $(document).on('click', '.rowCheckbox', function() {
        var allChecked = $('.rowCheckbox:visible').length === $('.rowCheckbox:visible:checked').length;
        $('#mainCheckbox').prop('checked', allChecked);
    });

    // Reset the main checkbox when DataTables perform operations such as pagination, ordering or searching
    $(document).on('draw.dt', function() {
        $('#mainCheckbox').prop('checked', false);
    });


});

