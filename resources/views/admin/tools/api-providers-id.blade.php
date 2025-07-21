@extends('admin.layouts.master')

@section('styles')
    <style>
        .select-cashback { color: green; }
        .select-charge { color: red; }
        .select-percent { color: blue; }
        .select-flat { color: orange; }
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

        <!-- API Provider Mapping Table -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom-dashed d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Update API Providers</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="slabTable">
                                <thead>
                                    <tr>
                                        <th>SR NO</th>
                                        <th>Provider Name</th>
                                        @foreach ($api_providers as $provide)
                                            <th>{{ $provide->name }}</th>
                                        @endforeach
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($providers as $key => $row)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $row->provider_name }}</td>
                                            @foreach ($api_providers as $provider)
                                                <td>
                                                    <input type="text"
                                                        name="api_value[{{ $row->id }}][{{ $provider->id }}]"
                                                        class="form-control form-control-sm api-input"
                                                        data-row-id="{{ $row->id }}"
                                                        data-api-provider-id="{{ $provider->id }}"
                                                        value="{{ $row->api_data[$provider->id] ?? '' }}"
                                                        placeholder="Enter ID" />
                                                </td>
                                            @endforeach
                                            <td>
                                                <button type="button"
                                                    class="btn btn-success btn-sm update-btn"
                                                    data-id="{{ $row->id }}"
                                                    data-provider="{{ $row->id }}"
                                                    data-service="{{ $row->service_id ?? 0 }}">
                                                    Update
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if ($providers->isEmpty())
                                        <tr>
                                            <td colspan="{{ 3 + count($api_providers) }}" class="text-center text-muted">No provider data found.</td>
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
                <div class="toast-body">Update successful</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.update-btn').on('click', function () {
            const rowId = $(this).data('id');
            const providerId = $(this).data('provider');

            // Collect input values for this row
            let apiValues = {};
            $(`input[data-row-id="${rowId}"]`).each(function () {
                const apiProviderId = $(this).data('api-provider-id');
                const value = $(this).val();
                apiValues[apiProviderId] = value;
            });

            $.ajax({
                url: '{{ url("admin/update-provider-mapping") }}', // Your route name
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    provider_id: providerId,
                    values: apiValues
                },
                success: function (response) {
                    $('#toastMessage').find('.toast-body').text(response.message || 'Updated successfully');
                    const toast = new bootstrap.Toast(document.getElementById('toastMessage'));
                    toast.show();
                },
                error: function () {
                    alert('Update failed. Please try again.');
                }
            });
        });
    });
</script>
@endsection
