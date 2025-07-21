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
                        <form id="languageForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="languageName" class="form-label">Language Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="Enter Language Name" required>
                                </div>
                                <div class="col-lg-12 col-md-6 mb-3">
                                    <label for="languageCode" class="form-label">Language Code</label>
                                    <input type="text" class="form-control" name="code"
                                        placeholder="Enter language Code">
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
                                        <th scope="col">Language Name</th>
                                        <th scope="col">Language Code</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="languagesTableBody">
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

    <!-- Edit Language Modal -->
    <div class="modal fade" id="editLanguageModal" tabindex="-1" aria-labelledby="editLanguageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLanguageModalLabel">Edit Language</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLanguageForm">
                        <input type="hidden" id="editLanguageId">
                        <div class="mb-3">
                            <label for="editLanguageName" class="form-label">Language Name</label>
                            <input type="text" class="form-control" id="editLanguageName" required>
                        </div>

                        <div class="mb-3">
                            <label for="editLanguageCode" class="form-label">Language Code</label>
                            <input type="text" class="form-control" id="editLanguageCode" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Language</button>
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

            fetchLanguages();

            $("#languageForm").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ url('admin/add-language') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === true) {
                            showToast(response.message, 'success');
                            $("#languageForm")[0].reset();
                            fetchLanguages();
                            $("#languageModal").modal("hide");
                        } else {
                            showToast("Failed to add Language!", 'danger');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = errors.join("<br>");
                            showToast(errorMessages, 'danger');
                        } else {
                            console.log(xhr.responseText);
                            showToast("Something went wrong!", 'danger');
                        }
                    }
                });
            });

            function fetchLanguages() {
                $.ajax({
                    url: "{{ url('admin/get-languages') }}",
                    type: "GET",
                    success: function(languages) {
                        let languagesTable = $('#languagesTableBody');
                        languagesTable.empty(); // Clear table body

                        if (languages.length > 0) {
                            languages.forEach((language, index) => {
                                languagesTable.append(`
                            <tr id="languageRow-${language.id}">
                                <td>${index + 1}</td>
                                <td>${language.name}</td>
                                <td>${language.code}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggleStatus" type="checkbox"
                                            data-id="${language.id}" ${language.status == 1 ? 'checked' : ''}>
                                        <label class="form-check-label status-label">
                                            ${language.status == 1 ? 'Active' : 'Inactive'}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning editLanguage" 
                                    data-id="${language.id}" data-name="${language.name}" 
                                    data-code="${language.code}">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteLanguage" data-id="${language.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                            });
                        } else {
                            languageTable.append(
                                '<tr><td colspan="4" class="text-center">No languages found.</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        showToast("Failed to load languages", 'danger');
                    }
                });
            }

            // Show Edit Modal with Data
            $(document).on("click", ".editLanguage", function() {
                let languageId = $(this).data("id");
                let languageName = $(this).data("name");
                let languageCode = $(this).data("code");

                $("#editLanguageId").val(languageId);
                $("#editLanguageName").val(languageName);
                $("#editLanguageCode").val(languageCode);
                $("#editLanguageModal").modal("show");
            });


            $("#editLanguageForm").submit(function(e) {
                e.preventDefault();

                let languageId = $("#editLanguageId").val();
                let languageName = $("#editLanguageName").val();
                let languageCode = $("#editLanguageCode").val();
                

                $.ajax({
                    url: "{{ url('admin/update-language') }}/" + languageId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: languageName,
                        code: languageCode,
                        
                    },
                    success: function(response) {
                        $("#editLanguageModal").modal("hide");
                        showToast("Language updated successfully", "success");
                        fetchLanguages(); // Reload Languages
                    },
                    error: function() {
                        showToast("Failed to update Language", "danger");
                    }
                });
            });


            $(document).on("change", ".toggleStatus", function() {
                let languageId = $(this).data("id");
                let newStatus = $(this).is(":checked") ? 1 : 0; // Get new status
                let statusLabel = $(this).closest("td").find(".status-label");

                $.ajax({
                    url: "{{ url('admin/update-language-status') }}/" + languageId,
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

            $(document).on("click", ".deleteLanguage", function() {
                let languageId = $(this).data("id");

                Swal.fire({
                    html: `
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" 
                            trigger="loop" colors="primary:#f7b84b,secondary:#f06548" 
                            style="width:100px;height:100px">
                        </lord-icon>
                        <p><b>Are you Sure ?</b></p>
                        <p style="font-size:15px;">Are you sure you want to remove</br/> this language?</p>
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
                            url: "{{ url('admin/delete-language') }}/" + languageId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    $("#languageRow-" + languageId)
                                        .remove(); // Remove row from table
                                    Swal.fire("Deleted!",
                                        "The language has been removed successfully.",
                                        "success");
                                } else {
                                    Swal.fire("Error!",
                                        "Failed to delete the language.", "error");
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
