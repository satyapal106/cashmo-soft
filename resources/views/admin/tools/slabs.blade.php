@extends('admin.layouts.master')

@section('styles')
<!-- Sweet Alert css-->
<link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Recharge Plans</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Slab Form & Table -->
    <div class="row">
        <!-- Form -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ $title }}</h4>
                </div>
                <div class="card-body">
                    <form id="providerForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Service -->
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Select Service <span class="text-danger">*</span></label>
                                <select class="form-control" name="service_id" id="serviceSelect" required>
                                    <option value="">Select Service</option>
                                    @foreach ($service as $row)
                                    <option value="{{ $row->id }}">{{ $row->service_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Provider -->
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Select Provider <span class="text-danger">*</span></label>
                                <select class="form-control" name="provider_id" id="providerSelect" required>
                                    <option value="">Select Provider</option>
                                </select>
                            </div>

                            <!-- Amounts -->
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Min Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="min_amount" placeholder="Enter Min Amount" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Max Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="max_amount" placeholder="Enter Max Amount" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-success w-100" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">All {{ $title }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-nowrap align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Service</th>
                                    <th>Provider</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="slabsTableBody">
                                <!-- AJAX loaded -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="editSlabModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Slab</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSlabForm">
                <div class="modal-body">
                    <input type="hidden" id="editSlabId">
                    <div class="mb-3">
                        <label for="editSlabService" class="form-label">Service</label>
                        <select id="editSlabService" class="form-select">
                            <!-- Services will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editSlabProvider" class="form-label">Provider</label>
                        <select id="editSlabProvider" class="form-select">
                            <!-- Providers will be loaded dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editSlabMinAmount" class="form-label">Min Amount</label>
                        <input type="number" id="editSlabMinAmount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSlabMaxAmount" class="form-label">Max Amount</label>
                        <input type="number" id="editSlabMaxAmount" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Slab</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Toast -->
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
<!-- Sweet Alerts -->
<script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ asset('assets') }}/js/pages/sweetalerts.init.js"></script>

<script>
    $(document).ready(function () {
        fetchSlabs();
    
        // Add Slab
        $("#providerForm").submit(function(e) {
            e.preventDefault();
    
            $.ajax({
                url: "{{ url('admin/add-slab') }}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        showToast(response.message, "success");
                        $("#providerForm")[0].reset();
                        fetchSlabs();
                    } else {
                        showToast("Failed to add slab!", "danger");
                    }
                },
                error: function(xhr) {
                    handleFormErrors(xhr);
                }
            });
        });
    
        // Fetch slabs
        function fetchSlabs() {
            $.ajax({
                url: "{{ url('admin/get-slabs') }}",
                type: "GET",
                success: function(slabs) {
                    let slabsTable = $('#slabsTableBody');
                    slabsTable.empty();
    
                    if (slabs.length > 0) {
                        slabs.forEach((slab, index) => {
                            slabsTable.append(`
                                <tr id="slabRow-${slab.id}">
                                    <td>${index + 1}</td>
                                    <td>${slab.service.service_name}</td>
                                    <td>${slab.provider.provider_name}</td>
                                    <td>${slab.min_amount}</td>
                                    <td>${slab.max_amount}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggleStatus" type="checkbox"
                                                data-id="${slab.id}" ${slab.status == 1 ? 'checked' : ''}>
                                            <label class="form-check-label">
                                                ${slab.status == 1 ? 'Active' : 'Inactive'}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning editSlab"
                                            data-id="${slab.id}" 
                                            data-service="${slab.service.id}" 
                                            data-provider="${slab.provider.id}" 
                                            data-min="${slab.min_amount}" 
                                            data-max="${slab.max_amount}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger deleteProvider" data-id="${slab.id}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        slabsTable.append('<tr><td colspan="7" class="text-center">No slabs found.</td></tr>');
                    }
                },
                error: function() {
                    showToast("Failed to load slabs", 'danger');
                }
            });
        }
    
        // Edit Slab button click
        $(document).on("click", ".editSlab", function() {
            let slabId = $(this).data("id");
            let serviceId = $(this).data("service");
            let providerId = $(this).data("provider");
            let minAmount = $(this).data("min");
            let maxAmount = $(this).data("max");
    
            $("#editSlabId").val(slabId);
            $("#editSlabMinAmount").val(minAmount);
            $("#editSlabMaxAmount").val(maxAmount);
    
            populateDropdown("#editSlabService", "{{ url('admin/get-services') }}", serviceId);
            populateDropdown("#editSlabProvider", "{{ url('admin/get-providers') }}", providerId);
    
            $("#editSlabModal").modal("show");
        });
    
        // Submit Edit Slab Form
        $("#editSlabForm").submit(function(e) {
            e.preventDefault();
    
            let slabId = $("#editSlabId").val();
    
            $.ajax({
                url: "{{ url('admin/update-slab') }}/" + slabId,
                type: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: {
                    service_id: $("#editSlabService").val(),
                    provider_id: $("#editSlabProvider").val(),
                    min_amount: $("#editSlabMinAmount").val(),
                    max_amount: $("#editSlabMaxAmount").val()
                },
                success: function(response) {
                    $("#editSlabModal").modal("hide");
                    showToast("Slab updated successfully", "success");
                    fetchSlabs();
                },
                error: function(xhr) {
                    handleFormErrors(xhr);
                }
            });
        });
    
        // Service dropdown -> Load providers
        $('#serviceSelect').on('change', function () {
            let serviceId = $(this).val();
            let providerDropdown = $('#providerSelect');
    
            providerDropdown.empty().append('<option value="">Select Provider</option>');
    
            if (serviceId) {
                $.ajax({
                    url: "{{ url('admin/get-providers-by-service') }}/" + serviceId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $.each(response.providers, function (_, provider) {
                                providerDropdown.append(`<option value="${provider.id}">${provider.provider_name}</option>`);
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Error:', xhr.responseText);
                    }
                });
            }
        });
    
        // Utility: populate dropdown
        function populateDropdown(selector, url, selectedId = null) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(data) {
                    let dropdown = $(selector);
                    dropdown.empty();
    
                    data.forEach(item => {
                        let isSelected = item.id == selectedId ? "selected" : "";
                        let text = item.service_name || item.provider_name || item.name;
                        dropdown.append(`<option value="${item.id}" ${isSelected}>${text}</option>`);
                    });
                },
                error: function() {
                    showToast("Failed to load dropdown data", "danger");
                }
            });
        }
    
        // Utility: toast message
        function showToast(message, type = 'success') {
            let toast = $('#toastMessage');
            toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
            toast.find('.toast-body').text(message);
            let bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    
        // Utility: show form errors
        function handleFormErrors(xhr) {
            let errors = xhr.responseJSON?.errors;
            if (errors) {
                let firstKey = Object.keys(errors)[0];
                showToast(errors[firstKey][0], "danger");
            } else {
                showToast("Something went wrong!", "danger");
            }
        }
    });
    </script>
    
@stop
