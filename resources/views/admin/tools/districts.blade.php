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
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Recharge Plans</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="districtForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="userenname" class="form-label">Select State <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="state_id">
                                        <option value="">Select State</option>
                                        @foreach ($state as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="districtName" class="form-label">District Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter District Name" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="districtCode" class="form-label">District Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="code"
                                        placeholder="Enter District Code">
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-success w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end col-->
            </div>
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">All {{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">State Name</th>
                                        <th scope="col">District Name</th>
                                        <th scope="col">District code</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="districtsTableBody">
                                    <!-- Data will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
            <!--end row-->
        </div>
    </div>

    <!-- Edit District Modal -->
    <div class="modal fade" id="editDistrictModal" tabindex="-1" aria-labelledby="editDistrictModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDistrictModalLabel">Edit District</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDistrictForm">
                        <input type="hidden" id="editDistrictId">
                        <div class="mb-3">
                            <label for="editDistrictState" class="form-label">Select State</label>
                            <select class="form-control" id="editDistrictState" required>
                                <!-- Services will be loaded here dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editDistrictName" class="form-label">District Name</label>
                            <input type="text" class="form-control" id="editDistrictName" required>
                        </div> 
                        <div class="mb-3">
                            <label for="editDistrictCode" class="form-label">District Code</label>
                            <input type="text" class="form-control" id="editDistrictCode">
                        </div> 
                        <button type="submit" class="btn btn-primary">Update District</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap Toast Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
        <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>
    <!-- Sweet alert init js-->
    <script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>
    <script>
        // jQuery Document Ready Function
        $(document).ready(function() {
            fetchDistricts();

            // Add District
            $("#districtForm").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('admin/add-district') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === true) {
                            showToast(response.message, 'success');
                            $("#districtForm")[0].reset();
                            fetchDistricts();
                            $("#districtModal").modal("hide");
                        } else {
                            showToast("Failed to add District!", 'danger');
                        }
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            });

            // Fetch Districts
            function fetchDistricts() {
                $.ajax({
                    url: "{{ url('admin/get-districts') }}",
                    type: "GET",
                    success: function(districts) {
                        let districtsTable = $('#districtsTableBody');
                        districtsTable.empty();

                        if (districts.length > 0) {
                            districts.forEach((district, index) => {
                                districtsTable.append(`
                            <tr id="districtRow-${district.id}">
                                <td>${index + 1}</td>
                                <td>${district.state.name}</td>
                                <td>${district.name}</td>
                                <td>${district.code ? district.code : 'NA'}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggleStatus" type="checkbox"
                                            data-id="${district.id}" ${district.status == 1 ? 'checked' : ''}>
                                        <label class="form-check-label status-label">
                                            ${district.status == 1 ? 'Active' : 'Inactive'}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning editDistrict" 
                                        data-id="${district.id}" data-name="${district.name}"
                                        data-code="${district.code}" 
                                        data-state="${district.state.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteDistrict" data-id="${district.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                            });
                        } else {
                            districtsTable.append(
                                '<tr><td colspan="5" class="text-center">No districts found.</td></tr>'
                                );
                        }
                    },
                    error: function() {
                        showToast("Failed to load districts", 'danger');
                    }
                });
            }

            // Edit District Modal
            $(document).on("click", ".editDistrict", function() {
                let districtId = $(this).data("id");
                let districtName = $(this).data("name");
                let districtCode = $(this).data("code");
                let districtState = $(this).data("state");

                $("#editDistrictId").val(districtId);
                $("#editDistrictName").val(districtName);
                $("#editDistrictCode").val(districtCode);

                $.ajax({
                    url: "{{ url('admin/get-states') }}",
                    type: "GET",
                    success: function(states) {
                        let stateDropdown = $("#editDistrictState");
                        stateDropdown.empty();

                        states.forEach(state => {
                            let selected = state.id == districtState ? "selected" : "";
                            stateDropdown.append(
                                `<option value="${state.id}" ${selected}>${state.name}</option>`
                                );
                        });

                        $("#editDistrictModal").modal("show");
                    },
                    error: function() {
                        showToast("Failed to load states", "danger");
                    }
                });
            });

            // Update District
            $("#editDistrictForm").submit(function(e) {
                e.preventDefault();
                let districtId = $("#editDistrictId").val();
                let districtName = $("#editDistrictName").val();
                let districtCode = $("#editDistrictCode").val();
                let districtState = $("#editDistrictState").val();

                $.ajax({
                    url: `{{ url('admin/update-district') }}/${districtId}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: districtName,
                        code: districtCode,
                        state_id: districtState
                    },
                    success: function(response) {
                        $("#editDistrictModal").modal("hide");
                        showToast("District updated successfully", "success");
                        fetchDistricts();
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            });

            // Toggle Status
            $(document).on("change", ".toggleStatus", function() {
                let districtId = $(this).data("id");
                let newStatus = $(this).is(":checked") ? 1 : 0;
                let statusLabel = $(this).closest("td").find(".status-label");

                $.ajax({
                    url: `{{ url('admin/update-district-status') }}/${districtId}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: newStatus
                    },
                    success: function(response) {
                        statusLabel.text(newStatus == 1 ? "Active" : "Inactive");
                        showToast(response.message, "success");
                    },
                    error: function() {
                        showToast("Failed to update status", "danger");
                    }
                });
            });

            // Delete District
            $(document).on("click", ".deleteDistrict", function() {
                let districtId = $(this).data("id");
                if (confirm("Are you sure you want to delete this district?")) {
                    $.ajax({
                        url: `{{ url('admin/delete-district') }}/${districtId}`,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                $(`#districtRow-${districtId}`).remove();
                                showToast("District deleted successfully", "success");
                            } else {
                                showToast("Failed to delete district", "danger");
                            }
                        },
                        error: function() {
                            showToast("Something went wrong", "danger");
                        }
                    });
                }
            });

            // Function to Show Toast
            function showToast(message, type = 'success') {
                let toast = $('#toastMessage');
                toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
                toast.find('.toast-body').text(message);
                let bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }

            // Handle AJAX Errors
            function handleAjaxError(xhr) {
                if (xhr.status === 422) {
                    let errors = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                    showToast(errors, 'danger');
                } else {
                    showToast("Something went wrong!", 'danger');
                }
            }
        });
    </script>
@stop
