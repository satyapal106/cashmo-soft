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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Recharge Plans</a></li>
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
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="retailerForm">
                            @csrf
                            <div class="row">
                                <input type="hidden" class="form-control" name="service_id" value="1" required>
                                <div class="col-lg-12 mb-3">
                                    <label for="userenname" class="form-label">Select Provider <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="provider_id">
                                        <option value="">Select Provider</option>
                                        @foreach ($filteredProviders as $provider)
                                            <option value="{{ $provider['id'] }}">{{ $provider['provider_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="useremail" class="form-label">Plan Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter Plan Category" required>
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
                                        <th scope="col">Provider</th>
                                        <th scope="col">Plan Name</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="PlancategoryTableBody">
                                    <!-- Data will be loaded dynamically -->
                                </tbody>
                            </table>

                            <div id="paginationControls" class="mt-3 text-center"></div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>
            <!--end row-->
        </div>
    </div>

    <!-- Edit Plan Category Modal -->
    <div class="modal fade" id="editPlanCategoryModal" tabindex="-1" aria-labelledby="editPlanCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPlanCategoryModalLabel">Edit Plan Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updatePlanCategoryForm">
                        <input type="hidden" id="editPlanCategoryId" name="id">

                        <div class="mb-3">
                            <label for="editProviderId" class="form-label">Provider</label>
                            <select id="editProviderId" name="provider_id" class="form-control" required>
                                <option value="">Select Provider</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editPlanCategoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="editPlanCategoryName" name="name" required>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
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

            fetchPlancategory();

            $("#retailerForm").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('admin/add-plan-category') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === true) {
                            showToast(response.message, 'success'); // Success Toast
                            $("#retailerForm")[0].reset();
                            fetchPlancategory();
                        } else {
                            showToast("Failed to add category!", 'danger');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        if (xhr.status === 400) {
                            let errors = xhr.responseJSON.message; // Validation errors
                            let errorMsg = errors.join(
                                "<br>"); // Convert array to string with line breaks
                            showToast(errorMsg, 'danger'); // Show validation error messages
                        } else {
                            showToast("Something went wrong!", 'danger'); // Other Errors
                        }
                    }
                });
            });


            let currentPage = 1; // Global variable to track page

            function fetchPlancategory(page = 1) {
                $.ajax({
                    url: `{{ url('admin/get-plan-category') }}?page=${page}`,
                    type: "GET",
                    success: function(planCategory) {
                        let PlanCategoryTable = $('#PlancategoryTableBody');
                        let paginationControls = $('#paginationControls');
                        paginationControls.empty();
                        PlanCategoryTable.empty();

                        if (planCategory.data.length > 0) {
                            planCategory.data.forEach((category, index) => {
                                PlanCategoryTable.append(`
                        <tr id="categoryRow-${category.id}">
                            <td>${(page - 1) * 10 + (index + 1)}</td>
                            <td>${category.provider ? category.provider.provider_name : 'N/A'}</td>
                            <td>${category.name}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggleStatus" type="checkbox"
                                        data-id="${category.id}" ${category.status == 1 ? 'checked' : ''}>
                                    <label class="form-check-label status-label">
                                        ${category.status == 1 ? 'Active' : 'Inactive'}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning editPlanCategory" data-id="${category.id}"
                                 data-name="${category.name}" data-provider-id="${category.provider ? category.provider.id : ''}">Edit</button>
                                <button class="btn btn-sm btn-danger deletePlanCategory" data-id="${category.id}">Delete</button>
                            </td>
                        </tr>
                    `);
                            });

                            // Update global `currentPage`
                            currentPage = planCategory.current_page;

                            // Generate pagination controls
                            let prevDisabled = planCategory.prev_page_url ? "" : "disabled";
                            let nextDisabled = planCategory.next_page_url ? "" : "disabled";

                            paginationControls.append(`
                    <button class="btn btn-sm btn-primary prevPage" ${prevDisabled}>Previous</button>
                    <span class="mx-2">Page ${planCategory.current_page} of ${planCategory.last_page}</span>
                    <button class="btn btn-sm btn-primary nextPage" ${nextDisabled}>Next</button>
                `);

                            // Add event listeners to buttons
                            $(".prevPage").click(() => {
                                if (planCategory.prev_page_url) {
                                    fetchPlancategory(currentPage - 1);
                                }
                            });

                            $(".nextPage").click(() => {
                                if (planCategory.next_page_url) {
                                    fetchPlancategory(currentPage + 1);
                                }
                            });

                        } else {
                            PlanCategoryTable.append(
                                '<tr><td colspan="5" class="text-center">No categories found.</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        showToast("Failed to load categories", 'danger');
                    }
                });
            }

            $(document).on("click", ".editPlanCategory", function() {
                let id = $(this).data("id");
                let name = $(this).data("name");
                let provider_id = $(this).data("provider-id");

                $("#editPlanCategoryId").val(id);
                $("#editPlanCategoryName").val(name);

                // Fetch Provider List
                $.ajax({
                    url: "{{ url('admin/get-providers') }}",
                    type: "GET",
                    success: function(providers) {
                        let providerDropdown = $("#editProviderId");
                        providerDropdown.empty();

                        providers.forEach(provider => {
                            let selected = provider.id == provider_id ? "selected" : "";
                            providerDropdown.append(
                                `<option value="${provider.id}" ${selected}>${provider.provider_name}</option>`
                            );
                        });

                        // Set the selected value after options are appended
                        $("#editProviderId").val(provider_id);
                    },
                    error: function() {
                        showToast("Failed to load providers", "danger");
                    }
                });

                $("#editPlanCategoryModal").modal("show");
            });

            // Handle Update Form Submission
            $("#updatePlanCategoryForm").on("submit", function(e) {
                e.preventDefault();

                let id = $("#editPlanCategoryId").val();
                let formData = $(this).serialize();

                $.ajax({
                    url: `{{ url('admin/update-plan-category') }}/${id}`,
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === true) {
                            showToast(response.message, 'success');
                            $("#editPlanCategoryModal").modal("hide");
                            fetchPlancategory();
                        } else {
                            showToast("Failed to update category!", 'danger');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        if (xhr.status === 400) {
                            let errors = xhr.responseJSON.message;
                            let errorMsg = errors.join("<br>");
                            showToast(errorMsg, 'danger');
                        } else {
                            showToast("Something went wrong!", 'danger');
                        }
                    }
                });
            });

            $(document).on("change", ".toggleStatus", function () {
                let checkbox = $(this);
                let serviceId = checkbox.data("id");
                let newStatus = checkbox.prop("checked") ? 1 : 0;
                let statusLabel = checkbox.closest('.form-check').find('.status-label');

                $.ajax({
                    url: "{{ url('admin/update-plan-category-status') }}",
                    type: "POST",
                    data: {
                        id: serviceId,
                        status: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            statusLabel.text(newStatus == 1 ? "Active" : "Inactive");
                            showToast("Category status updated successfully", 'success');
                        } else {
                            checkbox.prop("checked", !newStatus); 
                            showToast("Failed to update status", 'danger');
                        }
                    },
                    error: function () {
                        checkbox.prop("checked", !newStatus); 
                        showToast("Error updating status", 'danger');
                    }
                });
            });


            $(document).on("click", ".deletePlanCategory", function() {
                let categoryId = $(this).data("id");

                Swal.fire({
                    html: `
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                            trigger="loop" colors="primary:#f7b84b,secondary:#f06548" 
                            style="width:100px;height:100px">
                        </lord-icon>
                        <p><b>Are you Sure ?</b></p>
                        <p style="font-size:15px;">Are you sure you want to remove</br/> this Plan Category?</p>
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
                            url: "{{ url('admin/delete-plan-category') }}/" + categoryId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status) {
                                    $("#categoryRow-" + categoryId)
                                        .remove(); // Remove row from table
                                    Swal.fire("Deleted!",
                                        "The plan Category has been removed successfully.",
                                        "success");
                                } else {
                                    Swal.fire("Error!",
                                        "Failed to delete the Plan Category.", "error");
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
