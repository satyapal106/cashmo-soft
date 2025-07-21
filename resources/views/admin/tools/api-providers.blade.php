@extends('admin.layouts.master')

@section('styles')
    <link href="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">{{ $title }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">API Providers</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                </div>
                <div class="card-body">
                    <form id="providerForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">API Provider Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="API Provider Name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">API Token <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="api_token" placeholder="API Token">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">API Base URL</label>
                            <input type="text" class="form-control" name="base_url" placeholder="API Base URL">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Providers</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Token</th>
                                    <th>Base URL</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="providersTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProviderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProviderForm">
                    <input type="hidden" id="editProviderId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="editProviderName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Token</label>
                        <input type="text" class="form-control" id="editProviderToken" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Base URL</label>
                        <input type="text" class="form-control" id="editProviderUrl">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
    <div id="toastMessage" class="toast text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script src="{{ asset('assets') }}/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
$(function() {
    fetchProviders();

    $('#providerForm').on('submit', function(e) {
        e.preventDefault();
        let form = new FormData(this);
        form.append('status', $('input[name="status"]').is(':checked') ? 1 : 0);

        $.ajax({
            url: "{{ url('admin/add-api-provider') }}",
            method: 'POST',
            data: form,
            processData: false,
            contentType: false,
            success: function(res) {
                showToast(res.message, 'success');
                $('#providerForm')[0].reset();
                fetchProviders();
            }
        });
    });

    function fetchProviders() {
        $.get("{{ url('admin/get-api-providers') }}", function(providers) {
            let html = '';
            providers.forEach((p, i) => {
                html += `<tr>
                    <td>${i+1}</td>
                    <td>${p.name}</td>
                    <td>${p.api_token}</td>
                    <td>${p.base_url || '-'}</td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input toggleStatus" data-id="${p.id}" type="checkbox" ${p.status == 1 ? 'checked' : ''}>
                            <label class="form-check-label">${p.status == 1 ? 'Active' : 'Inactive'}</label>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning editProvider" data-id="${p.id}" data-name="${p.name}" data-token="${p.api_token}" data-url="${p.base_url}" data-status="${p.status}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteProvider" data-id="${p.id}">Delete</button>
                    </td>
                </tr>`;
            });
            $('#providersTableBody').html(html);
        });
    }

    $(document).on('click', '.editProvider', function() {
        $('#editProviderId').val($(this).data('id'));
        $('#editProviderName').val($(this).data('name'));
        $('#editProviderToken').val($(this).data('token'));
        $('#editProviderUrl').val($(this).data('url'));
        $('#editProviderStatus').prop('checked', $(this).data('status') == 1);
        $('#editProviderModal').modal('show');
    });

    $('#editProviderForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editProviderId').val();
        let form = {
            _token: "{{ csrf_token() }}",
            name: $('#editProviderName').val(),
            api_token: $('#editProviderToken').val(),
            base_url: $('#editProviderUrl').val(),
            status: $('#editProviderStatus').is(':checked') ? 1 : 0
        };

        $.post("{{ url('admin/update-api-provider') }}/" + id, form, function(res) {
            $('#editProviderModal').modal('hide');
            showToast(res.message);
            fetchProviders();
        });
    });

    $(document).on('change', '.toggleStatus', function() {
        let id = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;
        $.post("{{ url('admin/update-provider-status') }}/" + id, {
            _token: "{{ csrf_token() }}",
            status: status
        }, function(res) {
            showToast(res.message);
        });
    });

    $(document).on('click', '.deleteProvider', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/delete-provider') }}/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(res) {
                        fetchProviders();
                        showToast(res.message);
                    }
                });
            }
        });
    });

    function showToast(message, type = 'success') {
        let toast = $('#toastMessage');
        toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
        toast.find('.toast-body').text(message);
        new bootstrap.Toast(toast).show();
    }
});
</script>
@stop
