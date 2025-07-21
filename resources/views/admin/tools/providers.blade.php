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
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="providerForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="userenname" class="form-label">Select Service <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="service_id">
                                        <option value="">Select Service</option>
                                        @foreach ($service as $row)
                                            <option value="{{ $row->id }}">{{ $row->service_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="providerName" class="form-label">Provider Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="provider_name"
                                        placeholder="Enter Provider Name" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="providerCode" class="form-label">Provider Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="provider_code"
                                        placeholder="Enter Provider Code" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="providerLogo" class="form-label">Provider Logo </label>
                                    <input type="file" class="form-control" name="logo"
                                        placeholder="Enter Provider Code">
                                    <span class="text-danger">60*60px max=100KB</span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button class="btn btn-success w-100" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end col-->
            </div>
            <div class="col-lg-8">
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
                                        <th scope="col">Provider Name</th>
                                        <th scope="col">Provider Code</th>
                                        <th scope="col">Logo</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="providersTableBody">
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

    <!-- Edit Provider Modal -->
    <div class="modal fade" id="editProviderModal" tabindex="-1" aria-labelledby="editProviderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProviderModalLabel">Edit Provider</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProviderForm">
                        <input type="hidden" id="editProviderId">
                        <div class="mb-3">
                            <label for="editProviderService" class="form-label">Service</label>
                            <select class="form-control" id="editProviderService" required>
                                <!-- Services will be loaded here dynamically -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editProviderName" class="form-label">Provider Name</label>
                            <input type="text" class="form-control" id="editProviderName" required>
                        </div>

                        <div class="mb-3">
                            <label for="editProviderCode" class="form-label">Provider Code</label>
                            <input type="text" class="form-control" id="editProviderCode" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProviderLogo" class="form-label">Provider Logo</label>
                            <input type="file" class="form-control" id="editProviderLogo" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Provider</button>
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
        $(document).ready(function() {

            fetchProviders();

            // $("#providerForm").on("submit", function(e) {
            //     e.preventDefault();

            //     $.ajax({
            //         url: "{{ url('admin/add-provider') }}",
            //         type: "POST",
            //         data: $(this).serialize(),
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             if (response.status === true) {
            //                 showToast(response.message, 'success');
            //                 $("#providerForm")[0].reset();
            //                 fetchProviders();
            //                 $("#providerModal").modal("hide");
            //             } else {
            //                 showToast("Failed to add Provider!", 'danger');
            //             }
            //         },
            //         error: function(xhr) {
            //             if (xhr.status === 422) {
            //                 let errors = xhr.responseJSON.errors;
            //                 let errorMessages = errors.join("<br>");
            //                 showToast(errorMessages, 'danger');
            //             } else {
            //                 console.log(xhr.responseText);
            //                 showToast("Something went wrong!", 'danger');
            //             }
            //         }
            //     });
            // });

            $("#providerForm").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ url('admin/add-provider') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status) {
                            showToast(response.message, "success");
                            $("#providerForm")[0].reset();
                            fetchProviders();
                        } else {
                            showToast("Failed to add provider!", "danger");
                        }
                    }
                });
            });

            function fetchProviders() {
                $.ajax({
                    url: "{{ url('admin/get-providers') }}",
                    type: "GET",
                    success: function(providers) {
                        let providersTable = $('#providersTableBody');
                        providersTable.empty(); // Clear table body

                        if (providers.length > 0) {
                            providers.forEach((provider, index) => {
                                providersTable.append(`
                            <tr id="providerRow-${provider.id}">
                                <td>${index + 1}</td>
                                <td>${provider.service.service_name}</td>
                                <td>${provider.provider_name}</td>
                                <td>${provider.provider_code}</td>
                                <td>
                                    <img src="${window.location.origin}/${provider.logo}" 
                                        alt="Provider Logo" class="img-thumbnail" width="50" height="50">
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggleStatus" type="checkbox"
                                            data-id="${provider.id}" ${provider.status == 1 ? 'checked' : ''}>
                                        <label class="form-check-label status-label">
                                            ${provider.status == 1 ? 'Active' : 'Inactive'}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning editProvider" 
                                    data-id="${provider.id}" data-name="${provider.provider_name}" 
                                    data-code="${provider.provider_code}" data-service="${provider.service.service_name}">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteProvider" data-id="${provider.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                            });
                        } else {
                            providerTable.append(
                                '<tr><td colspan="4" class="text-center">No providers found.</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        showToast("Failed to load providers", 'danger');
                    }
                });
            }

            // // Show Edit Modal with Data
            // $(document).on("click", ".editProvider", function() {
            //     let providerId = $(this).data("id");
            //     let providerName = $(this).data("name");
            //     let providerCode = $(this).data("code");
            //     let providerService = $(this).data("service");

            //     $("#editProviderId").val(providerId);
            //     $("#editProviderName").val(providerName);
            //     $("#editProviderCode").val(providerCode);

            //     // Fetch services dynamically and preselect the provider's service
            //     $.ajax({
            //         url: "{{ url('admin/get-services') }}",
            //         type: "GET",
            //         success: function(services) {
            //             let serviceDropdown = $("#editProviderService");
            //             serviceDropdown.empty();

            //             services.forEach(service => {
            //                 let selected = service.service_name === providerService ?
            //                     "selected" : "";
            //                 serviceDropdown.append(
            //                     `<option value="${service.id}" ${selected}>${service.service_name}</option>`
            //                 );
            //             });

            //             $("#editProviderModal").modal("show");
            //         },
            //         error: function() {
            //             showToast("Failed to load services", "danger");
            //         }
            //     });
            // });


            // $("#editProviderForm").submit(function(e) {
            //     e.preventDefault();

            //     let providerId = $("#editProviderId").val();
            //     let providerName = $("#editProviderName").val();
            //     let providerCode = $("#editProviderCode").val();
            //     let providerService = $("#editProviderService").val();
            //     let providerLogo = $("#editProviderLogo")[0].files[0];

            //     $.ajax({
            //         url: "{{ url('admin/update-provider') }}/" + providerId,
            //         type: "POST",
            //         data: {
            //             _token: "{{ csrf_token() }}",
            //             provider_name: providerName,
            //             provider_code: providerCode,
            //             service_id: providerService
            //         },
            //         success: function(response) {
            //             $("#editProviderModal").modal("hide");
            //             showToast("Provider updated successfully", "success");
            //             fetchProviders(); // Reload providers
            //         },
            //         error: function() {
            //             showToast("Failed to update provider", "danger");
            //         }
            //     });
            // });

            // Show Edit Modal with Data
            $(document).on("click", ".editProvider", function() {
                let providerId = $(this).data("id");
                let providerName = $(this).data("name");
                let providerCode = $(this).data("code");
                let providerService = $(this).data("service");

                $("#editProviderId").val(providerId);
                $("#editProviderName").val(providerName);
                $("#editProviderCode").val(providerCode);

                // Fetch services dynamically and preselect the provider's service
                $.ajax({
                    url: "{{ url('admin/get-services') }}",
                    type: "GET",
                    success: function(services) {
                        let serviceDropdown = $("#editProviderService");
                        serviceDropdown.empty();

                        services.forEach(service => {
                            let selected = service.service_name === providerService ?
                                "selected" : "";
                            serviceDropdown.append(
                                `<option value="${service.id}" ${selected}>${service.service_name}</option>`
                            );
                        });

                        $("#editProviderModal").modal("show");
                    },
                    error: function() {
                        showToast("Failed to load services", "danger");
                    }
                });
            });

            // Submit Edit Form
            $("#editProviderForm").submit(function(e) {
                e.preventDefault();

                let providerId = $("#editProviderId").val();
                let providerName = $("#editProviderName").val();
                let providerCode = $("#editProviderCode").val();
                let providerService = $("#editProviderService").val();
                let providerLogo = $("#editProviderLogo")[0].files[0];

                let formData = new FormData();
                formData.append("_token", "{{ csrf_token() }}");
                formData.append("provider_name", providerName);
                formData.append("provider_code", providerCode);
                formData.append("service_id", providerService);

                // Append Logo if Selected
                if (providerLogo) {
                    formData.append("logo", providerLogo);
                }

                $.ajax({
                    url: "{{ url('admin/update-provider') }}/" + providerId,
                    type: "POST",
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    success: function(response) {
                        $("#editProviderModal").modal("hide");
                        showToast("Provider updated successfully", "success");
                        fetchProviders(); // Reload providers
                    },
                    error: function() {
                        showToast("Failed to update provider", "danger");
                    }
                });
            });



            $(document).on("change", ".toggleStatus", function() {
                let providerId = $(this).data("id");
                let newStatus = $(this).is(":checked") ? 1 : 0; // Get new status
                let statusLabel = $(this).closest("td").find(".status-label");

                $.ajax({
                    url: "{{ url('admin/update-provider-status') }}/" + providerId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: newStatus
                    },
                    success: function(response) {
                        // Update status text dynamically without refresh
                        if (newStatus == 1) {
                            statusLabel.text("Active");
                        } else {
                            statusLabel.text("Inactive");
                        }
                        showToast(response.message, "success");
                    },
                    error: function() {
                        showToast("Failed to update status", "danger");
                    }
                });
            });

            // Delete function

            $(document).on("click", ".deleteProvider", function() {
                let providerId = $(this).data("id");

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
                            url: "{{ url('admin/delete-provider') }}/" + providerId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    $("#providerRow-" + providerId)
                                        .remove(); // Remove row from table
                                    Swal.fire("Deleted!",
                                        "The provider has been removed successfully.",
                                        "success");
                                } else {
                                    Swal.fire("Error!",
                                        "Failed to delete the provider.", "error");
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
