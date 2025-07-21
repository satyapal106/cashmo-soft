@extends('admin.layouts.master')

@section('styles')
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Agent Type</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Agent Type Form -->
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Add {{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="agentTypeForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Agent Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Agent Type" required>
                                <span id="nameError" class="text-danger"></span>
                            </div>
                            <button class="btn btn-success w-100" id="btnSubmit" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Agent Type List -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">All {{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Agent Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="agentTypeTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
        <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editAgentTypeModal" tabindex="-1" aria-labelledby="editAgentTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editAgentTypeForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Agent Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="agentTypeId">
                        <div class="mb-3">
                            <label class="form-label">Agent Type</label>
                            <input type="text" class="form-control" id="editAgentTypeName" required>
                            <span id="editAgentTypeError" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnUpdate" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            fetchAgentTypes();

            function showToast(message, type = 'success') {
                let toast = new bootstrap.Toast($('#toastMessage'));
                $('#toastMessage .toast-body').text(message);
                $('#toastMessage').removeClass().addClass(
                    `toast align-items-center text-white bg-${type} border-0`);
                toast.show();
            }

            $('#agentTypeForm').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $('#btnSubmit').prop('disabled', true).text('Submitting...');

                $.post("{{ url('admin/add-agent-type') }}", formData)
                    .done(response => {
                        if (response.status) {
                            showToast(response.message);
                            $('#agentTypeForm')[0].reset();
                            $('#nameError').text('');
                            fetchAgentTypes();
                        }
                    })
                    .fail(xhr => {
                        let response = xhr.responseJSON;
                        if (response?.errors?.name) {
                            $('#nameError').text(response.errors.name[0]);
                            showToast(response.errors.name[0], 'danger');
                        } else {
                            showToast("Something went wrong!", 'danger');
                        }
                    })
                    .always(() => $('#btnSubmit').prop('disabled', false).text('Submit'));
            });

            function fetchAgentTypes() {
                $.get("{{ url('admin/get-agent-type') }}", function(data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach((type, index) => {
                            html += `
                        <tr id="agentTypeRow-${type.id}">
                            <td>${index + 1}</td>
                            <td>${type.name}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggleStatus" type="checkbox" data-id="${type.id}" ${type.status == 1 ? 'checked' : ''}>
                                    <label class="form-check-label status-label">${type.status == 1 ? 'Active' : 'Inactive'}</label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm editAgentType" data-id="${type.id}" data-name="${type.name}">Edit</button>
                                <button class="btn btn-danger btn-sm deleteAgentType" data-id="${type.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center">No Agent Types Found.</td></tr>';
                    }
                    $('#agentTypeTableBody').html(html);
                });
            }

            $(document).on('click', '.editAgentType', function() {
                $('#agentTypeId').val($(this).data('id'));
                $('#editAgentTypeName').val($(this).data('name'));
                $('#editAgentTypeModal').modal('show');
            });

            $('#editAgentTypeForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#agentTypeId').val();
                let name = $('#editAgentTypeName').val();
                $('#btnUpdate').prop('disabled', true).text('Updating...');

                $.post(`{{ url('admin/update-agent-type') }}/${id}`, {
                        _token: "{{ csrf_token() }}",
                        name: name
                    })
                    .done(response => {
                        if (response.status) {
                            showToast(response.message);
                            $('#editAgentTypeModal').modal('hide');
                            fetchAgentTypes();
                        }
                    })
                    .fail(xhr => {
                        let response = xhr.responseJSON;
                        if (response?.errors?.name) {
                            $('#editAgentTypeError').text(response.errors.name[0]);
                            showToast(response.errors.name[0], 'danger');
                        } else {
                            showToast("Something went wrong!", 'danger');
                        }
                    })
                    .always(() => $('#btnUpdate').prop('disabled', false).text('Update'));
            });

            $(document).on("change", ".toggleStatus", function() {
                let checkbox = $(this);
                let id = checkbox.data("id");
                let newStatus = checkbox.prop("checked") ? 1 : 0;

                $.post("{{ url('admin/update-agent-type-status') }}", {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: newStatus
                    })
                    .done(response => {
                        if (response.success) {
                            checkbox.closest('.form-check').find('.status-label').text(newStatus ?
                                'Active' : 'Inactive');
                            showToast("Status updated successfully");
                        } else {
                            checkbox.prop("checked", !newStatus);
                            showToast("Status update failed", 'danger');
                        }
                    })
                    .fail(() => {
                        checkbox.prop("checked", !newStatus);
                        showToast("Something went wrong!", 'danger');
                    });
            });

            $(document).on("click", ".deleteAgentType", function() {
                let agentTypeId = $(this).data("id");

                Swal.fire({
                    html: `
            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json"
                trigger="loop"
                colors="primary:#f7b84b,secondary:#f06548"
                style="width:100px;height:100px">
            </lord-icon>
            <p><b>Are you Sure?</b></p>
            <p style="font-size:15px;">Are you sure you want to remove<br/> this agent type?</p>
        `,
                    showCancelButton: true,
                    confirmButtonColor: "#405189",
                    cancelButtonColor: "#f06548",
                    confirmButtonText: "Yes, Delete",
                    cancelButtonText: "Cancel",
                    customClass: {
                        confirmButton: 'btn btn-primary btn-sm',
                        cancelButton: 'btn btn-danger btn-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/delete-agent-type') }}/" + agentTypeId,
                            type: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status) {
                                    $("#agentTypeRow-" + agentTypeId).remove();
                                    Swal.fire({
                                        icon: "success",
                                        title: "Deleted!",
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Error!",
                                        text: "Failed to delete the agent type.",
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error!",
                                    text: "Something went wrong. Please try again.",
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
