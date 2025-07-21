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
                    <form id="operatorForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="operatorName" class="form-label">Operator Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Operator Name" required>
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
                                    <th scope="col">Operator</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="operatorTableBody">
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
    <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script>
    $(document).ready(function () {
        
        fetchOperators();
        
        $('#operatorForm').on('submit', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let submitButton = $('#btnSubmit');

            submitButton.prop('disabled', true).text('Submitting...');

            $.ajax({
                url: "{{ url('admin/add-operator') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    showToast(response.message, 'success');
                    $('#operatorForm')[0].reset(); // Reset form
                    $('#nameError').text('');
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors?.name) {
                        $('#nameError').text(errors.name[0]);
                        showToast(errors.name[0], 'danger'); // Show error toast
                    } else {
                        showToast("Something went wrong!", 'danger');
                    }
                },
                complete: function () {
                    submitButton.prop('disabled', false).text('Submit');
                }
            });
        });
        
        
        function fetchOperators() {
            $.ajax({
                url: "{{ url('admin/get-operators') }}",
                type: "GET",
                success: function (operators) {
                    let operatorTable = $('#operatorTableBody');
                    operatorTable.empty(); // Clear table body

                    if (operators.length > 0) {
                        operators.forEach((operator, index) => {
                            operatorTable.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${operator.name}</td>
                                    <td>
                                        <span class="badge ${operator.is_active == 1 ? 'bg-success' : 'bg-danger'}">
                                            ${operator.is_active == 1 ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning editOperator" data-id="${operator.id}" data-name="${operator.name}">Edit</button>
                                        <button class="btn btn-sm btn-danger deleteOperator" data-id="${operator.id}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        operatorTable.append('<tr><td colspan="4" class="text-center">No operators found.</td></tr>');
                    }
                },
                error: function () {
                    showToast("Failed to load operators", 'danger');
                }
            });
        }

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
