@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Recharge Plans</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Add {{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="circleForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="circleName" class="form-label">State Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="State Name"
                                        required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="circleName" class="form-label">State Code</label>
                                    <input type="text" class="form-control" name="code" placeholder="State Code"
                                        required>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button class="btn btn-success w-100" id="btnSubmit" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->

            <div class="col-lg-7">
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
                                        <th scope="col">State Code</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="circleTableBody">
                                    <!-- Data will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
        </div>
        <!-- end row -->
    </div>

    <!-- Edit District Modal -->
    <div class="modal fade" id="editStateModal" tabindex="-1" aria-labelledby="editStateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStateModalLabel">Edit State</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStateForm">
                        <input type="hidden" id="editStateId">
                        <div class="mb-3">
                            <label for="editStateName" class="form-label">State Name</label>
                            <input type="text" class="form-control" id="editStateName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStateCode" class="form-label">State Code</label>
                            <input type="text" class="form-control" id="editStateCode">
                        </div>
                        <button type="submit" class="btn btn-primary">Update State</button>
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
    <script>
        $(document).ready(function() {

            fetchcircles();

            $('#circleForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let submitButton = $('#btnSubmit');

                submitButton.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: "{{ url('admin/add-state') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        showToast(response.message, 'success');
                        $('#circleForm')[0].reset(); // Reset form
                        $('#nameError').text('');
                        fetchcircles();
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors?.name) {
                            $('#nameError').text(errors.name[0]);
                            showToast(errors.name[0], 'danger'); // Show error toast
                        } else {
                            showToast("Something went wrong!", 'danger');
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Submit');
                    }
                });
            });

            function fetchcircles() {
                $.ajax({
                    url: "{{ url('admin/get-states') }}",
                    type: "GET",
                    success: function(circles) {
                        let circleTable = $('#circleTableBody');
                        circleTable.empty(); // Clear table body

                        if (circles.length > 0) {
                            circles.forEach((circle, index) => {
                                circleTable.append(`
                                <tr id="stateRow-${circle.id}">
                                    <td>${index + 1}</td>
                                    <td>${circle.name}</td>
                                    <td>${circle.code ? circle.code : 'NA'}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggleStatus" type="checkbox"
                                                data-id="${circle.id}" ${circle.status == 1 ? 'checked' : ''}>
                                            <label class="form-check-label status-label">
                                                ${circle.status == 1 ? 'Active' : 'Inactive'}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning editcircle" data-id="${circle.id}" data-name="${circle.name}"
                                        data-code="${circle.code}">Edit</button>
                                        <button class="btn btn-sm btn-danger deleteState" data-id="${circle.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                            });
                        } else {
                            circleTable.append(
                                '<tr><td colspan="4" class="text-center">No circles found.</td></tr>'
                                );
                        }
                    },
                    error: function() {
                        showToast("Failed to load circles", 'danger');
                    }
                });
            }

            // Edit District Modal
            $(document).on("click", ".editcircle", function() {
                let stateId = $(this).data("id");
                let stateName = $(this).data("name");
                let stateCode = $(this).data("code");

                $("#editStateId").val(stateId);
                $("#editStateName").val(stateName);
                $("#editStateCode").val(stateCode);

                $("#editStateModal").modal("show");
            });

            $("#editStateForm").submit(function(e) {
                e.preventDefault();
                let stateId = $("#editStateId").val();
                let stateName = $("#editStateName").val();
                let stateCode = $("#editStateCode").val();

                $.ajax({
                    url: `{{ url('admin/update-state') }}/${stateId}`,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: stateName,
                        code: stateCode
                    },
                    success: function(response) {
                        $("#editStateModal").modal("hide");
                        showToast("State updated successfully", "success");
                        fetchcircles();
                    },
                    error: function(xhr) {
                        handleAjaxError(xhr);
                    }
                });
            });

            $(document).on("change", ".toggleStatus", function() {
                let stateId = $(this).data("id");
                let newStatus = $(this).is(":checked") ? 1 : 0;
                let statusLabel = $(this).closest("td").find(".status-label");

                $.ajax({
                    url: `{{ url('admin/update-state-status') }}/${stateId}`,
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

            $(document).on("click", ".deleteState", function() {
                let stateId = $(this).data("id");

                Swal.fire({
                    html: `
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                            trigger="loop" colors="primary:#f7b84b,secondary:#f06548" 
                            style="width:100px;height:100px">
                        </lord-icon>
                        <p><b>Are you Sure ?</b></p>
                        <p style="font-size:15px;">Are you sure you want to remove</br/> this State?</p>
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
                            url: "{{ url('admin/delete-state') }}/" + stateId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status) {
                                    $("#stateRow-" + stateId)
                                        .remove(); // Remove row from table
                                    Swal.fire("Deleted!",
                                        "The State has been removed successfully.",
                                        "success");
                                } else {
                                    Swal.fire("Error!",
                                        "Failed to delete the State.", "error");
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

            // Function to Show Toast
            function showToast(message, type = 'success') {
                let toast = $('#toastMessage');
                toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
                toast.find('.toast-body').text(message);
                let bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }
        });
    </script>
@stop
