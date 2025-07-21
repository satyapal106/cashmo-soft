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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Services</a></li>
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
                        <form id="serviceForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="serviceName" class="form-label">Service Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="service_name"
                                        placeholder="Service Name" required>
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
                                        <th scope="col">Service Name</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="serviceTableBody">
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

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editServiceForm">
                        <input type="hidden" id="serviceId"> <!-- Hidden field for service ID -->

                        <div class="mb-3">
                            <label for="editServiceName" class="form-label">Service Name</label>
                            <input type="text" class="form-control" id="editServiceName" required>
                            <span id="editServiceError" class="text-danger"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
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
        $(document).ready(function() {

            fetchservices();

            $('#serviceForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let submitButton = $('#btnSubmit');

                submitButton.prop('disabled', true).text('Submitting...');

                $.ajax({
                    url: "{{ url('admin/add-service') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.status) {
                            showToast(response.message, 'success');
                            $('#serviceForm')[0].reset(); // Reset form
                            $('#nameError').text(''); // Clear previous error
                            fetchservices();
                        }
                    },
                    error: function(xhr) {
                        let response = xhr.responseJSON;
                        if (response?.errors) {
                            if (response.errors.service_name) {
                                $('#nameError').text(response.errors.service_name[
                                0]); // Show error message
                                showToast(response.errors.service_name[0],
                                'danger'); // Show error toast
                            }
                        } else {
                            showToast("Something went wrong!", 'danger');
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Submit');
                    }
                });
            });

            function fetchservices() {
                $.ajax({
                    url: "{{ url('admin/get-services') }}",
                    type: "GET",
                    success: function(services) {
                        let serviceTable = $('#serviceTableBody');
                        serviceTable.empty(); // Clear table body

                        if (services.length > 0) {
                            services.forEach((service, index) => {
                                serviceTable.append(`
                                <tr id="serviceRow-${service.id}">
                                    <td>${index + 1}</td>
                                    <td>${service.service_name}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggleStatus" type="checkbox"
                                                data-id="${service.id}" ${service.status == 1 ? 'checked' : ''}>
                                            <label class="form-check-label status-label">
                                                ${service.status == 1 ? 'Active' : 'Inactive'}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="api-provider-mapping/${service.id}" class="btn btn-sm btn-primary">Update Provider Id</a>
                                        <button class="btn btn-sm btn-warning editservice" data-id="${service.id}" data-name="${service.service_name}">Edit</button>
                                        <button class="btn btn-sm btn-danger deleteService" data-id="${service.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                            });
                        } else {
                            serviceTable.append(
                                '<tr><td colspan="4" class="text-center">No services found.</td></tr>'
                                );
                        }
                    },
                    error: function() {
                        showToast("Failed to load services", 'danger');
                    }
                });
            }


            $(document).on('click', '.editservice', function() {
                let serviceId = $(this).data('id');
                let serviceName = $(this).data('name');

                $('#editServiceModal #serviceId').val(serviceId);
                $('#editServiceModal #editServiceName').val(serviceName);
                $('#editServiceModal').modal('show');
            });

            $('#editServiceForm').on('submit', function(e) {
                e.preventDefault();
                let serviceId = $('#serviceId').val();
                let serviceName = $('#editServiceName').val();
                let status = $('#editServiceStatus').val();

                $.ajax({
                    url: `{{ url('admin/update-service') }}/${serviceId}`,
                    type: "POST",
                    data: {
                        service_name: serviceName,
                        status: status,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status) {
                            showToast(response.message, 'success');
                            $('#editServiceModal').modal('hide');
                            $('#editServiceError').text(''); // Clear validation error
                            fetchservices();
                        }
                    },
                    error: function(xhr) {
                        let response = xhr.responseJSON;
                        if (response?.errors) {
                            if (response.errors.service_name) {
                                $('#editServiceError').text(response.errors.service_name[
                                0]); // Show error message
                                showToast(response.errors.service_name[0],
                                'danger'); // Show error toast
                            }
                            if (response.errors.status) {
                                showToast(response.errors.status[0], 'danger');
                            }
                        } else if (response?.message) {
                            showToast(response.message, 'danger');
                        } else {
                            showToast("Something went wrong!", 'danger');
                        }
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text('Update');
                    }
                });
            });

            $(document).on("change", ".toggleStatus", function() {
                let checkbox = $(this);
                let serviceId = checkbox.data("id");
                let newStatus = checkbox.prop("checked") ? 1 : 0;
                let statusLabel = checkbox.closest('.form-check').find('.status-label');

                $.ajax({
                    url: "{{ url('admin/update-service-status') }}",
                    type: "POST",
                    data: {
                        id: serviceId,
                        status: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            statusLabel.text(newStatus == 1 ? "Active" : "Inactive");
                            showToast("Service status updated successfully", 'success');
                        } else {
                            checkbox.prop("checked", !
                            newStatus); // Revert checkbox state if update fails
                            showToast("Failed to update status", 'danger');
                        }
                    },
                    error: function() {
                        checkbox.prop("checked", !
                        newStatus); // Revert checkbox state if error occurs
                        showToast("Error updating status", 'danger');
                    }
                });
            });


            $(document).on("click", ".deleteService", function() {
                let serviceId = $(this).data("id");

                Swal.fire({
                    html: `
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                            trigger="loop" colors="primary:#f7b84b,secondary:#f06548" 
                            style="width:100px;height:100px">
                        </lord-icon>
                        <p><b>Are you Sure ?</b></p>
                        <p style="font-size:15px;">Are you sure you want to remove</br/> this Service?</p>
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
                            url: "{{ url('admin/delete-service') }}/" + serviceId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status) {
                                    $("#serviceRow-" + serviceId)
                                        .remove(); // Remove row from table
                                    Swal.fire("Deleted!",
                                        "The service has been removed successfully.",
                                        "success");
                                } else {
                                    Swal.fire("Error!",
                                        "Failed to delete the service.", "error");
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
