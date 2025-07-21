@extends('retailer.layouts.master')

@section('styles')
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">{{ $title }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">DMT</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form id="kycForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" required placeholder="Enter mobile number">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Aadhaar Number</label>
                                    <input type="text" name="aadhaar_number" class="form-control" required placeholder="Enter Aadhaar number">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="lat" class="form-control" required placeholder="Auto-filled from location">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="long" class="form-control" required placeholder="Auto-filled from location">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Encrypted PID Data</label>
                                    <textarea name="data" class="form-control" rows="3" required placeholder="Paste encrypted PID data"></textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Is IRIS</label>
                                    <select name="is_iris" class="form-select">
                                        <option value="1">Yes</option>
                                        <option value="2" selected>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-success w-100" type="submit">Send OTP</button>
                            </div>
                        </form>

                        <form id="otpForm" style="display:none;" class="mt-3">
                            @csrf
                            <input type="hidden" name="stateresp" class="form-control">
                            <input type="hidden" name="ekyc_id" class="form-control">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Enter OTP</label>
                                    <input type="text" name="otp" class="form-control" required placeholder="Enter OTP received">
                                </div>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-primary w-100" type="submit">Verify OTP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <!-- Optional preview/result display -->
            </div>
        </div>
    </div>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Auto-fetch latitude and longitude
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            $('input[name="lat"]').val(position.coords.latitude);
            $('input[name="long"]').val(position.coords.longitude);
        }, function(error) {
            Swal.fire('Error', 'Location access denied. Please enable location services.', 'error');
        });
    } else {
        Swal.fire('Error', 'Geolocation is not supported by this browser.', 'error');
    }

    $('#kycForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: '{{ url('remitter.kyc.query') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire('Success!', 'OTP Sent successfully.', 'success');
                $('#otpForm').show();
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Something went wrong!', 'error');
            }
        });
    });

    $('#otpForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: '{{ url('remitter.kyc.register') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire('Success!', 'Remitter Registered Successfully.', 'success');
                $('#kycForm')[0].reset();
                $('#otpForm').hide();
            },
            error: function(xhr) {
                Swal.fire('Error!', 'OTP verification failed!', 'error');
            }
        });
    });
});
</script>
@stop
