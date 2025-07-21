@extends('admin.layouts.master')
@section('styles')
    <style>
        .select-cashback {
            color: green;
        }

        .select-charge {
            color: red;
        }

        .select-percent {
            color: blue;
        }

        .select-flat {
            color: orange;
        }
    </style>
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
                            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $title }}</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slab Commission Table -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom-dashed d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Slab Commission</h5>
                        <select class="form-select w-auto" id="providerFilter">
                            <option value="">All Providers</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->provider_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="slabTable">
                                <thead>
                                    <tr>
                                        <th>SR NO</th>
                                        <th>SLABS</th>
                                        <th>CHARGE/CASHBACK</th>
                                        <th>PRICE TYPE</th>
                                        <th>PRICE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($slabs as $index => $slab)
                                        @php
                                            $commission = $commissions[$slab->id] ?? null;
                                        @endphp
                                        <tr data-slab-id="{{ $slab->id }}" data-provider-id="{{ $slab->provider_id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $slab->min_amount }} - {{ $slab->max_amount }}</td>
                                            <td>
                                                <select class="form-control nature" data-id="{{ $slab->id }}">
                                                    <option value="cashback"
                                                        {{ $commission && $commission->nature == 'cashback' ? 'selected' : '' }}>
                                                        Cashback</option>
                                                    <option value="charge"
                                                        {{ $commission && $commission->nature == 'charge' ? 'selected' : '' }}>
                                                        Charge</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control type" data-id="{{ $slab->id }}">
                                                    <option value="%"
                                                        {{ $commission && $commission->type == '%' ? 'selected' : '' }}>%
                                                    </option>
                                                    <option value="flat"
                                                        {{ $commission && $commission->type == 'flat' ? 'selected' : '' }}>
                                                        Rs</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01" class="form-control value"
                                                    data-id="{{ $slab->id }}" value="{{ $commission->value ?? 0 }}">
                                            </td>
                                            <td>
                                                <button class="btn btn-success btn-sm update-btn"
                                                    data-id="{{ $slab->id }}" data-provider="{{ $slab->provider_id }}"
                                                    data-service="{{ $slab->provider->service_id }}"
                                                    data-package="{{ $package_id }}">Update</button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($slabs->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No slab data found.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>

    </div>

    <!-- Toast Notification -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="toastMessage" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.update-btn').on('click', function() {
                let btn = $(this);
                let slab_id = btn.data('id');
                let provider_id = btn.data('provider');
                let service_id = btn.data('service');
                let package_id = btn.data('package');

                let nature = $('.nature[data-id="' + slab_id + '"]').val();
                let type = $('.type[data-id="' + slab_id + '"]').val();
                let value = $('.value[data-id="' + slab_id + '"]').val();

                $.ajax({
                    url: "{{ url('admin/update-commission') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        slab_id: slab_id,
                        provider_id: provider_id,
                        service_id: service_id,
                        package_id: package_id,
                        nature: nature,
                        type: type,
                        value: value
                    },
                    success: function(response) {
                        if (response.status === true) {
                            showToast(response.message || 'Updated successfully.', 'success');
                        } else {
                            showToast(response.message || 'Update failed.', 'danger');
                        }
                    },
                    error: function(xhr) {
                        showToast('Something went wrong. Please try again.', 'danger');
                    }
                });
            });

            function showToast(message, type = 'success') {
                let toast = $('#toastMessage');
                toast.removeClass('bg-success bg-danger').addClass(`bg-${type}`);
                toast.find('.toast-body').text(message);
                let bsToast = new bootstrap.Toast(toast[0]);
                bsToast.show();
            }


            function updateSelectColors() {
                $('.nature').each(function() {
                    var val = $(this).val();
                    $(this).removeClass('select-cashback select-charge');
                    if (val === 'cashback') {
                        $(this).addClass('select-cashback');
                    } else if (val === 'charge') {
                        $(this).addClass('select-charge');
                    }
                });

                $('.type').each(function() {
                    var val = $(this).val();
                    $(this).removeClass('select-percent select-flat');
                    if (val === '%') {
                        $(this).addClass('select-percent');
                    } else if (val === 'flat') {
                        $(this).addClass('select-flat');
                    }
                });
            }

            // Initial color update
            updateSelectColors();

            // Update on change
            $('.nature, .type').on('change', function() {
                updateSelectColors();
            });
        });


        $('#providerFilter').on('change', function() {
            const selectedProvider = $(this).val();

            $('#slabTable tbody tr').each(function() {
                const providerId = $(this).data('provider-id');

                if (selectedProvider === "" || selectedProvider == providerId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    </script>
@endsection
