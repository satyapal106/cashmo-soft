@extends('admin.layouts.master')
@section('styles')
    <!-- Sweet Alert css-->
    <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">All Retailer</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="customerList">
                    <div class="card-header border-bottom-dashed">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <div>
                                    <h5 class="card-title mb-0">{{ $title }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex flex-wrap align-items-start gap-2">
                                    <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i
                                            class="ri-delete-bin-2-line"></i></button>
                                    <a href="{{ url('admin/add-rechargeplan') }}" class="btn btn-success add-btn"><i
                                            class="ri-add-line align-bottom me-1"></i> Add Recharge Plan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-bottom-dashed border-bottom">
                        <form id="filterForm">
                            <div class="row g-3">
                                <div class="col-xl-6">
                                    <div class="search-box">
                                        <input type="text" id="searchInput" class="form-control"
                                            placeholder="Search for provider, amount, etc.">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="row g-3">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" id="datepicker-range"
                                                data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true"
                                                placeholder="Select date range">
                                        </div>
                                        <div class="col-sm-4">
                                            <select class="form-control" id="statusFilter">
                                                <option value="">All Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" class="btn btn-primary w-100"
                                                onclick="loadRechargePlans(1);">
                                                <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th data-ordering="false">#</th>
                                    <th data-ordering="false">Provider Name</th>
                                    <th data-ordering="false">Amount</th>
                                    <th data-ordering="false">Validaty</th>
                                    <th data-ordering="false">Data</th>
                                    <th data-ordering="false">Calling</th>
                                    <th data-ordering="false">Sms</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="plansTableBody"></tbody>
                        </table>
                        <div id="paginationWrapper" class="d-flex justify-content-center mt-3"></div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!-- container-fluid -->
@stop

@section('scripts')
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Sweet alert init js-->
    <script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>
    <script>
        function loadRechargePlans(page = 1) {
            const search = $('#searchInput').val();
            const dateRange = $('#datepicker-range').val();
            const status = $('#statusFilter').val();

            console.log({
                search,
                dateRange,
                status
            });

            $.ajax({
                url: `/admin/get-recharge-plan?page=${page}`,
                type: "GET",
                data: {
                    search: search,
                    date_range: dateRange,
                    status: status
                },
                dataType: "json",
                success: function(response) {
                    let tableBody = "";

                    if (response.status) {
                        $.each(response.data, function(index, row) {
                            tableBody += `
                            <tr id="rechargeRow-${row.id}">
                                <td>${index + 1}</td>
                                <td>${row.provider.provider_name}</td>
                                <td>₹${row.amount}</td>
                                <td>${row.time_duration === "other" ? (row.other_duration ?? "NA") : `${row.validity} ${row.time_duration}`}</td>
                                <td>${
                                    row.data_renewal === "other" 
                                        ? (row.other_data_renewal ?? "NA") 
                                        : row.data_renewal === "per day"
                                            ? `${row.data} / Day`
                                            : row.data_renewal === "per plan"
                                                ? `${row.data} / Plan`
                                                : `${row.data} ${row.data_renewal}`
                                }</td>
                                <td>${row.calling_options}</td>
                                <td>${row.sms_count}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggleStatus" type="checkbox" data-id="${row.id}" ${row.status == 1 ? 'checked' : ''}>
                                        <label class="form-check-label status-label">${row.status == 1 ? 'Active' : 'Inactive'}</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="/admin/add-rechargeplan/${row.id}" class="dropdown-item">Edit</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item deleteRechargePlan" data-id="${row.id}">Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>`;
                        });

                        buildPagination(response.pagination);
                    } else {
                        tableBody = `
                        <tr>
                            <td colspan="9" class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                            </td>
                        </tr>`;
                        $("#paginationWrapper").html('');
                    }

                    $("#plansTableBody").html(tableBody);
                },
                error: function() {
                    alert("Error fetching recharge plans. Please try again.");
                }
            });
        }

        function buildPagination(pagination) {
            let html = '';
            const currentPage = pagination.current_page;
            const lastPage = pagination.last_page;

            html += currentPage > 1 ?
                `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a></li>` :
                `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;

            for (let i = 1; i <= lastPage; i++) {
                html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }

            html += currentPage < lastPage ?
                `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Next</a></li>` :
                `<li class="page-item disabled"><span class="page-link">Next</span></li>`;

            $("#paginationWrapper").html(`
            <nav>
                <ul class="pagination justify-content-center">${html}</ul>
            </nav>`);

            $("#paginationWrapper .page-link").click(function(e) {
                e.preventDefault();
                const page = $(this).data("page");
                if (page) {
                    loadRechargePlans(page);
                }
            });
        }

        // 👇 Keep the rest inside ready
        $(document).ready(function() {
            loadRechargePlans();

            $('#searchInput').keypress(function(e) {
                if (e.which === 13) {
                    loadRechargePlans(1);
                }
            });

            $('#filterForm').submit(function(e) {
                e.preventDefault();
                loadRechargePlans(1);
            });

            $(document).on("change", ".toggleStatus", function() {
                let checkbox = $(this);
                let serviceId = checkbox.data("id");
                let newStatus = checkbox.prop("checked") ? 1 : 0;
                let statusLabel = checkbox.closest('.form-check').find('.status-label');

                $.ajax({
                    url: "{{ url('admin/update-recharge-plan-status') }}",
                    type: "POST",
                    data: {
                        id: serviceId,
                        status: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            statusLabel.text(newStatus == 1 ? "Active" : "Inactive");
                            showToast("Recharge-plan status updated successfully", 'success');
                        } else {
                            checkbox.prop("checked", !newStatus);
                            showToast("Failed to update status", 'danger');
                        }
                    },
                    error: function() {
                        checkbox.prop("checked", !newStatus);
                        showToast("Error updating status", 'danger');
                    }
                });
            });

            $(document).on("click", ".deleteRechargePlan", function() {
                let rechargeId = $(this).data("id");

                Swal.fire({
                    html: `
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                        trigger="loop" colors="primary:#f7b84b,secondary:#f06548" 
                        style="width:100px;height:100px">
                    </lord-icon>
                    <p><b>Are you Sure ?</b></p>
                    <p style="font-size:15px;">Are you sure you want to remove</br/> this provider?</p>
                `,
                    showCancelButton: true,
                    confirmButtonColor: "#405189",
                    cancelButtonColor: "#f06548",
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: "Cancel",
                    customClass: {
                        confirmButton: 'btn btn-primary btn-sm',
                        cancelButton: 'btn btn-danger btn-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/delete-recharge-plan') }}/" + rechargeId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    $("#rechargeRow-" + rechargeId).remove();
                                    Swal.fire("Deleted!",
                                        "The Recharge Plan has been removed successfully.",
                                        "success");
                                } else {
                                    Swal.fire("Error!",
                                        "Failed to delete the Recharge Plan.",
                                        "error");
                                }
                            },
                            error: function() {
                                Swal.fire("Error!",
                                    "Something went wrong. Please try again.",
                                    "error");
                            }
                        });
                    }
                });
            });
        });
    </script>

@stop
