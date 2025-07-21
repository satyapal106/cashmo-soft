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
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{ $title }}</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom-dashed">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm">
                                <h5 class="card-title mb-0">{{ $title }}</h5>
                            </div>
                            <div class="col-sm-auto">
                                <a href="javascript:void(0);" class="btn btn-success add-btn" data-bs-toggle="modal"
                                    data-bs-target="#addPackageModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Package
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-nowrap align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Package Name</th>
                                        {{-- @php
                                            $services = [
                                                'recharges',
                                                'money_transfer',
                                                'offer_dmt',
                                                'aeps',
                                                'aadhaar_pay',
                                                'payout',
                                                'upi',
                                            ];
                                        @endphp --}}

                                        @foreach ($services as $service)
                                            <th>{{ strtoupper(str_replace('_', ' ', $service->service_name)) }}</th>
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($packages as $key => $package)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $package->name }}</td>
                                            @foreach ($services as $service)
                                                <td>
                                                    <a href="{{ url('admin/' . strtolower($service->service_name) . '-commission-settings') }}?package_id={{ $package->id }}"
                                                        class="btn btn-success btn-sm">
                                                        Update
                                                    </a>
                                                </td>
                                            @endforeach


                                            <td>
                                                <button class="btn btn-warning btn-sm editPackageBtn"
                                                    data-id="{{ $package->id }}" data-name="{{ $package->name }}"
                                                    data-bs-toggle="modal" data-bs-target="#editPackageModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>

    <!-- Bootstrap Toast -->
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

    <!-- Add Package Modal -->
    <div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="addPackageForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="packageName" class="form-label">Package Name</label>
                            <input type="text" class="form-control" id="packageName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSavePackage" class="btn btn-primary">Save Package</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Package Modal -->
    <div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editPackageForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="editPackageId" name="id">
                        <div class="mb-3">
                            <label for="editPackageName" class="form-label">Package Name</label>
                            <input type="text" class="form-control" id="editPackageName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnUpdatePackage" class="btn btn-primary">Update Package</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        $(document).ready(function() {

            // Add Package Submit
            $('#addPackageForm').submit(function(event) {
                event.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ url('admin/package-store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#btnSavePackage').prop('disabled', true).text('Saving...');
                    },
                    success: function(response) {
                        $('#btnSavePackage').prop('disabled', false).text('Save Package');
                        if (response.status === 'success') {
                            $('#toastMessage .toast-body').text(response.message);
                            $('#toastMessage').toast('show');
                            $('#addPackageModal').modal('hide');
                            $('#addPackageForm')[0].reset();
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        $('#btnSavePackage').prop('disabled', false).text('Save Package');
                        alert('Something went wrong. Please try again.');
                    }
                });
            });

            // Set Edit Modal Values
            $('.editPackageBtn').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                $('#editPackageId').val(id);
                $('#editPackageName').val(name);
            });

            // Edit Package Submit
            $('#editPackageForm').submit(function(event) {
                event.preventDefault();
                let id = $('#editPackageId').val();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ url('admin/package-update') }}/" + id,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#btnUpdatePackage').prop('disabled', true).text('Updating...');
                    },
                    success: function(response) {
                        $('#btnUpdatePackage').prop('disabled', false).text('Update Package');
                        if (response.status === 'success') {
                            $('#toastMessage .toast-body').text(response.message);
                            $('#toastMessage').toast('show');
                            $('#editPackageModal').modal('hide');
                            $('#editPackageForm')[0].reset();
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        $('#btnUpdatePackage').prop('disabled', false).text('Update Package');
                        alert('Something went wrong. Please try again.');
                    }
                });
            });

        });
    </script>
@stop
