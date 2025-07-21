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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>

                    <!-- end card header -->
                    <div class="card-body">
                        <form id="rechargePlanForm">
                            @csrf
                            <div class="row">
                                <input type="hidden" class="form-control" value="2" name="service_id" required>
                                <input type="hidden" id="planId" value="{{ $dthPlan->id ?? '' }}">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label for="operator" class="form-label">Select Operator <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="provider_id" id="operatorSelect">
                                        <option value="">Select Operator</option>
                                        @foreach ($filteredProviders as $provider)
                                            <option value="{{ $provider['id'] }}"
                                                {{ isset($dthPlan) && $dthPlan->provider_id == $provider->id ? 'selected' : '' }}>
                                                {{ $provider['provider_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Plan Price <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="amount"
                                        value="{{ $dthPlan->amount ?? '' }}" placeholder="Enter Plan Price" required>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Plan Name </label>
                                    <input type="text" class="form-control" name="plan_name"
                                        value="{{ $dthPlan->plan_name ?? '' }}" placeholder="Enter Plan Name">
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Plan Validity</label>
                                    <input type="text" class="form-control" name="validity"
                                        value="{{ $dthPlan->validity ?? '' }}" placeholder="Plan Validity">
                                </div>
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="states" class="form-label">Select Languages<span
                                            class="text-danger">*</span></label>
                                    <div class="row border p-2" id="state-container">
                                        @foreach ($language as $row)
                                            <div class="col-lg-2 col-md-4 mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    id="state_{{ $row->id }}" name="languages[]"
                                                    value="{{ $row->name }}"
                                                    {{ isset($dthPlan) && is_array($dthPlan->languages) && in_array($row->name, $dthPlan->languages) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="state_{{ $row->id }}">
                                                    {{ $row->name }} </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label for="states" class="form-label">Channel Quality<span
                                            class="text-danger">*</span></label>
                                    <div class="row p-2" id="state-container">
                                        @php
                                            $selectedChannelQuality = isset($dthPlan->channel_quality)
                                                ? json_decode($dthPlan->channel_quality, true)
                                                : [];
                                        @endphp
                                        <div class="col-lg-2 col-md-4 mb-3">
                                            <input class="form-check-input" type="checkbox" name="channel_quality[]"
                                                value="SD"
                                                {{ in_array('SD', $selectedChannelQuality ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="channel_quality">
                                                SD </label>
                                        </div>
                                        <div class="col-lg-2 col-md-4 mb-3">
                                            <input class="form-check-input" type="checkbox" name="channel_quality[]"
                                                value="HD"
                                                {{ in_array('HD', $selectedChannelQuality ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="channel_quality">
                                                HD </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label class="form-label">Benefits:</label>
                                    <textarea name="benefits" class="form-control" rows="3">{{ $dthPlan->benefits ?? '' }}</textarea>
                                </div>
                                <div class="col-lg-12 col-md-12 mb-3">
                                    <label class="form-label">Channel Summary:</label>
                                    <textarea name="channel_summary" class="form-control" rows="3">{{ $dthPlan->channel_summary ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button class="btn btn-success w-100" id="btnSubmit" type="submit">Submit</button>
                            </div>
                        </form>

                        <!-- end col -->
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
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
                $('#rechargePlanForm').submit(function(event) {
                    event.preventDefault(); // Prevent default form submission

                    let formData = new FormData(this);
                    let planId = $('#planId').val();
                    let url = planId ? `{{ url('admin/add-dthplan/') }}/${planId}` :
                        `{{ url('admin/add-dthplan') }}`;
                    let method = planId ? 'POST' : 'POST';

                    $.ajax({
                        url: url,
                        type: method,
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function() {
                            $('#btnSubmit').prop('disabled', true).text('Processing...');
                        },
                        success: function(response) {
                            $('#btnSubmit').prop('disabled', false).text('Submit');

                            if (response.status === 'success') {
                                $('#toastMessage .toast-body').text(response.message);
                                $('#toastMessage').toast('show');

                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }
                        },
                        error: function(xhr) {
                            $('#btnSubmit').prop('disabled', false).text('Submit');

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorMessages = '';

                                $.each(errors, function(key, value) {
                                    errorMessages += value[0] + '\n';
                                });

                                alert(errorMessages);
                            } else {
                                alert('Something went wrong. Please try again.');
                            }
                        }
                    });
                });
            });
        </script>
    @stop
